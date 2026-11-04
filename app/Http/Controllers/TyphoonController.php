<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\FireSafetyNotification;
use App\Models\School;
use App\Models\TypFldFamily;
use App\Models\TypFldFamilyMember;
use App\Models\TypFldNeed;
use App\Models\TypFldMonitoringSnapshot;
use App\Models\SchoolSpecificsInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
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

    private function canManageSchool($user, School $school): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return (int) $user->school_id === (int) $school->id
            || (int) $user->typhoon_school_id === (int) $school->id;
    }

    private function currentOccupancyCount(int $schoolId): int
    {
        return TypFldFamilyMember::query()
            ->join('typ_fld_families', 'typ_fld_families.id', '=', 'typ_fld_family_members.family_id')
            ->where('typ_fld_families.school_id', $schoolId)
            ->whereNull('typ_fld_families.checked_out_at')
            ->count();
    }

    private function syncEvacuationStatus(School $school): void
    {
        $currentOccupancyCount = $this->currentOccupancyCount($school->id);
        $capacity = (int) $school->evacuation_capacity;

        if ($capacity > 0 && $currentOccupancyCount >= $capacity) {
            $school->evacuation_status = 'full';
        } elseif ($currentOccupancyCount > 0) {
            $school->evacuation_status = 'occupied';
        }

        $school->save();
    }

    private function familyNeedOptions()
    {
        $defaultNeedOptions = [
            'Rice',
            'Water',
            'Ready-to-Eat Food',
            'Medicine',
            'Blanket',
            'Clothing',
            'Hygiene Kit',
            'Baby Supplies',
            'Diapers',
            'Milk',
            'Sleeping Mat',
            'Others Please Specify',
        ];

        $databaseNeedOptions = DB::table('typ_fld_needs')
            ->where('need_name', '!=', 'Others Please Specify')
            ->orderBy('need_name')
            ->pluck('need_name')
            ->all();

        return collect($defaultNeedOptions)
            ->merge($databaseNeedOptions)
            ->unique()
            ->values();
    }

    private function normalizeFamilyNeeds(array $needsRows): array
    {
        return collect($needsRows)
            ->map(function ($row) {
                $needName = trim((string) ($row['need_name'] ?? ''));
                $customNeed = trim((string) ($row['custom_need'] ?? ''));
                $quantity = max(1, (int) ($row['quantity'] ?? 1));

                if ($needName === 'Others Please Specify') {
                    $needName = $customNeed;
                }

                return [
                    'need_name' => $needName,
                    'quantity' => $quantity,
                    'is_custom' => $customNeed !== '',
                ];
            })
            ->filter(fn ($row) => $row['need_name'] !== '')
            ->values()
            ->all();
    }

    private function syncFamilyNeeds(TypFldFamily $family, array $needsRows): void
    {
        $family->needs()->delete();

        foreach ($this->normalizeFamilyNeeds($needsRows) as $row) {
            $family->needs()->create($row);
        }
    }

    private function deriveAgeFlags(array $members): array
    {
        $hasSenior = false;
        $hasChildUnder5 = false;

        foreach ($members as $member) {
            $age = (int) ($member['age'] ?? -1);
            if ($age >= 60) {
                $hasSenior = true;
            }
            if ($age >= 0 && $age <= 5) {
                $hasChildUnder5 = true;
            }
        }

        return [
            'has_senior' => $hasSenior,
            'has_child_under5' => $hasChildUnder5,
        ];
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

    private function typhoonNotificationsQueryForUser($user, bool $unreadOnly = false)
    {
        $query = FireSafetyNotification::forCompliance('typhoon_flood');

        if ($unreadOnly) {
            $query->unread();
        }

        if ($user->role === 'admin') {
            return $query;
        }

        return $query->where(function ($q) use ($user) {
            $q->where(function ($personalQuery) use ($user) {
                $personalQuery->where('module', 'incident_bridge')
                    ->where('user_id', $user->id);
            })->orWhere(function ($sharedQuery) use ($user) {
                $sharedQuery->where('module', '!=', 'incident_bridge')
                    ->where(function ($visibilityQuery) use ($user) {
                        $visibilityQuery->whereNull('school_id')
                            ->orWhere('school_id', $user->typhoon_school_id)
                            ->orWhere('school_id', $user->school_id)
                            ->orWhere('user_id', $user->id);
                    });
            });
        });
    }

    public function dashboard()
    {
        $user = Auth::user();

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
        $familyNeedOptions = $this->familyNeedOptions();
        $existingFamiliesByCenter = TypFldFamily::query()
            ->with(['members', 'needs'])
            ->whereIn('school_id', $evacuationCenters->pluck('id')->all())
            ->latest()
            ->get()
            ->groupBy('school_id')
            ->map(function ($families) {
                return $families->map(function ($family) {
                    return [
                        'id' => $family->id,
                        'head_family_name' => $family->head_family_name,
                        'checked_out_at' => optional($family->checked_out_at)->toDateTimeString(),
                        'created_at' => optional($family->created_at)->toDateTimeString(),
                        'has_pregnant' => (bool) $family->has_pregnant,
                        'has_pwd' => (bool) $family->has_pwd,
                        'has_senior' => (bool) $family->has_senior,
                        'has_lactating' => (bool) $family->has_lactating,
                        'has_child_under5' => (bool) $family->has_child_under5,
                        'members' => $family->members->map(function ($member) {
                            return [
                                'full_name' => $member->full_name,
                                'age' => (int) $member->age,
                                'gender' => $member->gender,
                                'is_head' => (bool) $member->is_head,
                            ];
                        })->values(),
                        'needs' => $family->needs->map(function ($need) {
                            return [
                                'need_name' => $need->need_name,
                                'quantity' => (int) $need->quantity,
                                'is_custom' => (bool) $need->is_custom,
                            ];
                        })->values(),
                    ];
                })->values();
            });

        $ecIds = $evacuationCenters->pluck('id')->all();

        $firesafetyBuildings = \App\Models\FireSafetyBuilding::query()
            ->whereIn('unified_school_id', $ecIds)
            ->get();

        $buildingIds = $firesafetyBuildings->pluck('id')->all();

        $fireSafetyRooms = \App\Models\FireSafetyRoom::query()
            ->whereIn('building_id', $buildingIds)
            ->get();

        return view('typhoon.dashboard', [
            'evacuationCenters' => $evacuationCenters,
            'firesafetyBuildings' => $firesafetyBuildings,
            'fireSafetyRooms' => $fireSafetyRooms,
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
            'familyNeedOptions' => $familyNeedOptions,
            'existingFamiliesByCenter' => $existingFamiliesByCenter,
            'quickAnnouncements' => FireSafetyNotification::forCompliance('typhoon_flood')
                ->where(function ($q) use ($contributorActiveSchoolId) {
                    $q->whereNull('school_id')
                        ->orWhere('school_id', $contributorActiveSchoolId);
                })
                ->where('type', 'announcement')
                ->latest()
                ->take(5)
                ->get(),
            'unreadNotificationsCount' => $this->typhoonNotificationsQueryForUser($user, true)->count(),
        ]);
    }

    public function chooseSchool()
    {
        $user = Auth::user();

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

        if (Auth::user()->role !== 'admin' && (int) Auth::user()->school_id !== (int) $school->id && (int) Auth::user()->typhoon_school_id !== (int) $school->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        session(['typhoon_active_school_id' => $school->id]);

        return response()->json(['success' => true]);
    }

    public function storeFamily(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'evacuation_center_id' => 'required|integer|exists:schools,id',
            'building_id'           => 'nullable|integer',
            'room_id'               => 'nullable|integer',
            'registration_mode'    => 'required|string|in:new,existing',
            'existing_family_id'   => 'nullable|integer|exists:typ_fld_families,id|required_if:registration_mode,existing',
            'head_family_name'     => 'required|string|max:255',
            'serial_number'        => 'nullable|string|max:50',
            'street'               => 'nullable|string|max:255',
            'barangay'             => 'nullable|string|max:255',
            'city'                 => 'nullable|string|max:255',
            'contact_number'       => 'nullable|string|max:20',
            'needs'                => 'required|array|min:1',
            'needs.*.need_name'    => 'required|string|max:255',
            'needs.*.custom_need'  => 'nullable|string|max:255',
            'needs.*.quantity'     => 'required|integer|min:1|max:999',
            'has_pregnant'         => 'nullable|boolean',
            'has_pwd'              => 'nullable|boolean',
            'has_senior'           => 'nullable|boolean',
            'has_lactating'        => 'nullable|boolean',
            'has_child_under5'     => 'nullable|boolean',
            'has_other_needs'      => 'nullable|boolean',
            'other_needs_details'  => 'nullable|string|max:500',
            'confirm_check_in'     => 'nullable|in:on,1,true',
            'members'              => 'required|array|min:1',
            'members.*.full_name'  => 'required|string|max:255',
            'members.*.age'        => 'required|integer|min:0|max:120',
            'members.*.gender'     => 'required|in:male,female',
            'members.*.family_role'=> 'nullable|string|max:100',
            'belongings'           => 'nullable|array',
            'belongings.*.name'    => 'nullable|string|max:255',
            'belongings.*.qty'     => 'nullable|integer|min:1|max:999',
            'pets'                 => 'nullable|array',
            'pets.*.name'          => 'nullable|string|max:255',
            'pets.*.qty'           => 'nullable|integer|min:1|max:999',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $ec = School::findOrFail($request->evacuation_center_id);

        $currentOccupancyCount = $this->currentOccupancyCount($ec->id);

        $capacity = $ec->evacuation_capacity;
        if ($capacity > 0 && $currentOccupancyCount >= $capacity) {
            return redirect()->back()->with('error', "Evacuation Center is full (Capacity: {$capacity}, Current: {$currentOccupancyCount}). Cannot register more families.")->withInput();
        }

        $user = Auth::user();
        if (!$this->canManageSchool($user, $ec)) {
            return redirect()->back()->with('error', 'Unauthorized evacuation center.');
        }

        $existingFamilyTemplate = null;
        if ($request->registration_mode === 'existing') {
            $existingFamilyTemplate = TypFldFamily::with(['members', 'needs'])
                ->where('id', (int) $request->existing_family_id)
                ->where('school_id', $ec->id)
                ->first();

            if (!$existingFamilyTemplate) {
                return redirect()->back()->with('error', 'Selected existing family is not from this evacuation center.')->withInput();
            }
        }

        $serialNumber = trim((string) $request->input('serial_number', ''));
        if ($serialNumber === '') {
            $serialNumber = trim((string) ($existingFamilyTemplate?->serial_number ?? ''));
        }
        if ($serialNumber === '') {
            $barangayWords = preg_split('/\\s+/', trim((string) $request->input('barangay', '')));
            $barangayCode = strtoupper(substr(implode('', array_map(
                fn ($word) => preg_replace('/[^A-Za-z0-9]/', '', $word)[0] ?? '',
                array_filter($barangayWords ?: [])
            )), 0, 5));
            $barangayCode = $barangayCode !== '' ? $barangayCode : 'BRGY';
            $serialNumber = $barangayCode . '-' . now()->format('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
        }

        // Parse belongings: filter out empty rows
        $belongingsInput = collect($request->input('belongings', []))
            ->filter(fn($b) => !empty($b['name']))
            ->map(fn($b) => ['name' => trim($b['name']), 'qty' => (int) ($b['qty'] ?? 1)])
            ->values()
            ->toArray();

        // Parse pets: filter out empty rows
        $petsInput = collect($request->input('pets', []))
            ->filter(fn($p) => !empty($p['name']))
            ->map(fn($p) => ['name' => trim($p['name']), 'qty' => (int) ($p['qty'] ?? 1)])
            ->values()
            ->toArray();

        DB::transaction(function () use ($request, $ec, $existingFamilyTemplate, $user, $belongingsInput, $petsInput, $serialNumber) {
            $existingFamily = $existingFamilyTemplate;

            $members = (array) $request->input('members', []);
            if (empty($members) && $existingFamily) {
                $members = $existingFamily->members
                    ->map(function ($member) {
                        return [
                            'full_name' => $member->full_name,
                            'age' => (int) $member->age,
                            'gender' => $member->gender,
                            'is_head' => (bool) $member->is_head,
                        ];
                    })
                    ->values()
                    ->all();
            }

            $ageFlags = $this->deriveAgeFlags($members);

            $family = TypFldFamily::create([
                'school_id'           => $ec->id,
                'building_id'         => $request->building_id ?: ($existingFamily?->building_id ?? null),
                'room_id'             => $request->room_id ?: ($existingFamily?->room_id ?? null),
                'head_family_name'    => $request->head_family_name ?: ($existingFamily?->head_family_name ?? ''),
                'serial_number'       => $serialNumber,
                'street'              => $request->street ?: ($existingFamily?->street ?? null),
                'barangay'            => $request->barangay ?: ($existingFamily?->barangay ?? null),
                'city'                => $request->city ?: ($existingFamily?->city ?? null),
                'contact_number'      => $request->contact_number ?: ($existingFamily?->contact_number ?? null),
                'has_pregnant'        => (bool) $request->has_pregnant || (bool) ($existingFamily?->has_pregnant ?? false),
                'has_pwd'             => (bool) $request->has_pwd || (bool) ($existingFamily?->has_pwd ?? false),
                'has_senior'          => (bool) $request->has_senior || $ageFlags['has_senior'] || (bool) ($existingFamily?->has_senior ?? false),
                'has_lactating'       => (bool) $request->has_lactating || (bool) ($existingFamily?->has_lactating ?? false),
                'has_child_under5'    => (bool) $request->has_child_under5 || $ageFlags['has_child_under5'] || (bool) ($existingFamily?->has_child_under5 ?? false),
                'has_other_needs'     => (bool) $request->has_other_needs,
                'other_needs_details' => $request->has_other_needs ? $request->other_needs_details : null,
                'personal_belongings' => count($belongingsInput) > 0 ? $belongingsInput : null,
                'personal_pets'       => count($petsInput) > 0 ? $petsInput : null,
                'registered_by'       => $user->id,
                'checked_in_at'       => $request->confirm_check_in ? now() : null,
            ]);

            // Persist the identifier directly on typ_fld_families even if it is not mass assignable.
            DB::table('typ_fld_families')
                ->where('id', $family->id)
                ->update(['serial_number' => $serialNumber]);

            foreach ($members as $idx => $m) {
                TypFldFamilyMember::create([
                    'family_id' => $family->id,
                    'full_name' => $m['full_name'],
                    'age' => (int) $m['age'],
                    'gender' => $m['gender'],
                    'is_head' => $idx === 0,
                    'status' => 'normal',
                ]);
            }

            $needsRows = (array) $request->input('needs', []);
            if (empty($needsRows) && $existingFamily) {
                $needsRows = $existingFamily->needs
                    ->map(function ($need) {
                        return [
                            'need_name' => $need->is_custom ? 'Others Please Specify' : $need->need_name,
                            'custom_need' => $need->is_custom ? $need->need_name : null,
                            'quantity' => (int) $need->quantity,
                        ];
                    })
                    ->values()
                    ->all();
            }

            $this->syncFamilyNeeds($family, $needsRows);

            // First check-in should activate the center state automatically.
            $ec->evacuation_status = 'occupied';
            $ec->save();

            $this->syncEvacuationStatus($ec);
        });

        ActivityLog::log('typhoon_flood', 'Registered family: ' . $request->head_family_name, [
            'school_id' => $ec->id,
            'notes' => collect($this->normalizeFamilyNeeds((array) $request->input('needs', [])))
                ->pluck('need_name')
                ->implode(', '),
        ]);

        return redirect()->route('typhoon.dashboard')->with('success', 'Family registered successfully.');
    }

    public function showEvacuationCenter($id)
    {
        $ec = School::findOrFail($id);
        $user = Auth::user();
        if ($user->role !== 'admin' && (int) $user->school_id !== (int) $ec->id && (int) $user->typhoon_school_id !== (int) $ec->id) {
            abort(403, 'Unauthorized');
        }

        session(['typhoon_active_school_id' => $ec->id]);

        $families = TypFldFamily::with(['needs'])->withCount('members')
            ->where('school_id', $ec->id)
            ->latest()
            ->get();

        $lastUsedAt = $families->max('created_at');

        $currentOccupancy = TypFldFamilyMember::query()
            ->join('typ_fld_families', 'typ_fld_families.id', '=', 'typ_fld_family_members.family_id')
            ->where('typ_fld_families.school_id', $ec->id)
            ->whereNull('typ_fld_families.checked_out_at')
            ->count();

        $familyNeedOptions = $this->familyNeedOptions();

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
            'familyNeedOptions' => $familyNeedOptions,
        ]);
    }

    public function storeEvacuationCenter(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'existing_school_id' => 'required|integer|exists:schools,id',
            'usage_status' => 'required|string|in:cleared,occupied',
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
        $user = Auth::user();
        if (!$this->canManageSchool($user, $ec)) {
            abort(403, 'Unauthorized');
        }

        $validator = Validator::make($request->all(), [
            'usage_status' => 'required|string|in:cleared,occupied',
            'capacity' => 'required|integer|min:0',
            'emergency_resources' => 'nullable|string|max:2000',
            'reports_status' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $hasDecampState = $ec->evacuation_status === 'decamp'
            || (TypFldFamily::where('school_id', $ec->id)->count() > 0
                && TypFldFamily::where('school_id', $ec->id)->whereNull('checked_out_at')->count() === 0);

        if ($request->usage_status === 'cleared' && !$hasDecampState) {
            return redirect()->back()->with('error', 'Site can only be set to CLEARED/STANDBY after decamp is recorded.')->withInput();
        }

        $ec->evacuation_status = $request->usage_status;
        $ec->evacuation_capacity = (int) $request->capacity;
        $ec->emergency_resources = $request->emergency_resources;
        $ec->reports_status = $request->reports_status;
        $ec->save();

        $this->syncEvacuationStatus($ec);

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

    public function updateFamily(Request $request, $id)
    {
        $family = TypFldFamily::with('school')->findOrFail($id);
        $user = Auth::user();

        if (!$this->canManageSchool($user, $family->school)) {
            abort(403, 'Unauthorized');
        }

        if ($family->checked_out_at) {
            return redirect()->back()->with('error', 'Cannot update a decamped family.');
        }

        $validator = Validator::make($request->all(), [
            'head_family_name' => 'required|string|max:255',
            'building_id' => 'nullable|integer',
            'room_id' => 'nullable|integer',
            'street' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'needs' => 'required|array|min:1',
            'needs.*.need_name' => 'required|string|max:255',
            'needs.*.custom_need' => 'nullable|string|max:255',
            'needs.*.quantity' => 'required|integer|min:1|max:999',
            'has_pregnant' => 'nullable|boolean',
            'has_pwd' => 'nullable|boolean',
            'has_senior' => 'nullable|boolean',
            'has_lactating' => 'nullable|boolean',
            'has_child_under5' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $family->update([
            'building_id' => $request->building_id ?: $family->building_id,
            'room_id' => $request->room_id ?: $family->room_id,
            'head_family_name' => $request->head_family_name,
            'street' => $request->input('street', $family->street),
            'barangay' => $request->input('barangay', $family->barangay),
            'city' => $request->input('city', $family->city),
            'contact_number' => $request->input('contact_number', $family->contact_number),
            'has_pregnant' => (bool) $request->has_pregnant,
            'has_pwd' => (bool) $request->has_pwd,
            'has_senior' => (bool) $request->has_senior,
            'has_lactating' => (bool) $request->has_lactating,
            'has_child_under5' => (bool) $request->has_child_under5,
        ]);

        $this->syncFamilyNeeds($family, (array) $request->input('needs', []));

        ActivityLog::log('typhoon_flood', 'Updated family: ' . $family->head_family_name, [
            'school_id' => $family->school_id,
        ]);

        return redirect()->route('typhoon.evacuation-center.show', $family->school_id)->with('success', 'Family details updated.');
    }

    public function decampFamily($id)
    {
        $family = TypFldFamily::with('school')->findOrFail($id);
        $user = Auth::user();

        if (!$this->canManageSchool($user, $family->school)) {
            abort(403, 'Unauthorized');
        }

        if ($family->checked_out_at) {
            return redirect()->back()->with('info', 'Family already marked as decamped.');
        }

        $family->checked_out_at = now();
        $family->save();

        $activeFamiliesCount = TypFldFamily::where('school_id', $family->school_id)
            ->whereNull('checked_out_at')
            ->count();

        if ($activeFamiliesCount === 0) {
            $family->school->evacuation_status = 'decamp';
            $family->school->save();
        } else {
            $this->syncEvacuationStatus($family->school);
        }

        ActivityLog::log('typhoon_flood', 'Decamped family: ' . $family->head_family_name, [
            'school_id' => $family->school_id,
        ]);

        return redirect()->route('typhoon.evacuation-center.show', $family->school_id)->with('success', 'Family marked as decamped.');
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

        $user = Auth::user();

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
        $user = Auth::user();
        $query = $this->typhoonNotificationsQueryForUser($user)
            ->latest();

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

        $unreadCount = $this->typhoonNotificationsQueryForUser($user, true)->count();

        return view('typhoon.notifications', compact('notifications', 'evacuationCenters', 'unreadCount'));
    }

    public function markNotificationRead($id)
    {
        $notification = FireSafetyNotification::findOrFail($id);
        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function markAllNotificationsRead()
    {
        $user = Auth::user();
        $query = $this->typhoonNotificationsQueryForUser($user, true);

        $query->update(['is_read' => true]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function realtime(Request $request)
    {
        $user = Auth::user();
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

 


    public function familyAssistance()
    {
        $user = Auth::user();
        $familiesQuery = DB::table('typ_fld_families')
            ->select('id', 'school_id', 'head_family_name', 'serial_number', 'barangay', 'created_at')
            ->orderByDesc('created_at');

        if ($user->role !== 'admin') {
            $schoolIds = $this->contributorTyphoonSchoolIds($user) ?? [];
            $familiesQuery->whereIn('school_id', $schoolIds);
        }

        $families = $familiesQuery->get();

        return view('typhoon.family-assistance', ['families' => $families]);
    }

    public function dswdForm(Request $request)
    {
        $user = Auth::user();

        $dswdRecords = DB::table('typh_dswd_form')
            ->latest('id')
            ->get();

        $familiesQuery = TypFldFamily::query()
            ->with(['members', 'school', 'building', 'room'])
            ->latest('id');

        if ($user->role !== 'admin') {
            $schoolIds = $this->contributorTyphoonSchoolIds($user) ?? [];
            $familiesQuery->whereIn('school_id', $schoolIds);
        }

        $families = $familiesQuery->get();

        // Ensure every family has a serial number
        foreach ($families as $f) {
            if (empty(trim((string)$f->serial_number))) {
                $brgy = trim((string)$f->barangay);
                $code = !empty($brgy) ? strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $brgy), 0, 4)) : 'FAM';
                $date = $f->created_at ? $f->created_at->format('Ymd') : now()->format('Ymd');
                $f->serial_number = sprintf("%s-%s-%04d", $code, $date, $f->id);
                DB::table('typ_fld_families')->where('id', $f->id)->update(['serial_number' => $f->serial_number]);
            }
        }

        $familiesData = $families->map(function ($f) {
            return $this->formatFamilyForDSWD($f);
        })->keyBy('serial_number');

        return view('typhoon.dswd-form', compact('dswdRecords', 'families', 'familiesData'));
    }

    public function getFamilyBySerial(Request $request, $serial)
    {
        $user = Auth::user();
        $family = TypFldFamily::with(['members', 'school', 'building', 'room'])
            ->where('serial_number', $serial)
            ->first();

        if (!$family) {
            return response()->json(['error' => 'Family not found'], 404);
        }

        if ($user->role !== 'admin') {
            $schoolIds = $this->contributorTyphoonSchoolIds($user) ?? [];
            if (!in_array((int)$family->school_id, $schoolIds, true)) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        }

        return response()->json($this->formatFamilyForDSWD($family));
    }

    private function formatFamilyForDSWD(TypFldFamily $family): array
    {
        $headMember = $family->members->first(fn($m) => (bool) $m->is_head)
            ?? $family->members->first(fn($m) => strtolower($m->family_role ?? '') === 'head')
            ?? $family->members->first();

        $rawHeadName = trim((string) ($headMember?->full_name ?: $family->head_family_name));
        $parsedName = $this->parsePersonName($rawHeadName);

        $school = $family->school;
        $evacuationCenterName = $school?->school_name ?? '';
        $buildingName = $family->building?->building_name ?? $family->building?->name ?? '';
        $roomName = $family->room?->room_name ?? $family->room?->room_code ?? '';
        if ($buildingName || $roomName) {
            $parts = array_filter([$buildingName, $roomName]);
            $evacuationCenterName .= ' (' . implode(', ', $parts) . ')';
        }

        $region = $school?->region ?: 'Region IV-A';
        $province = $school?->division ?: ($school?->province ?: '');
        $city = $family->city_municipality ?: ($family->city ?: ($school?->district ?: ''));
        $district = $school?->district ?: '';
        $barangay = $family->barangay ?: '';

        $age = $headMember?->age ?? null;
        $gender = $headMember?->gender ? ucfirst(strtolower($headMember->gender)) : '';
        $contact = $family->contact_number ?: ($family->contactnum ? (string)$family->contactnum : '');
        $street = $family->street ?: '';

        $membersList = $family->members->map(function ($m) use ($family) {
            $vulnerabilities = [];
            if ($m->age >= 60) {
                $vulnerabilities[] = 'Senior Citizen';
            }
            if ($m->age !== null && $m->age <= 5) {
                $vulnerabilities[] = 'Child (Under 5)';
            }
            if ($family->has_pregnant && strtolower($m->gender ?? '') === 'female' && (bool)$m->is_head) {
                $vulnerabilities[] = 'Pregnant';
            }
            if ($family->has_lactating && strtolower($m->gender ?? '') === 'female' && (bool)$m->is_head) {
                $vulnerabilities[] = 'Lactating';
            }
            if ($family->has_pwd && (bool)$m->is_head) {
                $vulnerabilities[] = 'PWD';
            }
            if ($family->has_other_needs && $family->other_needs_details) {
                $vulnerabilities[] = $family->other_needs_details;
            }

            return [
                'name'          => $m->full_name,
                'relation'      => $m->family_role ?: ($m->is_head ? 'Head' : 'Member'),
                'birthdate'     => '',
                'age'           => $m->age ?? '',
                'sex'           => $m->gender ? ucfirst(strtolower($m->gender)) : '',
                'education'     => '',
                'occupation'    => '',
                'vulnerability' => implode(', ', array_unique($vulnerabilities)),
            ];
        })->values()->all();

        $existingDswd = DB::table('typh_dswd_form')
            ->where('serial_number', $family->serial_number)
            ->latest('id')
            ->first();

        $savedMembers = null;
        if (!empty($existingDswd?->family_members)) {
            $decoded = is_string($existingDswd->family_members)
                ? json_decode($existingDswd->family_members, true)
                : $existingDswd->family_members;
            if (is_array($decoded) && count($decoded) > 0) {
                $savedMembers = $decoded;
            }
        }

        return [
            'id'                   => $family->id,
            'dswd_id'              => $existingDswd?->id,
            'serial_number'        => $family->serial_number,
            'date_registered'      => $existingDswd?->date_registered
                                        ?: ($family->checked_in_at ? $family->checked_in_at->format('Y-m-d')
                                        : ($family->created_at ? $family->created_at->format('Y-m-d') : date('Y-m-d'))),
            'region'               => $existingDswd?->region ?: $region,
            'province'             => $existingDswd?->province ?: $province,
            'city_municipality'    => $existingDswd?->city_municipality ?: $city,
            'district'             => $existingDswd?->district ?: $district,
            'barangay'             => $existingDswd?->barangay ?: $barangay,
            'evacuation_center'    => $existingDswd?->evacuation_center ?: $evacuationCenterName,
            'last_name'            => $existingDswd?->last_name ?: $parsedName['last_name'],
            'first_name'           => $existingDswd?->first_name ?: $parsedName['first_name'],
            'middle_name'          => $existingDswd?->middle_name ?: $parsedName['middle_name'],
            'name_ext'             => $existingDswd?->name_ext ?: $parsedName['name_ext'],
            'birthdate'            => $existingDswd?->birthdate ?: '',
            'age'                  => $existingDswd?->age ?? $age,
            'birthplace'           => $existingDswd?->birthplace ?: '',
            'gender'               => $existingDswd?->gender ? ucfirst(strtolower($existingDswd->gender)) : $gender,
            'civil_status'         => $existingDswd?->civil_status ?: '',
            'mothers_maiden_name'  => $existingDswd?->mothers_maiden_name ?: '',
            'religion'             => $existingDswd?->religion ?: '',
            'occupation'           => $existingDswd?->occupation ?: '',
            'household_income'     => $existingDswd?->household_income ?: '',
            'id_card_presented'    => $existingDswd?->id_card_presented ?: '',
            'id_card_number'       => $existingDswd?->id_card_number ?: '',
            'contact_primary'      => $existingDswd?->contact_number ?: $contact,
            'contact_alternate'    => $existingDswd?->phone_number ?: '',
            'addr_house'           => $existingDswd?->addr_house ?: '',
            'addr_street'          => $existingDswd?->addr_street ?: $street,
            'addr_subd'            => $existingDswd?->addr_subd ?: '',
            'addr_barangay'        => $existingDswd?->addr_barangay ?: $barangay,
            'addr_city'            => $existingDswd?->addr_city ?: $city,
            'addr_province'        => $existingDswd?->addr_province ?: $province,
            'addr_zip'             => $existingDswd?->addr_zip ?: '',
            'is_4ps'               => (bool)($existingDswd?->is_4ps ?? false),
            'is_ip'                => (bool)($existingDswd?->is_ip ?? false),
            'ip_ethnicity'         => $existingDswd?->ip_ethnicity ?: '',
            'bank_ewallet'         => $existingDswd?->bank_ewallet ?: '',
            'account_name'         => $existingDswd?->account_name ?: '',
            'account_type'         => $existingDswd?->account_type ?: '',
            'account_number'       => $existingDswd?->account_number ?: '',
            'house_ownership'      => $existingDswd?->house_ownership ?: '',
            'shelter_damage'       => $existingDswd?->shelter_damage ?: '',
            'sig_barangay_captain' => $existingDswd?->sig_barangay_captain ?: '',
            'sig_lswdo'            => $existingDswd?->sig_lswdo ?: '',
            'family_members'       => $savedMembers ?? $membersList,
        ];
    }

    private function parsePersonName(string $fullName): array
    {
        $fullName = trim($fullName);
        if ($fullName === '') {
            return ['first_name' => '', 'middle_name' => '', 'last_name' => '', 'name_ext' => ''];
        }

        $extensions = ['JR', 'JR.', 'SR', 'SR.', 'II', 'III', 'IV', 'V', 'VI'];
        $surnamePrefixes = ['SAN', 'SANTA', 'STA', 'STA.', 'STO', 'STO.', 'DE', 'DEL', 'DELA', 'DE LA', 'DELOS', 'DE LOS', 'DELAS', 'DE LAS', 'VAN', 'VON', 'MC', 'MAC'];

        $nameExt = '';

        if (str_contains($fullName, ',')) {
            $parts = explode(',', $fullName, 2);
            $lastName = trim($parts[0]);
            $remainder = trim($parts[1]);

            $remainderWords = preg_split('/\s+/', $remainder);
            if (count($remainderWords) > 1 && in_array(strtoupper(end($remainderWords)), $extensions, true)) {
                $nameExt = array_pop($remainderWords);
            }

            $firstName = array_shift($remainderWords) ?? '';
            $middleName = implode(' ', $remainderWords);

            return [
                'first_name'  => $firstName,
                'middle_name' => $middleName,
                'last_name'   => $lastName,
                'name_ext'    => $nameExt,
            ];
        }

        $words = preg_split('/\s+/', $fullName);
        if (count($words) === 1) {
            return ['first_name' => $words[0], 'middle_name' => '', 'last_name' => '', 'name_ext' => ''];
        }

        if (in_array(strtoupper(end($words)), $extensions, true)) {
            $nameExt = array_pop($words);
        }

        if (count($words) === 1) {
            return ['first_name' => $words[0], 'middle_name' => '', 'last_name' => '', 'name_ext' => $nameExt];
        }

        if (count($words) === 2) {
            return [
                'first_name'  => $words[0],
                'middle_name' => '',
                'last_name'   => $words[1],
                'name_ext'    => $nameExt,
            ];
        }

        $secondToLast = strtoupper($words[count($words) - 2]);
        if (in_array($secondToLast, $surnamePrefixes, true)) {
            $lastName = $words[count($words) - 2] . ' ' . $words[count($words) - 1];
            array_splice($words, count($words) - 2, 2);
            $firstName = array_shift($words) ?? '';
            $middleName = implode(' ', $words);

            return [
                'first_name'  => $firstName,
                'middle_name' => $middleName,
                'last_name'   => $lastName,
                'name_ext'    => $nameExt,
            ];
        }

        $lastName = array_pop($words);
        $firstName = array_shift($words);
        $middleName = implode(' ', $words);

        return [
            'first_name'  => $firstName,
            'middle_name' => $middleName,
            'last_name'   => $lastName,
            'name_ext'    => $nameExt,
        ];
    }

    public function submitDSWDForm(Request $request)
    {
        $contactNumber = $request->input('contact_number') ?: $request->input('contact_primary');
        $phoneNumber = $request->input('phone_number') ?: $request->input('contact_alternate');

        $request->merge([
            'contact_number' => $contactNumber,
            'phone_number'   => $phoneNumber,
        ]);

        $validator = Validator::make($request->all(), [
            'dswd_id'             => 'nullable|integer',
            'serial_number'       => 'nullable|string|max:50',
            'date_registered'     => 'nullable|date',
            'region'              => 'nullable|string|max:100',
            'province'            => 'nullable|string|max:100',
            'city_municipality'   => 'nullable|string|max:100',
            'district'            => 'nullable|string|max:100',
            'barangay'            => 'nullable|string|max:100',
            'evacuation_center'   => 'nullable|string|max:255',
            'last_name'           => 'required|string|max:100',
            'first_name'          => 'required|string|max:100',
            'middle_name'         => 'nullable|string|max:100',
            'name_ext'            => 'nullable|string|max:20',
            'birthdate'           => 'nullable|date',
            'age'                 => 'nullable|integer|min:0|max:150',
            'birthplace'          => 'nullable|string|max:150',
            'gender'              => 'nullable|string|max:20',
            'civil_status'        => 'nullable|string|max:30',
            'mothers_maiden_name' => 'nullable|string|max:200',
            'religion'            => 'nullable|string|max:100',
            'contact_number'      => 'nullable|string|max:30',
            'occupation'          => 'nullable|string|max:100',
            'household_income'    => 'nullable|numeric|min:0',
            'id_card_presented'   => 'nullable|string|max:100',
            'id_card_number'      => 'nullable|string|max:100',
            'phone_number'        => 'nullable|string|max:30',
            'addr_house'          => 'nullable|string|max:255',
            'addr_street'         => 'nullable|string|max:255',
            'addr_subd'           => 'nullable|string|max:255',
            'addr_barangay'       => 'nullable|string|max:255',
            'addr_city'           => 'nullable|string|max:255',
            'addr_province'       => 'nullable|string|max:255',
            'addr_zip'            => 'nullable|string|max:20',
            'is_4ps'              => 'nullable',
            'is_ip'               => 'nullable',
            'ip_ethnicity'        => 'nullable|string|max:150',
            'bank_ewallet'        => 'nullable|string|max:150',
            'account_name'        => 'nullable|string|max:150',
            'account_type'        => 'nullable|string|max:100',
            'account_number'      => 'nullable|string|max:100',
            'house_ownership'     => 'nullable|string|max:50',
            'shelter_damage'      => 'nullable|string|max:50',
            'sig_barangay_captain'=> 'nullable|string|max:150',
            'sig_lswdo'           => 'nullable|string|max:150',
            'privacy_consent'     => 'nullable',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $familyMembers = [];
        if ($request->has('family_members')) {
            $inputMembers = $request->input('family_members');
            if (is_string($inputMembers)) {
                $decoded = json_decode($inputMembers, true);
                $familyMembers = is_array($decoded) ? $decoded : [];
            } elseif (is_array($inputMembers)) {
                $familyMembers = $inputMembers;
            }
        } elseif ($request->has('fam_name') && is_array($request->input('fam_name'))) {
            $names = $request->input('fam_name', []);
            $relations = $request->input('fam_relation', []);
            $birthdates = $request->input('fam_birthdate', []);
            $ages = $request->input('fam_age', []);
            $sexes = $request->input('fam_sex', []);
            $educations = $request->input('fam_education', []);
            $occupations = $request->input('fam_occupation', []);
            $vulnerabilities = $request->input('fam_vulnerability', []);

            foreach ($names as $idx => $name) {
                $name = trim((string)$name);
                $rel = trim((string)($relations[$idx] ?? ''));
                if ($name !== '' || $rel !== '') {
                    $familyMembers[] = [
                        'name'          => $name,
                        'relation'      => $rel,
                        'birthdate'     => $birthdates[$idx] ?? '',
                        'age'           => $ages[$idx] ?? '',
                        'sex'           => $sexes[$idx] ?? '',
                        'education'     => $educations[$idx] ?? '',
                        'occupation'    => $occupations[$idx] ?? '',
                        'vulnerability' => $vulnerabilities[$idx] ?? '',
                    ];
                }
            }
        }

        $dataToSave = [
            'serial_number'        => $validated['serial_number'] ?? null,
            'date_registered'      => $validated['date_registered'] ?? null,
            'region'               => $validated['region'] ?? '',
            'province'             => $validated['province'] ?? '',
            'city_municipality'    => $validated['city_municipality'] ?? '',
            'district'             => $validated['district'] ?? null,
            'barangay'             => $validated['barangay'] ?? '',
            'evacuation_center'    => $validated['evacuation_center'] ?? null,
            'last_name'            => $validated['last_name'],
            'first_name'           => $validated['first_name'],
            'middle_name'          => $validated['middle_name'] ?? null,
            'name_ext'             => $validated['name_ext'] ?? null,
            'birthdate'            => $validated['birthdate'] ?? null,
            'age'                  => $validated['age'] ?? 0,
            'birthplace'           => $validated['birthplace'] ?? '',
            'gender'               => $validated['gender'] ?? null,
            'civil_status'         => $validated['civil_status'] ?? null,
            'mothers_maiden_name'  => $validated['mothers_maiden_name'] ?? '',
            'religion'             => $validated['religion'] ?? '',
            'contact_number'       => $validated['contact_number'] ?? '',
            'occupation'           => $validated['occupation'] ?? '',
            'household_income'     => $validated['household_income'] ?? null,
            'id_card_presented'    => $validated['id_card_presented'] ?? '',
            'id_card_number'       => $validated['id_card_number'] ?? '',
            'phone_number'         => $validated['phone_number'] ?? '',
            'addr_house'           => $request->input('addr_house') ?? null,
            'addr_street'          => $request->input('addr_street') ?? null,
            'addr_subd'            => $request->input('addr_subd') ?? null,
            'addr_barangay'        => $request->input('addr_barangay') ?? null,
            'addr_city'            => $request->input('addr_city') ?? null,
            'addr_province'        => $request->input('addr_province') ?? null,
            'addr_zip'             => $request->input('addr_zip') ?? null,
            'is_4ps'               => $request->boolean('is_4ps'),
            'is_ip'                => $request->boolean('is_ip'),
            'ip_ethnicity'         => $request->input('ip_ethnicity') ?? null,
            'bank_ewallet'         => $request->input('bank_ewallet') ?? null,
            'account_name'         => $request->input('account_name') ?? null,
            'account_type'         => $request->input('account_type') ?? null,
            'account_number'       => $request->input('account_number') ?? null,
            'house_ownership'      => $request->input('house_ownership') ?? null,
            'shelter_damage'       => $request->input('shelter_damage') ?? null,
            'sig_barangay_captain' => $request->input('sig_barangay_captain') ?? null,
            'sig_lswdo'            => $request->input('sig_lswdo') ?? null,
            'family_members'       => !empty($familyMembers) ? json_encode($familyMembers) : null,
            'privacy_consent'      => true,
            'updated_at'           => now(),
        ];

        $existingId = $request->input('dswd_id') ?: $request->input('id');
        if (!$existingId && !empty($dataToSave['serial_number'])) {
            $existingRecord = DB::table('typh_dswd_form')
                ->where('serial_number', $dataToSave['serial_number'])
                ->first();
            if ($existingRecord) {
                $existingId = $existingRecord->id;
            }
        }

        if ($existingId) {
            DB::table('typh_dswd_form')->where('id', $existingId)->update($dataToSave);
            $id = $existingId;
        } else {
            $dataToSave['created_at'] = now();
            $id = DB::table('typh_dswd_form')->insertGetId($dataToSave);
        }

        $record = DB::table('typh_dswd_form')->where('id', $id)->first();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Family registration saved permanently.',
                'record'  => $record,
            ]);
        }

        return redirect()->route('typhoon.dswd-form')->with('success', 'Family registration saved permanently.');
    }

    public function destroyDSWDForm(Request $request, $id)
    {
        $record = DB::table('typh_dswd_form')->where('id', $id)->first();
        if (!$record) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'DSWD record not found.',
                ], 404);
            }
            return redirect()->route('typhoon.dswd-form')->with('error', 'DSWD record not found.');
        }

        DB::table('typh_dswd_form')->where('id', $id)->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'DSWD record deleted successfully.',
            ]);
        }

        return redirect()->route('typhoon.dswd-form')->with('success', 'DSWD record deleted successfully.');
    }
}


