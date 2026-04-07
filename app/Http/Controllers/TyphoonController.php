<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\FireSafetyNotification;
use App\Models\School;
use App\Models\TypFldFamily;
use App\Models\TypFldFamilyMember;
use App\Models\TypFldMonitoringSnapshot;
use App\Models\SchoolSpecificsInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TyphoonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Schools that completed Typhoon/Flood evacuation-center registration (not the whole directory).
     */
    private function typhoonRegisteredEvacuationCentersQuery()
    {
        return School::query()
            ->whereHas('specifics', function ($q) {
                $q->where('module', 'typhoon_flood')->where('key', 'original_evacuation_center_id');
            })
            ->orderBy('school_name');
    }

    /**
     * Main-directory schools not yet registered for Typhoon/Flood monitoring.
     */
    private function schoolsNotYetRegisteredForTyphoon()
    {
        return School::query()
            ->whereDoesntHave('specifics', function ($q) {
                $q->where('module', 'typhoon_flood')->where('key', 'original_evacuation_center_id');
            })
            ->orderBy('school_name')
            ->get();
    }

    private function contributorTyphoonSchoolIds($user): ?array
    {
        if ($user->role === 'admin') {
            return null;
        }
        if ($user->typhoon_school_id) {
            return [(int) $user->typhoon_school_id];
        }
        if ($user->school_id) {
            return [(int) $user->school_id];
        }

        return [];
    }

    private function attachCurrentOccupancy($ec)
    {
        $ec->current_occupancy = TypFldFamilyMember::query()
            ->join('typ_fld_families', 'typ_fld_families.id', '=', 'typ_fld_family_members.family_id')
            ->where('typ_fld_families.school_id', $ec->id)
            ->whereNull('typ_fld_families.checked_out_at')
            ->count();

        return $ec;
    }

    public function dashboard()
    {
        $user = auth()->user();

        $evacuationCentersQuery = $this->typhoonRegisteredEvacuationCentersQuery();
        if ($user->role !== 'admin') {
            $ids = $this->contributorTyphoonSchoolIds($user);
            if ($ids === null || count($ids) === 0) {
                $evacuationCentersQuery->whereRaw('1=0');
            } else {
                $evacuationCentersQuery->whereIn('id', $ids);
            }
        }

        $evacuationCenters = $evacuationCentersQuery->get()->map(fn ($ec) => $this->attachCurrentOccupancy($ec));

        $globalFamiliesQuery = TypFldFamily::query();
        $globalMembersQuery = TypFldFamilyMember::query();
        $activeFamiliesQuery = (clone $globalFamiliesQuery)->whereNull('checked_out_at');

        $totalFamilies = (clone $activeFamiliesQuery)->count();
        $totalEvacuees = (clone $globalMembersQuery)
            ->join('typ_fld_families as f', 'f.id', '=', 'typ_fld_family_members.family_id')
            ->whereNull('f.checked_out_at')
            ->count();

        $missingCount = (clone $globalMembersQuery)->where('status', 'missing')->count();
        $injuredCount = (clone $globalMembersQuery)->where('status', 'injured')->count();
        $deceasedCount = (clone $globalMembersQuery)->where('status', 'deceased')->count();

        $vulnerableCounts = [
            'pregnant' => (clone $activeFamiliesQuery)->where('has_pregnant', true)->count(),
            'pwd' => (clone $activeFamiliesQuery)->where('has_pwd', true)->count(),
            'senior' => (clone $activeFamiliesQuery)->where('has_senior', true)->count(),
        ];

        $openEvacuationCentersCount = School::where('evacuation_status', '!=', 'cleared')->count();

        $contributorIds = $this->contributorTyphoonSchoolIds($user);
        $activeCenter = ($contributorIds && count($contributorIds) === 1)
            ? School::find($contributorIds[0])
            : null;

        $floodMonitoring = null;
        if ($activeCenter) {
            $floodMonitoring = TypFldMonitoringSnapshot::where('school_id', $activeCenter->id)
                ->where('type', 'flood')
                ->latest('recorded_at')
                ->first();
        }

        $weatherData = cache()->remember('typhoon_weather_data', 1800, function () {
            try {
                $lat = 14.83;
                $lon = 120.28;
                $url = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lon}&current=temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m,precipitation&daily=weather_code,temperature_2m_max,temperature_2m_min,precipitation_sum&timezone=Asia%2FSingapore";
                $response = file_get_contents($url);

                return json_decode($response, true);
            } catch (\Exception $e) {
                return null;
            }
        });

        $weatherDesc = 'Clear';
        if (isset($weatherData['current']['weather_code'])) {
            $code = $weatherData['current']['weather_code'];
            $codes = [
                0 => 'Clear Sky', 1 => 'Mainly Clear', 2 => 'Partly Cloudy', 3 => 'Overcast',
                45 => 'Fog', 48 => 'Depositing Rime Fog',
                51 => 'Light Drizzle', 53 => 'Moderate Drizzle', 55 => 'Dense Drizzle',
                61 => 'Slight Rain', 63 => 'Moderate Rain', 65 => 'Heavy Rain',
                80 => 'Slight Rain Showers', 81 => 'Moderate Rain Showers', 82 => 'Violent Rain Showers',
                95 => 'Thunderstorm', 96 => 'Thunderstorm with Hail', 99 => 'Heavy Thunderstorm',
            ];
            $weatherDesc = $codes[$code] ?? 'Cloudy';
        }

        $dailyRainfallSum = $weatherData['daily']['precipitation_sum'][0] ?? 0.0;

        $activeTyphoon = cache()->remember('gdacs_active_typhoon_ph', 1800, function () {
            $targetLat = 14.838;
            $targetLon = 120.282;
            $radiusKm = 500;

            try {
                $url = 'https://www.gdacs.org/gdacsapi/api/events/geteventlist/SEARCH'
                     . '?eventtypes=TC&fromdate=' . now()->subDays(7)->format('Y-m-d')
                     . '&todate=' . now()->addDays(7)->format('Y-m-d')
                     . '&alertlevel=&pagenumber=1&pagesize=50';

                $ctx = stream_context_create(['http' => ['timeout' => 8]]);
                $raw = @file_get_contents($url, false, $ctx);
                if (!$raw) {
                    return null;
                }

                $data = json_decode($raw, true);
                $features = $data['features'] ?? [];

                $haversine = function ($lat1, $lon1, $lat2, $lon2) {
                    $R = 6371;
                    $dLat = deg2rad($lat2 - $lat1);
                    $dLon = deg2rad($lon2 - $lon1);
                    $a = sin($dLat / 2) ** 2
                            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
                            * sin($dLon / 2) ** 2;

                    return $R * 2 * atan2(sqrt($a), sqrt(1 - $a));
                };

                $closest = null;
                $closestDist = PHP_INT_MAX;

                foreach ($features as $f) {
                    $coords = $f['geometry']['coordinates'] ?? null;
                    if (!$coords) {
                        continue;
                    }

                    $lon = (float) $coords[0];
                    $lat = (float) $coords[1];

                    $distKm = $haversine($targetLat, $targetLon, $lat, $lon);

                    if ($distKm <= $radiusKm && $distKm < $closestDist) {
                        $closestDist = $distKm;
                        $props = $f['properties'] ?? [];
                        $rawName = $props['name'] ?? ($props['htmldescription'] ?? 'Unnamed');
                        $alertLevel = strtolower($props['alertlevel'] ?? 'green');
                        $windKph = isset($props['maxwind']) ? round($props['maxwind'] * 3.6) : 0;

                        if ($windKph >= 221) {
                            $signal = 5;
                        } elseif ($windKph >= 180) {
                            $signal = 4;
                        } elseif ($windKph >= 120) {
                            $signal = 3;
                        } elseif ($windKph >= 60) {
                            $signal = 2;
                        } else {
                            $signal = $alertLevel === 'red' ? 3 : ($alertLevel === 'orange' ? 2 : 1);
                        }

                        if ($signal >= 5) {
                            $category = 'Super Typhoon';
                        } elseif ($signal >= 3) {
                            $category = 'Typhoon';
                        } elseif ($signal >= 2) {
                            $category = 'Tropical Storm';
                        } else {
                            $category = 'Tropical Depression';
                        }

                        $cleanName = trim(preg_replace('/^TC\s*\d+[A-Z\d]+\s*/i', '', $rawName));
                        if (empty($cleanName) || strlen($cleanName) < 2) {
                            $cleanName = $props['eventid'] ?? 'Unnamed';
                        }

                        $closest = [
                            'name' => strtoupper($cleanName),
                            'category' => $category,
                            'signal' => $signal,
                            'wind_kph' => $windKph,
                            'distance_km' => round($distKm),
                            'lat' => $lat,
                            'lon' => $lon,
                        ];
                    }
                }

                return $closest;
            } catch (\Exception $e) {
                return null;
            }
        });

        $contributorActiveSchoolId = ($contributorIds && count($contributorIds) === 1) ? $contributorIds[0] : null;

        $unregisteredSchools = $this->schoolsNotYetRegisteredForTyphoon();

        return view('typhoon.dashboard', [
            'evacuationCenters' => $evacuationCenters,
            'totalFamilies' => $totalFamilies,
            'totalEvacuees' => $totalEvacuees,
            'openEvacuationCentersCount' => $openEvacuationCentersCount,
            'incidentMonitoring' => ['major' => 0, 'minor' => 0],
            'rainfall' => [
                'bangal' => number_format($dailyRainfallSum * 0.95, 2),
                'kalaklan' => number_format($dailyRainfallSum * 1.05, 2),
            ],
            'missingCount' => $missingCount,
            'injuredCount' => $injuredCount,
            'deceasedCount' => $deceasedCount,
            'vulnerableCounts' => $vulnerableCounts,
            'recentEvacuees' => $totalEvacuees > 0,
            'recentlyRegistered' => (clone $activeFamiliesQuery)->whereDate('created_at', Carbon::today())->count(),
            'floodMonitoring' => $floodMonitoring ? (object) ($floodMonitoring->payload ?? []) : null,
            'typhoonData' => (object) [
                'name' => $weatherDesc,
                'temp' => $weatherData['current']['temperature_2m'] ?? '--',
                'wind' => $weatherData['current']['wind_speed_10m'] ?? '--',
            ],
            'activeSchoolId' => $contributorActiveSchoolId,
            'activeTyphoon' => $activeTyphoon,
            'unregisteredSchools' => $unregisteredSchools,
            'quickAnnouncements' => FireSafetyNotification::forCompliance('typhoon_flood')
                ->where(function ($q) use ($contributorActiveSchoolId) {
                    $q->whereNull('school_id')
                        ->orWhere('school_id', $contributorActiveSchoolId);
                })
                ->where('type', 'announcement')
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }

    public function chooseSchool()
    {
        $user = auth()->user();

        $centersQuery = $this->typhoonRegisteredEvacuationCentersQuery();
        if ($user->role !== 'admin') {
            $ids = $this->contributorTyphoonSchoolIds($user);
            if (!$ids || count($ids) === 0) {
                $centersQuery->whereRaw('1=0');
            } else {
                $centersQuery->whereIn('id', $ids);
            }
        }

        $schools = $centersQuery->get()->map(function ($ec) {
            $occupancy = TypFldFamilyMember::query()
                ->join('typ_fld_families', 'typ_fld_families.id', '=', 'typ_fld_family_members.family_id')
                ->where('typ_fld_families.school_id', $ec->id)
                ->whereNull('typ_fld_families.checked_out_at')
                ->count();

            $school = $ec;
            $school->typ_ec = $ec;
            $school->typ_ec_current_occupancy = $occupancy;

            return $school;
        });

        $activeSchoolId = session('typhoon_active_school_id');
        if ($user->role !== 'admin') {
            $activeSchoolId = $user->school_id;
        }

        return view('typhoon.choose-school', [
            'schools' => $schools,
            'activeSchoolId' => $activeSchoolId,
        ]);
    }

    public function setActiveSchool(Request $request, $id)
    {
        $school = School::findOrFail($id);

        if (auth()->user()->role !== 'admin' && (int) auth()->user()->school_id !== (int) $school->id && (int) auth()->user()->typhoon_school_id !== (int) $school->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        session(['typhoon_active_school_id' => $school->id]);

        return response()->json(['success' => true]);
    }

    public function storeFamily(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'evacuation_center_id' => 'required|integer|exists:schools,id',
            'head_family_name' => 'required|string|max:255',
            'collective_needs' => 'nullable|string|max:2000',
            'has_pregnant' => 'nullable|boolean',
            'has_pwd' => 'nullable|boolean',
            'has_senior' => 'nullable|boolean',
            'has_lactating' => 'nullable|boolean',
            'has_child_under5' => 'nullable|boolean',
            'confirm_check_in' => 'nullable|in:on,1,true',
            'members' => 'required|array|min:1',
            'members.*.full_name' => 'required|string|max:255',
            'members.*.age' => 'required|integer|min:0|max:120',
            'members.*.gender' => 'required|in:male,female',
            'members.*.needs' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $ec = School::findOrFail($request->evacuation_center_id);

        $currentOccupancyCount = TypFldFamilyMember::query()
            ->join('typ_fld_families', 'typ_fld_families.id', '=', 'typ_fld_family_members.family_id')
            ->where('typ_fld_families.school_id', $ec->id)
            ->whereNull('typ_fld_families.checked_out_at')
            ->count();

        $capacity = $ec->evacuation_capacity;
        if ($capacity > 0 && $currentOccupancyCount >= $capacity) {
            return redirect()->back()->with('error', "Evacuation Center is full (Capacity: {$capacity}, Current: {$currentOccupancyCount}). Cannot register more families.")->withInput();
        }

        $user = auth()->user();
        if ($user->role !== 'admin' && (int) $user->school_id !== (int) $ec->id && (int) $user->typhoon_school_id !== (int) $ec->id) {
            return redirect()->back()->with('error', 'Unauthorized evacuation center.');
        }

        DB::transaction(function () use ($request, $ec) {
            $family = TypFldFamily::create([
                'school_id' => $ec->id,
                'head_family_name' => $request->head_family_name,
                'collective_needs' => $request->collective_needs,
                'has_pregnant' => (bool) $request->has_pregnant,
                'has_pwd' => (bool) $request->has_pwd,
                'has_senior' => (bool) $request->has_senior,
                'has_lactating' => (bool) $request->has_lactating,
                'has_child_under5' => (bool) $request->has_child_under5,
                'checked_in_at' => $request->confirm_check_in ? now() : null,
            ]);

            $members = $request->members ?? [];
            foreach ($members as $idx => $m) {
                TypFldFamilyMember::create([
                    'family_id' => $family->id,
                    'full_name' => $m['full_name'],
                    'age' => (int) $m['age'],
                    'gender' => $m['gender'],
                    'needs' => $m['needs'] ?? null,
                    'is_head' => $idx === 0,
                    'status' => 'normal',
                ]);
            }
        });

        ActivityLog::log('typhoon_flood', 'Registered family: ' . $request->head_family_name, [
            'school_id' => $ec->id,
            'notes' => $request->collective_needs,
        ]);

        return redirect()->route('typhoon.dashboard')->with('success', 'Family registered successfully.');
    }

    public function showEvacuationCenter($id)
    {
        $ec = School::findOrFail($id);
        $user = auth()->user();
        if ($user->role !== 'admin' && (int) $user->school_id !== (int) $ec->id && (int) $user->typhoon_school_id !== (int) $ec->id) {
            abort(403, 'Unauthorized');
        }

        session(['typhoon_active_school_id' => $ec->id]);

        $families = TypFldFamily::withCount('members')
            ->where('school_id', $ec->id)
            ->latest()
            ->get();

        $lastUsedAt = $families->max('created_at');

        $currentOccupancy = TypFldFamilyMember::query()
            ->join('typ_fld_families', 'typ_fld_families.id', '=', 'typ_fld_family_members.family_id')
            ->where('typ_fld_families.school_id', $ec->id)
            ->whereNull('typ_fld_families.checked_out_at')
            ->count();

        $evacuationCenters = $this->typhoonRegisteredEvacuationCentersQuery()->get()->map(function ($center) {
            return $this->attachCurrentOccupancy($center);
        });

        $quickAnnouncements = FireSafetyNotification::forCompliance('typhoon_flood')
            ->where(function ($q) use ($ec) {
                $q->whereNull('school_id')->orWhere('school_id', $ec->id);
            })
            ->where('type', 'announcement')
            ->latest()
            ->take(10)
            ->get();

        return view('typhoon.evacuation-center', [
            'ec' => $ec,
            'families' => $families,
            'lastUsedAt' => $lastUsedAt,
            'currentOccupancy' => $currentOccupancy,
            'evacuationCenters' => $evacuationCenters,
            'quickAnnouncements' => $quickAnnouncements,
        ]);
    }

    public function storeEvacuationCenter(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'existing_school_id' => 'required|integer|exists:schools,id',
            'usage_status' => 'required|string|in:cleared,occupied,full,decamp',
            'emergency_resources' => 'nullable|string|max:2000',
            'capacity' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $schoolRow = School::findOrFail($request->existing_school_id);

        if (SchoolSpecificsInformation::where('school_id', $schoolRow->id)
            ->where('module', 'typhoon_flood')
            ->where('key', 'original_evacuation_center_id')
            ->exists()) {
            return redirect()->back()->with('error', 'This school is already registered as an evacuation center.');
        }

        $schoolRow->identification = $schoolRow->identification ?: $schoolRow->school_id ?: $schoolRow->school_id_number;
        $schoolRow->evacuation_identification = $schoolRow->identification;
        $schoolRow->evacuation_location = $schoolRow->evacuation_location ?: $schoolRow->address;
        $schoolRow->evacuation_capacity = (int) $request->capacity;
        $schoolRow->evacuation_status = $request->usage_status;
        $schoolRow->emergency_resources = $request->emergency_resources;
        $schoolRow->operational_status = $schoolRow->operational_status ?: 'operational';
        $schoolRow->monitoring_status = $schoolRow->monitoring_status ?: 'Active';
        $schoolRow->save();

        DB::table('school_specifics_information')->updateOrInsert(
            [
                'school_id' => $schoolRow->id,
                'module' => 'typhoon_flood',
                'key' => 'original_evacuation_center_id',
            ],
            [
                'value' => (string) $schoolRow->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        session(['typhoon_active_school_id' => $schoolRow->id]);

        ActivityLog::log('typhoon_flood', 'Created evacuation center: ' . $schoolRow->school_name, [
            'school_id' => $schoolRow->id,
        ]);

        return redirect()->route('typhoon.dashboard')->with('success', 'Evacuation center created.');
    }

    public function updateEvacuationCenter(Request $request, $id)
    {
        $ec = School::findOrFail($id);
        $user = auth()->user();
        if ($user->role !== 'admin' && (int) $user->school_id !== (int) $ec->id && (int) $user->typhoon_school_id !== (int) $ec->id) {
            abort(403, 'Unauthorized');
        }

        $validator = Validator::make($request->all(), [
            'usage_status' => 'required|string|in:cleared,occupied,full,decamp',
            'emergency_resources' => 'nullable|string|max:2000',
            'reports_status' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $ec->evacuation_status = $request->usage_status;
        $ec->emergency_resources = $request->emergency_resources;
        $ec->reports_status = $request->reports_status;
        $ec->save();

        FireSafetyNotification::create([
            'compliance_type' => 'typhoon_flood',
            'module' => 'evacuation_center',
            'school_id' => $ec->id,
            'user_id' => $user->id,
            'type' => 'update',
            'title' => 'Site Intelligence Updated',
            'message' => "Contributor {$user->name} updated site {$ec->school_name}. Status: " . strtoupper($ec->usage_status),
            'action_type' => 'view_site',
            'action_url' => route('typhoon.evacuation-center.show', $ec->id),
            'is_read' => false,
        ]);

        ActivityLog::log('typhoon_flood', 'Updated evacuation center: ' . ($ec->identification ?? $ec->school_name), [
            'school_id' => $ec->id,
            'notes' => "Status: {$ec->usage_status}",
        ]);

        return redirect()->route('typhoon.evacuation-center.show', $ec->id)->with('success', 'Evacuation center updated.');
    }

    public function storeAnnouncement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'school_id' => 'nullable|integer|exists:schools,id',
            'urgency' => 'required|in:info,warning,danger',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $user = auth()->user();

        FireSafetyNotification::create([
            'compliance_type' => 'typhoon_flood',
            'module' => 'announcement',
            'school_id' => $request->school_id,
            'user_id' => $user->id,
            'type' => 'announcement',
            'title' => $request->title,
            'message' => $request->message,
            'action_type' => 'mark_read',
            'is_read' => false,
            'action_data' => ['urgency' => $request->urgency],
        ]);

        return redirect()->back()->with('success', 'Announcement posted successfully.');
    }

    public function notifications()
    {
        $user = auth()->user();
        $query = FireSafetyNotification::forCompliance('typhoon_flood')
            ->latest();

        if ($user->role !== 'admin') {
            $query->where(function ($q) use ($user) {
                $q->whereNull('school_id')
                    ->orWhere('school_id', $user->typhoon_school_id)
                    ->orWhere('school_id', $user->school_id)
                    ->orWhere('user_id', $user->id);
            });
        }

        $notifications = $query->paginate(15);

        $evacuationCentersQuery = $this->typhoonRegisteredEvacuationCentersQuery();
        if ($user->role !== 'admin') {
            $ids = $this->contributorTyphoonSchoolIds($user);
            if (!$ids || count($ids) === 0) {
                $evacuationCentersQuery->whereRaw('1=0');
            } else {
                $evacuationCentersQuery->whereIn('id', $ids);
            }
        }

        $evacuationCenters = $evacuationCentersQuery->get()->map(fn ($ec) => $this->attachCurrentOccupancy($ec));

        return view('typhoon.notifications', compact('notifications', 'evacuationCenters'));
    }

    public function markNotificationRead($id)
    {
        $notification = FireSafetyNotification::findOrFail($id);
        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function markAllNotificationsRead()
    {
        $user = auth()->user();
        $query = FireSafetyNotification::forCompliance('typhoon_flood')->unread();

        if ($user->role !== 'admin') {
            $query->where(function ($q) use ($user) {
                $q->whereNull('school_id')
                    ->orWhere('school_id', $user->school_id)
                    ->orWhere('school_id', $user->typhoon_school_id);
            });
        }

        $query->update(['is_read' => true]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function realtime(Request $request)
    {
        $user = auth()->user();
        $activeSchoolId = session('typhoon_active_school_id');
        if ($user->role !== 'admin') {
            $activeSchoolId = $user->typhoon_school_id ?: $user->school_id;
        }

        if (!$activeSchoolId) {
            return response()->json([
                'last_updated_label' => now()->format('h:i A'),
                'flood_level' => 'Normal',
                'flood_station' => 'San Isidro',
                'typhoon_name' => 'None',
                'typhoon_wind_speed' => '--',
                'routes_status_html' => '<i class="fas fa-check-circle"></i> All routes clear',
                'blocked_roads_label' => '0 blocked roads reported',
            ]);
        }

        $ec = School::find($activeSchoolId);
        if (!$ec) {
            return response()->json([
                'last_updated_label' => now()->format('h:i A'),
            ]);
        }

        $flood = TypFldMonitoringSnapshot::where('school_id', $ec->id)
            ->where('type', 'flood')
            ->latest('recorded_at')
            ->first();
        $typhoon = TypFldMonitoringSnapshot::where('school_id', $ec->id)
            ->where('type', 'typhoon')
            ->latest('recorded_at')
            ->first();
        $routes = TypFldMonitoringSnapshot::where('school_id', $ec->id)
            ->where('type', 'routes')
            ->latest('recorded_at')
            ->first();

        $floodPayload = (array) ($flood->payload ?? []);
        $typhoonPayload = (array) ($typhoon->payload ?? []);
        $routesPayload = (array) ($routes->payload ?? []);

        return response()->json([
            'last_updated_label' => now()->format('h:i A'),
            'flood_level' => $floodPayload['level'] ?? 'Normal',
            'flood_station' => $floodPayload['station'] ?? 'San Isidro',
            'typhoon_name' => $typhoonPayload['name'] ?? 'None',
            'typhoon_wind_speed' => $typhoonPayload['wind_speed'] ?? '--',
            'routes_status_html' => $routesPayload['status_html'] ?? '<i class="fas fa-check-circle"></i> All routes clear',
            'blocked_roads_label' => $routesPayload['blocked_roads_label'] ?? '0 blocked roads reported',
        ]);
    }
}
