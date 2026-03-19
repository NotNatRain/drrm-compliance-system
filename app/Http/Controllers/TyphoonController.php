<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\FireSafetySchool;
use App\Models\TypFldEvacuationCenter;
use App\Models\TypFldFamily;
use App\Models\TypFldFamilyMember;
use App\Models\TypFldMonitoringSnapshot;
use App\Models\ActivityLog;
use App\Models\FireSafetyNotification;

class TyphoonController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the typhoon/flooding dashboard.
     */
    public function dashboard()
    {
        $user = auth()->user();

        // For contributors: resolve their assigned typhoon school
        $contributorActiveSchoolId = null;
        if ($user->role !== 'admin') {
            if ($user->typhoon_school_id) {
                $assignedEc = TypFldEvacuationCenter::find($user->typhoon_school_id);
                $contributorActiveSchoolId = $assignedEc ? $assignedEc->school_id : null;
            }
        }

        // ─── EVACUATION CENTERS for the bottom section ──────────────────────────
        // Admins → ALL centers (no filter); Contributors → only their school's center
        $evacuationCentersQuery = TypFldEvacuationCenter::query()->with('school');

        if ($user->role !== 'admin') {
            if ($contributorActiveSchoolId) {
                $evacuationCentersQuery->where('school_id', $contributorActiveSchoolId);
            } else {
                $evacuationCentersQuery->whereRaw('1=0');
            }
        }
        // admin: no where clause → all centers

        $evacuationCenters = $evacuationCentersQuery->get()->map(function ($ec) {
            $ec->current_occupancy = TypFldFamilyMember::query()
                ->join('typ_fld_families', 'typ_fld_families.id', '=', 'typ_fld_family_members.family_id')
                ->where('typ_fld_families.evacuation_center_id', $ec->id)
                ->whereNull('typ_fld_families.checked_out_at')
                ->count();
            return $ec;
        });

        // ─── GLOBAL stat queries (ALWAYS all schools, all user types) ────────────
        $globalFamiliesQuery = TypFldFamily::query();
        $globalMembersQuery  = TypFldFamilyMember::query();

        $activeFamiliesQuery = (clone $globalFamiliesQuery)->whereNull('checked_out_at');

        $totalFamilies = (clone $activeFamiliesQuery)->count();
        $totalEvacuees = (clone $globalMembersQuery)
            ->join('typ_fld_families as f', 'f.id', '=', 'typ_fld_family_members.family_id')
            ->whereNull('f.checked_out_at')
            ->count();

        $missingCount  = (clone $globalMembersQuery)->where('status', 'missing')->count();
        $injuredCount  = (clone $globalMembersQuery)->where('status', 'injured')->count();
        $deceasedCount = (clone $globalMembersQuery)->where('status', 'deceased')->count();

        $vulnerableCounts = [
            'pregnant' => (clone $activeFamiliesQuery)->where('has_pregnant', true)->count(),
            'pwd'      => (clone $activeFamiliesQuery)->where('has_pwd', true)->count(),
            'senior'   => (clone $activeFamiliesQuery)->where('has_senior', true)->count(),
        ];

        // All centers count (always global for header badge)
        $openEvacuationCentersCount = TypFldEvacuationCenter::where('usage_status', '!=', 'cleared')->count();

        // Latest monitoring snapshots (from contributor's active center, if any)
        $activeCenter    = $contributorActiveSchoolId
            ? TypFldEvacuationCenter::where('school_id', $contributorActiveSchoolId)->first()
            : null;
        $floodMonitoring = null;

        if ($activeCenter) {
            $floodMonitoring = TypFldMonitoringSnapshot::where('evacuation_center_id', $activeCenter->id)
                ->where('type', 'flood')
                ->latest('recorded_at')
                ->first();
        }

        // Fetch Real-time Weather Data from Open-Meteo (Olongapo City)
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
            $code  = $weatherData['current']['weather_code'];
            $codes = [
                0  => 'Clear Sky', 1 => 'Mainly Clear', 2 => 'Partly Cloudy', 3 => 'Overcast',
                45 => 'Fog', 48 => 'Depositing Rime Fog',
                51 => 'Light Drizzle', 53 => 'Moderate Drizzle', 55 => 'Dense Drizzle',
                61 => 'Slight Rain', 63 => 'Moderate Rain', 65 => 'Heavy Rain',
                80 => 'Slight Rain Showers', 81 => 'Moderate Rain Showers', 82 => 'Violent Rain Showers',
                95 => 'Thunderstorm', 96 => 'Thunderstorm with Hail', 99 => 'Heavy Thunderstorm',
            ];
            $weatherDesc = $codes[$code] ?? 'Cloudy';
        }

        $dailyRainfallSum = $weatherData['daily']['precipitation_sum'][0] ?? 0.0;

        // ─── Active Typhoon near Olongapo City / Zambales (via GDACS) ───────────────
        // Only alert if the typhoon's centre is within ~500 km of Olongapo City
        $activeTyphoon = cache()->remember('gdacs_active_typhoon_ph', 1800, function () {
            // Olongapo City, Zambales coordinates
            $targetLat = 14.838;
            $targetLon = 120.282;
            $radiusKm  = 500; // 500 km impact radius around Olongapo

            try {
                $url = 'https://www.gdacs.org/gdacsapi/api/events/geteventlist/SEARCH'
                     . '?eventtypes=TC&fromdate=' . now()->subDays(7)->format('Y-m-d')
                     . '&todate=' . now()->addDays(7)->format('Y-m-d')
                     . '&alertlevel=&pagenumber=1&pagesize=50';

                $ctx = stream_context_create(['http' => ['timeout' => 8]]);
                $raw = @file_get_contents($url, false, $ctx);
                if (!$raw) return null;

                $data     = json_decode($raw, true);
                $features = $data['features'] ?? [];

                // Helper: Haversine distance in km between two lat/lon points
                $haversine = function ($lat1, $lon1, $lat2, $lon2) {
                    $R  = 6371; // Earth radius in km
                    $dLat = deg2rad($lat2 - $lat1);
                    $dLon = deg2rad($lon2 - $lon1);
                    $a    = sin($dLat / 2) ** 2
                            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
                            * sin($dLon / 2) ** 2;
                    return $R * 2 * atan2(sqrt($a), sqrt(1 - $a));
                };

                $closest     = null;
                $closestDist = PHP_INT_MAX;

                foreach ($features as $f) {
                    $coords = $f['geometry']['coordinates'] ?? null;
                    if (!$coords) continue;

                    $lon = (float) $coords[0];
                    $lat = (float) $coords[1];

                    $distKm = $haversine($targetLat, $targetLon, $lat, $lon);

                    // Only consider typhoons within the impact radius
                    if ($distKm <= $radiusKm && $distKm < $closestDist) {
                        $closestDist = $distKm;
                        $props       = $f['properties'] ?? [];
                        $rawName     = $props['name'] ?? ($props['htmldescription'] ?? 'Unnamed');
                        $alertLevel  = strtolower($props['alertlevel'] ?? 'green');
                        $windKph     = isset($props['maxwind']) ? round($props['maxwind'] * 3.6) : 0;

                        // PAGASA TCWS Signal mapping
                        if ($windKph >= 221)     $signal = 5;
                        elseif ($windKph >= 180) $signal = 4;
                        elseif ($windKph >= 120) $signal = 3;
                        elseif ($windKph >= 60)  $signal = 2;
                        else {
                            $signal = $alertLevel === 'red' ? 3 : ($alertLevel === 'orange' ? 2 : 1);
                        }

                        // Typhoon category label (PAGASA style)
                        if ($signal >= 5)      $category = 'Super Typhoon';
                        elseif ($signal >= 3)  $category = 'Typhoon';
                        elseif ($signal >= 2)  $category = 'Tropical Storm';
                        else                   $category = 'Tropical Depression';

                        // Clean GDACS-style internal names (e.g. "TC 2026001N11142")
                        $cleanName = trim(preg_replace('/^TC\s*\d+[A-Z\d]+\s*/i', '', $rawName));
                        if (empty($cleanName) || strlen($cleanName) < 2) {
                            $cleanName = $props['eventid'] ?? 'Unnamed';
                        }

                        $closest = [
                            'name'        => strtoupper($cleanName),
                            'category'    => $category,
                            'signal'      => $signal,
                            'wind_kph'    => $windKph,
                            'distance_km' => round($distKm),
                            'lat'         => $lat,
                            'lon'         => $lon,
                        ];
                    }
                }

                return $closest; // null if nothing within 500 km of Olongapo
            } catch (\Exception $e) {
                return null;
            }
        });

        return view('typhoon.dashboard', [
            'evacuationCenters'          => $evacuationCenters,
            'totalFamilies'              => $totalFamilies,
            'totalEvacuees'              => $totalEvacuees,
            'openEvacuationCentersCount' => $openEvacuationCentersCount,
            'incidentMonitoring'         => ['major' => 0, 'minor' => 0],
            'rainfall' => [
                'bangal'   => number_format($dailyRainfallSum * 0.95, 2),
                'kalaklan' => number_format($dailyRainfallSum * 1.05, 2),
            ],
            'missingCount'       => $missingCount,
            'injuredCount'       => $injuredCount,
            'deceasedCount'      => $deceasedCount,
            'vulnerableCounts'   => $vulnerableCounts,
            'recentEvacuees'     => $totalEvacuees > 0,
            'recentlyRegistered' => (clone $activeFamiliesQuery)->whereDate('created_at', Carbon::today())->count(),
            'floodMonitoring'    => $floodMonitoring ? (object) ($floodMonitoring->payload ?? []) : null,
            'typhoonData'        => (object) [
                'name' => $weatherDesc,
                'temp' => $weatherData['current']['temperature_2m'] ?? '--',
                'wind' => $weatherData['current']['wind_speed_10m'] ?? '--',
            ],
            'activeSchoolId' => $contributorActiveSchoolId,
            'activeTyphoon'  => $activeTyphoon,
            'quickAnnouncements' => FireSafetyNotification::forCompliance('typhoon_flood')
                ->where(function($q) use ($contributorActiveSchoolId) {
                    $q->whereNull('school_id')->orWhere('school_id', $contributorActiveSchoolId);
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

        $schoolsQuery = FireSafetySchool::query();
        if ($user->role !== 'admin' && $user->school_id) {
            $schoolsQuery->where('id', $user->school_id);
        }

        $schools = $schoolsQuery->get()->map(function ($s) {
            $ec = TypFldEvacuationCenter::firstOrCreate(
                ['school_id' => $s->id],
                [
                    'identification' => $s->school_id ?? null,
                    'location' => $s->address ?? null,
                    'capacity' => 0,
                    'occupancy_safety' => 'safe',
                    'operational_status' => 'operational',
                    'monitoring_status' => 'Active',
                ]
            );

            $occupancy = TypFldFamilyMember::query()
                ->join('typ_fld_families', 'typ_fld_families.id', '=', 'typ_fld_family_members.family_id')
                ->where('typ_fld_families.evacuation_center_id', $ec->id)
                ->whereNull('typ_fld_families.checked_out_at')
                ->count();

            $s->typ_ec = $ec;
            $s->typ_ec_current_occupancy = $occupancy;
            return $s;
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
        $school = FireSafetySchool::findOrFail($id);

        if (auth()->user()->role !== 'admin' && auth()->user()->school_id != $id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        session(['typhoon_active_school_id' => $school->id]);
        return response()->json(['success' => true]);
    }

    public function storeFamily(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'evacuation_center_id' => 'required|integer|exists:typ_fld_evacuation_centers,id',
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

        $ec = TypFldEvacuationCenter::with('school')->findOrFail($request->evacuation_center_id);

        // Capacity check logic:
        // "Capacity is 500 and Occupancy are currently in 498 if there were a new family ... forced it to register ... but if another new family ... do not allow it anymore"
        // This means if CURRENT occupancy < CAPACITY, allow registration (even if result > capacity).
        // If CURRENT occupancy >= CAPACITY, block.
        
        $currentOccupancyCount = TypFldFamilyMember::query()
            ->join('typ_fld_families', 'typ_fld_families.id', '=', 'typ_fld_family_members.family_id')
            ->where('typ_fld_families.evacuation_center_id', $ec->id)
            ->whereNull('typ_fld_families.checked_out_at')
            ->count();

        // Check if full
        if ($ec->capacity > 0 && $currentOccupancyCount >= $ec->capacity) {
            return redirect()->back()->with('error', "Evacuation Center is full (Capacity: {$ec->capacity}, Current: {$currentOccupancyCount}). Cannot register more families.")->withInput();
        }

        // permission: contributor can only create under their school
        $user = auth()->user();
        if ($user->role !== 'admin' && $user->school_id != $ec->school_id && $user->typhoon_school_id != $ec->id) {
            return redirect()->back()->with('error', 'Unauthorized evacuation center.');
        }

        DB::transaction(function () use ($request, $ec) {
            $family = TypFldFamily::create([
                'evacuation_center_id' => $ec->id,
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

            $ec->save();

            ActivityLog::log('typhoon_flood', 'Registered family: ' . $family->head_family_name, [
                'school_id' => $ec->school_id,
                'notes' => $family->collective_needs,
            ]);
        });

        return redirect()->route('typhoon.dashboard')->with('success', 'Family registered successfully.');
    }

    public function showEvacuationCenter($id)
    {
        $ec = TypFldEvacuationCenter::with('school')->findOrFail($id);
        $user = auth()->user();
        if ($user->role !== 'admin' && $user->school_id != $ec->school_id && $user->typhoon_school_id != $ec->id) {
            abort(403, 'Unauthorized');
        }

        session(['typhoon_active_school_id' => $ec->school_id]);

        $families = TypFldFamily::withCount('members')
            ->where('evacuation_center_id', $ec->id)
            ->latest()
            ->get();

        $lastUsedAt = $families->max('created_at');

        $currentOccupancy = TypFldFamilyMember::query()
            ->join('typ_fld_families', 'typ_fld_families.id', '=', 'typ_fld_family_members.family_id')
            ->where('typ_fld_families.evacuation_center_id', $ec->id)
            ->whereNull('typ_fld_families.checked_out_at')
            ->count();

        $evacuationCenters = TypFldEvacuationCenter::with('school')->get()->map(function ($center) {
            $center->current_occupancy = TypFldFamilyMember::query()
                ->join('typ_fld_families', 'typ_fld_families.id', '=', 'typ_fld_family_members.family_id')
                ->where('typ_fld_families.evacuation_center_id', $center->id)
                ->whereNull('typ_fld_families.checked_out_at')
                ->count();
            return $center;
        });

        $quickAnnouncements = FireSafetyNotification::forCompliance('typhoon_flood')
            ->where(function($q) use ($ec) {
                $q->whereNull('school_id')->orWhere('school_id', $ec->school_id);
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
            'existing_school_id' => 'nullable|integer|exists:firesafety_school_information,id',
            'identification' => 'nullable|string|max:255',
            'school_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:2000',
            'usage_status' => 'required|string|in:cleared,occupied,full,decamp',
            'emergency_resources' => 'nullable|string|max:2000',
            'capacity' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $schoolId = $request->existing_school_id;

        if (!$schoolId) {
            // Create minimal FireSafetySchool entry for new school
            $school = FireSafetySchool::create([
                'school_name' => $request->school_name ?: ($request->identification ?: 'New Evacuation School'),
                'school_id' => $request->identification ?: ('EVAC-' . strtoupper(uniqid())),
                'address' => $request->location,
                'school_head' => null,
                'school_drrm_coordinator' => null,
                'status' => 'unconfigured',
                'evacuation_map_layout' => null,
                'alerts' => null,
                'events' => null,
            ]);
            $schoolId = $school->id;
        }

        TypFldEvacuationCenter::firstOrCreate(
            ['school_id' => $schoolId],
            [
                'identification' => $request->identification,
                'location' => $request->location,
                'capacity' => $request->capacity ?? 0,
                'operational_status' => 'operational',
                'needs_summary' => null,
                'occupancy_safety' => 'safe',
                'usage_status' => $request->usage_status,
                'emergency_resources' => $request->emergency_resources,
                'monitoring_status' => 'Active',
                'reports_status' => null,
            ]
        );

        session(['typhoon_active_school_id' => $schoolId]);

        ActivityLog::log('typhoon_flood', 'Created evacuation center: ' . ($request->school_name ?: $request->identification), [
            'school_id' => $schoolId,
        ]);

        return redirect()->route('typhoon.dashboard')->with('success', 'Evacuation center created.');
    }

    public function updateEvacuationCenter(Request $request, $id)
    {
        $ec = TypFldEvacuationCenter::with('school')->findOrFail($id);
        $user = auth()->user();
        if ($user->role !== 'admin' && $user->school_id != $ec->school_id && $user->typhoon_school_id != $ec->id) {
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

        $ec->usage_status = $request->usage_status;
        $ec->emergency_resources = $request->emergency_resources;
        $ec->reports_status = $request->reports_status;
        $ec->save();

        // Create notification for site update
        FireSafetyNotification::create([
            'compliance_type' => 'typhoon_flood',
            'module' => 'evacuation_center',
            'school_id' => $ec->school_id,
            'user_id' => $user->id,
            'type' => 'update',
            'title' => 'Site Intelligence Updated',
            'message' => "Contributor {$user->name} updated site {$ec->school->school_name}. Status: " . strtoupper($ec->usage_status),
            'action_type' => 'view_site',
            'action_url' => route('typhoon.evacuation-center.show', $ec->id),
            'is_read' => false,
        ]);

        ActivityLog::log('typhoon_flood', 'Updated evacuation center: ' . $ec->identification, [
            'school_id' => $ec->school_id,
            'notes' => "Status: {$ec->usage_status}",
        ]);

        return redirect()->route('typhoon.evacuation-center.show', $ec->id)->with('success', 'Evacuation center updated.');
    }

    public function storeAnnouncement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'school_id' => 'nullable|integer|exists:firesafety_school_information,id',
            'urgency' => 'required|in:info,warning,danger',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $user = auth()->user();

        FireSafetyNotification::create([
            'compliance_type' => 'typhoon_flood',
            'module' => 'announcement',
            'school_id' => $request->school_id, // Null means all schools
            'user_id' => $user->id,
            'type' => 'announcement',
            'title' => $request->title,
            'message' => $request->message,
            'action_type' => 'mark_read',
            'is_read' => false,
            'action_data' => ['urgency' => $request->urgency]
        ]);

        return redirect()->back()->with('success', 'Announcement posted successfully.');
    }

    public function notifications()
    {
        $user = auth()->user();
        $query = FireSafetyNotification::forCompliance('typhoon_flood')
            ->latest();

        if ($user->role !== 'admin') {
            // Conributors see:
            // 1. Announcements (global: school_id is null)
            // 2. Announcements for their assigned school
            // 3. Their own updates
            $query->where(function($q) use ($user) {
                $q->whereNull('school_id')
                  ->orWhere('school_id', $user->school_id)
                  ->orWhere('user_id', $user->id);
            });
        }
        // Admins see everything (no filter)

        $notifications = $query->paginate(15);

        return view('typhoon.notifications', compact('notifications'));
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
            $query->where(function($q) use ($user) {
                $q->whereNull('school_id')
                  ->orWhere('school_id', $user->school_id);
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
            $activeSchoolId = $user->school_id;
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

        $ec = TypFldEvacuationCenter::where('school_id', $activeSchoolId)->first();
        if (!$ec) {
            return response()->json([
                'last_updated_label' => now()->format('h:i A'),
            ]);
        }

        $flood = TypFldMonitoringSnapshot::where('evacuation_center_id', $ec->id)
            ->where('type', 'flood')
            ->latest('recorded_at')
            ->first();
        $typhoon = TypFldMonitoringSnapshot::where('evacuation_center_id', $ec->id)
            ->where('type', 'typhoon')
            ->latest('recorded_at')
            ->first();
        $routes = TypFldMonitoringSnapshot::where('evacuation_center_id', $ec->id)
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
