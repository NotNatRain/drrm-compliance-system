<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ComprehensiveSummaryFinding;
use App\Models\FireSafetyRoom;
use App\Models\HazardMapping;
use App\Models\School;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class HazardMappingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('module.access:hazard_mapping');
    }

    /**
     * Show the main hazard mapping dashboard.
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $schoolId = $request->input('school_id');
        $viewMode = strtolower((string) $request->input('view', 'floor'));
        if (!in_array($viewMode, ['school', 'building', 'floor'], true)) {
            $viewMode = 'floor';
        }

        // Get available schools for hazard mapping
        if ($user->role === 'admin') {
            $schools = School::orderBy('school_name')->get();
            $currentSchool = $schoolId ? School::findOrFail($schoolId) : ($schools->first() ?? null);
        } else {
            $currentSchool = School::find($user->school_id);
            if (!$currentSchool) {
                abort(403, 'You do not have access to any school.');
            }
            $schools = collect([$currentSchool]);
        }

        if (!$currentSchool) {
            abort(404, 'No schools available.');
        }

        $buildings = $currentSchool->fireSafetyBuildings()
            ->orderBy('building_no')
            ->orderBy('building_name')
            ->get();

        $currentBuilding = null;
        $requestedBuildingId = (int) $request->input('building_id', $buildings->first()->id ?? 0);
        if ($buildings->isNotEmpty() && $viewMode !== 'school') {
            $currentBuilding = $buildings->firstWhere('id', $requestedBuildingId) ?? $buildings->first();
        }

        $buildingFloorMap = $buildings->mapWithKeys(function ($building) {
            $maxFloor = max(1, (int) ($building->floors ?? 1));
            return [$building->id => range(1, $maxFloor)];
        });

        // Get all floors for current school
        $floors = HazardMapping::where('school_id', $currentSchool->id)
            ->orderBy('floor_number')
            ->get();

        // If no floors exist, create a default one
        if ($floors->isEmpty()) {
            $floor = HazardMapping::create([
                'school_id' => $currentSchool->id,
                'floor_number' => 1,
                'floor_name' => 'Ground Floor',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
            $floors = collect([$floor]);
        }

        $selectedFloorNumber = (int) $request->input('floor_number', $floors->first()->floor_number ?? 1);
        if ($selectedFloorNumber <= 0) {
            $selectedFloorNumber = 1;
        }

        // Backward compatibility when old floor_id links are used
        if ($request->filled('floor_id')) {
            $legacyFloor = $floors->firstWhere('id', (int) $request->input('floor_id'));
            if ($legacyFloor) {
                $selectedFloorNumber = (int) $legacyFloor->floor_number;
            }
        }

        $currentFloor = $floors->firstWhere('floor_number', $selectedFloorNumber);

        if (!$currentFloor) {
            $currentFloor = HazardMapping::create([
                'school_id' => $currentSchool->id,
                'floor_number' => $selectedFloorNumber,
                'floor_name' => $selectedFloorNumber === 1 ? 'Ground Floor' : "Floor {$selectedFloorNumber}",
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
            $floors = HazardMapping::where('school_id', $currentSchool->id)
                ->orderBy('floor_number')
                ->get();
        }

        $rooms = FireSafetyRoom::query()
            ->where('unified_school_id', $currentSchool->id)
            ->orderByRaw('COALESCE(building_id, 0)')
            ->orderByRaw('COALESCE(floor_no, 1)')
            ->orderBy('room_code')
            ->orderBy('room_name')
            ->get();

        $findings = ComprehensiveSummaryFinding::query()
            ->where('school_id', $currentSchool->id)
            ->latest('observation_date')
            ->latest('id')
            ->get();

        $hasFindingFloorNumber = Schema::hasColumn('cmpr_schl_sfty_sumFindings', 'floor_number');
        $hasFindingRoomCode = Schema::hasColumn('cmpr_schl_sfty_sumFindings', 'room_code');
        $hasFindingInsideDetails = Schema::hasColumn('cmpr_schl_sfty_sumFindings', 'chairs_count');

        $roomInsightLookup = $this->buildRoomInsightLookup(
            $findings,
            $hasFindingFloorNumber,
            $hasFindingRoomCode,
            $hasFindingInsideDetails
        );

        $buildingStructures = $this->buildBuildingStructures(
            $buildings,
            $rooms,
            $findings,
            $roomInsightLookup,
            $hasFindingFloorNumber
        );

        $currentBuildingStructure = null;
        if ($currentBuilding) {
            $currentBuildingStructure = collect($buildingStructures)->firstWhere('id', (int) $currentBuilding->id);
        }

        $currentFloorStructure = null;
        if (is_array($currentBuildingStructure)) {
            $currentFloorStructure = collect($currentBuildingStructure['floors'] ?? [])->firstWhere('number', (int) $selectedFloorNumber);
        }

        $schoolHazardSummary = [
            'total_buildings' => $buildings->count(),
            'total_rooms' => $rooms->count(),
            'total_floors' => (int) collect($buildingStructures)->sum(fn ($row) => count($row['floors'] ?? [])),
            'total_hazards' => (int) collect($floors)->sum(fn ($f) => count((array) ($f->hazards ?? []))),
            'total_vulnerabilities' => (int) collect($floors)->sum(fn ($f) => count((array) ($f->vulnerabilities ?? []))),
            'total_findings' => $findings->count(),
            'high_findings' => $findings->where('priority', 'high')->count(),
            'medium_findings' => $findings->where('priority', 'medium')->count(),
            'low_findings' => $findings->where('priority', 'low')->count(),
        ];

        ActivityLog::log('hazard_mapping', "Viewed hazard mapping dashboard for {$currentSchool->school_name}");

        return view('hazard-mapping.dashboard', compact(
            'schools',
            'currentSchool',
            'viewMode',
            'buildings',
            'currentBuilding',
            'buildingFloorMap',
            'selectedFloorNumber',
            'floors',
            'currentFloor',
            'buildingStructures',
            'currentBuildingStructure',
            'currentFloorStructure',
            'schoolHazardSummary',
            'user'
        ));
    }

    private function buildBuildingStructures(
        Collection $buildings,
        Collection $rooms,
        Collection $findings,
        array $roomInsightLookup,
        bool $hasFindingFloorNumber
    ): array {
        $roomGroups = $rooms->groupBy('building_id');

        return $buildings->map(function ($building) use ($roomGroups, $findings, $roomInsightLookup, $hasFindingFloorNumber) {
            $buildingRooms = collect($roomGroups->get($building->id, []));
            $maxFloor = max(1, (int) ($building->floors ?? $buildingRooms->max('floor_no') ?? 1));
            $buildingFindings = $findings->where('building_id', (int) $building->id);

            $stairsCount = $buildingRooms->filter(function ($room) {
                $haystack = strtolower(trim((string) ($room->room_type . ' ' . $room->room_name . ' ' . $room->room_code)));
                return str_contains($haystack, 'stair');
            })->count();

            $hallwayCount = $buildingRooms->filter(function ($room) {
                $haystack = strtolower(trim((string) ($room->room_type . ' ' . $room->room_name . ' ' . $room->room_code)));
                return str_contains($haystack, 'hall') || str_contains($haystack, 'corridor') || str_contains($haystack, 'pathway') || str_contains($haystack, 'passage');
            })->count();

            if ($stairsCount === 0 && $maxFloor > 1) {
                $stairsCount = 1;
            }

            $floors = collect(range(1, $maxFloor))->map(function ($floorNo) use ($buildingRooms, $building, $roomInsightLookup, $buildingFindings, $hasFindingFloorNumber) {
                $floorRooms = $buildingRooms
                    ->filter(fn ($room) => max(1, (int) ($room->floor_no ?? 1)) === (int) $floorNo)
                    ->values();

                $floorFindingCount = $hasFindingFloorNumber
                    ? $buildingFindings->where('floor_number', (int) $floorNo)->count()
                    : $buildingFindings->count();

                $roomCards = $floorRooms->map(function ($room) use ($building, $floorNo, $roomInsightLookup) {
                    $roomCode = trim((string) ($room->room_code ?? ''));
                    $roomLookupKey = strtoupper((int) $building->id . '|' . (int) $floorNo . '|' . $roomCode);
                    $insideInfo = $roomInsightLookup[$roomLookupKey] ?? null;

                    return [
                        'id' => (int) $room->id,
                        'room_code' => $roomCode !== '' ? $roomCode : ('ROOM-' . $room->id),
                        'room_name' => trim((string) ($room->room_name ?? 'Room ' . $room->id)),
                        'room_type' => trim((string) ($room->room_type ?? 'room')),
                        'remarks' => $room->remarks,
                        'has_smoke_detector' => (bool) $room->has_smoke_detector,
                        'has_secondary_exit' => (bool) $room->has_secondary_exit,
                        'inside_info' => $insideInfo,
                    ];
                })->values()->all();

                return [
                    'number' => (int) $floorNo,
                    'label' => (int) $floorNo === 1 ? 'Ground Floor' : ('Floor ' . $floorNo),
                    'rooms_count' => count($roomCards),
                    'finding_count' => (int) $floorFindingCount,
                    'rooms' => $roomCards,
                ];
            })->values()->all();

            return [
                'id' => (int) $building->id,
                'building_no' => $building->building_no,
                'name' => $building->building_name ?: ('Building ' . $building->building_no),
                'floors_count' => (int) $maxFloor,
                'rooms_count' => $buildingRooms->count(),
                'stairs_count' => (int) $stairsCount,
                'hallways_count' => (int) $hallwayCount,
                'alarms_count' => (int) ($building->functional_alarms_count ?? 0),
                'extinguishers_count' => (int) ($building->active_extinguishers_count ?? 0),
                'findings_count' => $buildingFindings->count(),
                'floors' => $floors,
            ];
        })->values()->all();
    }

    private function buildRoomInsightLookup(
        Collection $findings,
        bool $hasFindingFloorNumber,
        bool $hasFindingRoomCode,
        bool $hasFindingInsideDetails
    ): array {
        if (!$hasFindingRoomCode || !$hasFindingInsideDetails) {
            return [];
        }

        $lookup = [];
        foreach ($findings as $finding) {
            $roomCode = strtoupper(trim((string) ($finding->room_code ?? '')));
            if ($roomCode === '') {
                continue;
            }

            $floorNo = $hasFindingFloorNumber ? max(1, (int) ($finding->floor_number ?? 1)) : 1;
            $key = strtoupper((int) $finding->building_id . '|' . (int) $floorNo . '|' . $roomCode);

            if (array_key_exists($key, $lookup)) {
                continue;
            }

            $lookup[$key] = [
                'chairs_count' => (int) ($finding->chairs_count ?? 0),
                'tables_count' => (int) ($finding->tables_count ?? 0),
                'tv_count' => (int) ($finding->tv_count ?? 0),
                'electric_fan_count' => (int) ($finding->electric_fan_count ?? 0),
                'ceiling_fan_count' => (int) ($finding->ceiling_fan_count ?? 0),
                'water_dispenser_count' => (int) ($finding->water_dispenser_count ?? 0),
                'window_type' => trim((string) ($finding->window_type ?? '')),
                'source_note' => 'From Comprehensive Summary of Findings',
            ];
        }

        return $lookup;
    }

    /**
     * Show the floor-based hazard map editor.
     */
    public function editFloor(Request $request, int $schoolId, int $floorId)
    {
        $user = Auth::user();
        $school = $this->getAuthorizedSchool($schoolId);
        $floor = HazardMapping::findOrFail($floorId);

        if ($floor->school_id != $school->id) {
            abort(403, 'Unauthorized access to this floor.');
        }

        // Get all floors for navigation
        $floors = HazardMapping::where('school_id', $school->id)
            ->orderBy('floor_number')
            ->get();

        ActivityLog::log('hazard_mapping', "Edited floor {$floor->floor_name} in {$school->school_name}");

        return view('hazard-mapping.editor', compact('school', 'floor', 'floors', 'user'));
    }

    /**
     * Update hazard mapping for a floor.
     */
    public function updateFloor(Request $request, int $schoolId, int $floorId)
    {
        $user = Auth::user();
        $school = $this->getAuthorizedSchool($schoolId);
        $floor = HazardMapping::findOrFail($floorId);

        if ($floor->school_id != $school->id) {
            abort(403, 'Unauthorized access to this floor.');
        }

        $validated = $request->validate([
            'floor_name' => 'required|string|max:255',
            'hazards' => 'nullable|array',
            'vulnerabilities' => 'nullable|array',
            'evacuation_routes' => 'nullable|array',
            'assembly_points' => 'nullable|array',
            'safe_zones' => 'nullable|array',
            'hazard_zones' => 'nullable|array',
            'notes' => 'nullable|string',
            'map_data' => 'nullable|array',
        ]);

        $floor->update([
            ...$validated,
            'updated_by' => $user->id,
        ]);

        ActivityLog::log('hazard_mapping', "Updated floor {$floor->floor_name} in {$school->school_name}");

        return response()->json([
            'success' => true,
            'message' => 'Floor hazard mapping updated successfully.',
        ]);
    }

    /**
     * Add a new floor to the school.
     */
    public function addFloor(Request $request, int $schoolId)
    {
        $user = Auth::user();
        $school = $this->getAuthorizedSchool($schoolId);

        $validated = $request->validate([
            'floor_number' => 'required|integer|min:1|max:50',
            'floor_name' => 'required|string|max:255',
        ]);

        $existing = HazardMapping::where('school_id', $school->id)
            ->where('floor_number', $validated['floor_number'])
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Floor number already exists.',
            ], 422);
        }

        $floor = HazardMapping::create([
            'school_id' => $school->id,
            'floor_number' => $validated['floor_number'],
            'floor_name' => $validated['floor_name'],
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        ActivityLog::log('hazard_mapping', "Added new floor {$floor->floor_name} to {$school->school_name}");

        return response()->json([
            'success' => true,
            'message' => 'Floor added successfully.',
            'floor' => $floor,
        ]);
    }

    /**
     * Get authorized school for the current user.
     */
    private function getAuthorizedSchool(int $schoolId): School
    {
        $school = School::findOrFail($schoolId);
        $user = Auth::user();

        if ($user->role !== 'admin' && $user->school_id != $schoolId) {
            abort(403, 'Unauthorized access to this school.');
        }

        return $school;
    }
}
