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

        // Determine active school (admins can switch; contributors only see their own)
        $activeSchoolId = session('typhoon_active_school_id');
        if ($user->role !== 'admin') {
            $activeSchoolId = $user->school_id;
        }

        $evacuationCentersQuery = TypFldEvacuationCenter::query()
            ->with('school');

        if ($user->role !== 'admin' && $activeSchoolId) {
            $evacuationCentersQuery->where('school_id', $activeSchoolId);
        } elseif ($activeSchoolId && $user->role !== 'admin') {
            // For admins, show ALL even if activeSchoolId is set
            $evacuationCentersQuery->where('school_id', $activeSchoolId);
        }

        $evacuationCenters = $evacuationCentersQuery->get()->map(function ($ec) {
            // Occupancy = total members of families that are checked-in and not checked-out
            $currentOccupancy = TypFldFamilyMember::query()
                ->join('typ_fld_families', 'typ_fld_families.id', '=', 'typ_fld_family_members.family_id')
                ->where('typ_fld_families.evacuation_center_id', $ec->id)
                ->whereNull('typ_fld_families.checked_out_at')
                ->count();

            $ec->current_occupancy = $currentOccupancy;
            return $ec;
        });

        $baseFamiliesQuery = TypFldFamily::query();
        $baseMembersQuery = TypFldFamilyMember::query();

        if ($activeSchoolId) {
            $centerIds = TypFldEvacuationCenter::where('school_id', $activeSchoolId)->pluck('id');
            $baseFamiliesQuery->whereIn('evacuation_center_id', $centerIds);
            $baseMembersQuery->whereIn('family_id', function ($q) use ($centerIds) {
                $q->select('id')->from('typ_fld_families')->whereIn('evacuation_center_id', $centerIds);
            });
        } elseif ($user->role !== 'admin') {
            // contributor with no assigned school => show empty
            $baseFamiliesQuery->whereRaw('1=0');
            $baseMembersQuery->whereRaw('1=0');
        }

        $activeFamiliesQuery = (clone $baseFamiliesQuery)->whereNull('checked_out_at');

        $totalFamilies = (clone $activeFamiliesQuery)->count();
        $totalEvacuees = (clone $baseMembersQuery)->join('typ_fld_families as f', 'f.id', '=', 'typ_fld_family_members.family_id')
            ->whereNull('f.checked_out_at')
            ->count();

        $missingCount = (clone $baseMembersQuery)->where('status', 'missing')->count();
        $injuredCount = (clone $baseMembersQuery)->where('status', 'injured')->count();
        $deceasedCount = (clone $baseMembersQuery)->where('status', 'deceased')->count();

        $vulnerableCounts = [
            'pregnant' => (clone $activeFamiliesQuery)->where('has_pregnant', true)->count(),
            'pwd' => (clone $activeFamiliesQuery)->where('has_pwd', true)->count(),
            'senior' => (clone $activeFamiliesQuery)->where('has_senior', true)->count(),
        ];

        // latest monitoring snapshots (optional)
        $activeCenter = $activeSchoolId
            ? TypFldEvacuationCenter::where('school_id', $activeSchoolId)->first()
            : null;

        $floodMonitoring = null;
        $typhoonData = null;

        if ($activeCenter) {
            $floodMonitoring = TypFldMonitoringSnapshot::where('evacuation_center_id', $activeCenter->id)
                ->where('type', 'flood')
                ->latest('recorded_at')
                ->first();
            $typhoonData = TypFldMonitoringSnapshot::where('evacuation_center_id', $activeCenter->id)
                ->where('type', 'typhoon')
                ->latest('recorded_at')
                ->first();
        }

        return view('typhoon.dashboard', [
            'evacuationCenters' => $evacuationCenters,
            'totalFamilies' => $totalFamilies,
            'totalEvacuees' => $totalEvacuees,
            'openEvacuationCentersCount' => $evacuationCenters->where('usage_status', '!=', 'cleared')->count(),
            'incidentMonitoring' => [
                'major' => 0, // Placeholder
                'minor' => 0, // Placeholder
            ],
            'rainfall' => [
                'bangal' => '0.0', // Placeholder
                'kalaklan' => '0.0', // Placeholder
            ],
            'missingCount' => $missingCount,
            'injuredCount' => $injuredCount,
            'deceasedCount' => $deceasedCount,
            'vulnerableCounts' => $vulnerableCounts,
            'recentEvacuees' => $totalEvacuees > 0,
            'recentlyRegistered' => (clone $activeFamiliesQuery)->whereDate('created_at', Carbon::today())->count(),
            'floodMonitoring' => $floodMonitoring ? (object) ($floodMonitoring->payload ?? []) : null,
            'typhoonData' => $typhoonData ? (object) ($typhoonData->payload ?? []) : null,
            'activeSchoolId' => $activeSchoolId,
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
        if ($user->role !== 'admin' && $user->school_id != $ec->school_id) {
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
        });

        return redirect()->route('typhoon.dashboard')->with('success', 'Family registered successfully.');
    }

    public function showEvacuationCenter($id)
    {
        $ec = TypFldEvacuationCenter::with('school')->findOrFail($id);
        $user = auth()->user();
        if ($user->role !== 'admin' && $user->school_id != $ec->school_id) {
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

        return view('typhoon.evacuation-center', [
            'ec' => $ec,
            'families' => $families,
            'lastUsedAt' => $lastUsedAt,
            'currentOccupancy' => $currentOccupancy,
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

        return redirect()->route('typhoon.dashboard')->with('success', 'Evacuation center created.');
    }

    public function updateEvacuationCenter(Request $request, $id)
    {
        $ec = TypFldEvacuationCenter::with('school')->findOrFail($id);
        $user = auth()->user();
        if ($user->role !== 'admin' && $user->school_id != $ec->school_id) {
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

        return redirect()->route('typhoon.evacuation-center.show', $ec->id)->with('success', 'Evacuation center updated.');
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
