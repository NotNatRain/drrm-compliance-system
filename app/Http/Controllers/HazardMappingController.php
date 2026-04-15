<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\HazardMapping;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if ($buildings->isNotEmpty()) {
            $requestedBuildingId = (int) $request->input('building_id', $buildings->first()->id);
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

        ActivityLog::log('hazard_mapping', "Viewed hazard mapping dashboard for {$currentSchool->school_name}");

        return view('hazard-mapping.dashboard', compact(
            'schools',
            'currentSchool',
            'buildings',
            'currentBuilding',
            'buildingFloorMap',
            'selectedFloorNumber',
            'floors',
            'currentFloor',
            'user'
        ));
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
