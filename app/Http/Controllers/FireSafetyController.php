<?php

namespace App\Http\Controllers;

use App\Models\FireSafetySchoolSnapshot;
use Illuminate\Http\Request;
use App\Models\FireSafetySchool;
use App\Models\FireSafetyExtinguisher;
use App\Models\FireSafetyAlarmSystem;
use App\Models\FireSafetyBuilding;
use App\Models\FireSafetyEvacuationPlan;
use App\Models\FireSafetyInspection;
use App\Models\FireSafetyExtinguisherInspection;
use App\Models\FireSafetyRoom;
use App\Models\FireSafetyEvacuationDrill;
use App\Models\FireSafetyArchive;
use Carbon\Carbon;
use App\Models\User;
use App\Models\SystemConfiguration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class FireSafetyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $query = FireSafetySchool::withCount(['extinguishers', 'alarmSystems', 'buildings', 'evacuationPlans']);

        if (auth()->user()->role !== 'admin') {
            $query->where('id', auth()->user()->school_id);
        }

        $schools = $query->get()
            ->map(function($school) {
                // Set status and issues count
                if ($school->extinguishers_count === 0 || $school->alarm_systems_count === 0) {
                    $school->status = 'unconfigured';
                    $school->issues_count = 1;
                } else {
                    // Calculate based on actual data
                    $badExtinguisherStatuses = ['expired', 'broken', 'missing', 'for-purchase', 'empty'];
                    $expiredExtinguishers = $school->extinguishers()
                        ->whereIn('status', $badExtinguisherStatuses)
                        ->count();

                    $badAlarmStatuses = ['offline', 'broken', 'missing', 'jammed', 'under-repair', 'system-error'];
                    $offlineAlarms = $school->alarmSystems()
                        ->whereIn('status', $badAlarmStatuses)
                        ->count();

                    $school->issues_count = $expiredExtinguishers + $offlineAlarms;
                    $school->status = $this->calculateStatus($school);
                }

                $school->last_inspection_date = $school->extinguishers()
                    ->latest('date_checked')
                    ->first()
                    ?->date_checked ?? null;

                return $school;
            });

        $allAlerts = [];
        $allEvents = [];

        foreach ($schools as $school) {
            if ($school->alerts) {
                foreach ($school->alerts as $alert) {
                    $alert['school_name'] = $school->school_name;
                    $allAlerts[] = $alert;
                }
            }
            if ($school->events) {
                foreach ($school->events as $event) {
                    $event['school_name'] = $school->school_name;
                    $allEvents[] = $event;
                }
            }
        }

        // Sort by date
        usort($allAlerts, fn($a, $b) => strtotime($b['created_at']) - strtotime($a['created_at']));
        usort($allEvents, fn($a, $b) => strtotime($a['date']) - strtotime($b['date']));

        return view('fire-safety.dashboard', [
            'schools' => $schools,
            'allAlerts' => array_slice($allAlerts, 0, 10),
            'allEvents' => array_slice($allEvents, 0, 10)
        ]);
    }

    private function calculateStatus($school)
    {
        $issues = $school->issues_count;

        if ($issues === 0) return 'passed';
        if ($issues >= 3) return 'failed';
        return 'warning';
    }

    public function alarmSystems()
    {
        $query = FireSafetySchool::with(['alarmSystems.buildings', 'buildings']);

        if (auth()->user()->role !== 'admin') {
            $query->where('id', auth()->user()->school_id);
        }

        $schools = $query->get();
        $alarmTypes = SystemConfiguration::where('config_type', 'alarm_type')->where('is_active', true)->orderBy('sort_order')->get();
        $alarmStatusesByType = SystemConfiguration::where('config_type', 'alarm_status')->where('is_active', true)->whereNotNull('parent_id')->get()->groupBy('parent_id');

        return view('fire-safety.alarm-systems', [
            'schools' => $schools,
            'alarmTypes' => $alarmTypes,
            'alarmStatusesByType' => $alarmStatusesByType
        ]);
    }

    // Get buildings for a school (AJAX)
    public function getBuildings($schoolId)
    {
        $buildings = FireSafetyBuilding::where('school_id', $schoolId)->get();
        return response()->json($buildings);
    }

    // Get alarm details (AJAX)
    public function getAlarm($id)
    {
        try {
            $alarm = FireSafetyAlarmSystem::with(['building', 'school'])->findOrFail($id);
            return response()->json($alarm);

        } catch (\Exception $e) {
            Log::error('Error getting alarm: ' . $e->getMessage());
            return response()->json([
                'error' => 'Alarm not found',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    // Store new alarm
    public function storeAlarm(Request $request)
    {
        try {
            Log::info('Alarm store request received:', $request->all());

            $validated = $request->validate([
                'school_id' => 'required|exists:firesafety_school_information,id',
                'building_id' => 'nullable|exists:firesafety_buildings,id',
                'building_ids' => 'nullable|array',
                'building_ids.*' => 'exists:firesafety_buildings,id',
                'code' => 'required|string|max:50',
                'alarm_type' => 'required|in:Bell,Mechanical,Digital',
                'status' => 'required|string',
                'location' => 'required|string|max:255',
                'manufacturer' => 'nullable|string|max:100',
                'installation_date' => 'nullable|date',
                'last_test' => 'nullable|date',
                'next_test_due' => 'required|date',
                'notes' => 'nullable|string'
            ]);

            // Format status (convert to lowercase with underscores)
            $validated['status'] = strtolower(str_replace(' ', '_', $validated['status']));

            Log::info('Validation passed:', $validated);

            // Check if code already exists
            // Check if code already exists for this school
            $exists = FireSafetyAlarmSystem::where('school_id', $validated['school_id'])
                ->where('code', $validated['code'])
                ->exists();
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alarm code already exists in this school. Please use a different code.'
                ], 422);
            }

            $alarm = FireSafetyAlarmSystem::create($validated);

            if ($request->has('building_ids') && is_array($request->building_ids)) {
                $alarm->buildings()->sync($request->building_ids);
            } elseif ($request->building_id) {
                $alarm->buildings()->sync([$request->building_id]);
            }

            Log::info('Alarm created successfully:', ['id' => $alarm->id]);

            return response()->json([
                'success' => true,
                'message' => 'Alarm system added successfully!',
                'alarm_id' => $alarm->id
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error creating alarm: ' . $e->getMessage());
            Log::error('Stack trace:', $e->getTrace());
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update alarm
    public function updateAlarm(Request $request, $id)
    {
        $alarm = FireSafetyAlarmSystem::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'status' => 'required|string',
            'last_test' => 'nullable|date',
            'next_test_due' => 'required|date',
            'notes' => 'nullable|string',
            'manufacturer' => 'nullable|string|max:100',
            'installation_date' => 'nullable|date',
            'building_ids' => 'nullable|array',
            'building_ids.*' => 'exists:firesafety_buildings,id'
        ]);

        // Check if code changes to an existing one
        if ($validated['code'] !== $alarm->code) {
            $exists = FireSafetyAlarmSystem::where('school_id', $alarm->school_id)
                ->where('code', $validated['code'])
                ->where('id', '!=', $id)
                ->exists();
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alarm code already exists in this school.'
                ], 422);
            }
        }

        $statusMap = [
        'functional' => 'active',
        'broken' => 'maintenance',
        ];
        $originalStatus = strtolower(str_replace(' ', '_', $validated['status']));
        $validated['status'] = $statusMap[$originalStatus] ?? $originalStatus;

        $alarm->update($validated);

        if ($request->has('building_ids')) {
            $alarm->buildings()->sync($request->building_ids);
        }

        return response()->json([
            'success' => true,
            'message' => 'Alarm system updated successfully!'
        ]);
    }

    // Test alarm (update last test date)
    public function testAlarm($id)
    {
        $alarm = FireSafetyAlarmSystem::findOrFail($id);
        $alarm->last_test = now();
        $alarm->save();

        return response()->json([
            'success' => true,
            'message' => 'Alarm test recorded successfully!'
        ]);
    }

    // Remove alarm (Archive and Delete)
    public function removeAlarm(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $alarm = FireSafetyAlarmSystem::with(['building'])->findOrFail($id);

            // Create archive entry
            FireSafetyArchive::create([
                'school_id' => $alarm->school_id,
                'type' => 'alarm',
                'item_id' => $alarm->id,
                'item_code' => $alarm->code,
                'item_data' => [
                    'alarm_type' => $alarm->alarm_type,
                    'status' => $alarm->status,
                    'building_name' => $alarm->building?->building_name ?? 'N/A',
                    'manufacturer' => $alarm->manufacturer,
                    'last_test' => $alarm->last_test
                ],
                'reason' => $request->reason,
                'removed_at' => now()
            ]);

            // Delete associations if any (e.g., if there's a pivot for multi-building)
            DB::table('fire_safety_alarm_building')->where('alarm_id', $alarm->id)->delete();

            $alarm->delete();

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error removing alarm: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to remove alarm system.'], 500);
        }
    }

    public function getAlarmHistory($schoolId)
    {
        $archives = FireSafetyArchive::where('school_id', $schoolId)
            ->where('type', 'alarm')
            ->orderBy('removed_at', 'desc')
            ->get();

        return response()->json($archives);
    }

    public function extinguishers()
    {
        $query = FireSafetySchool::with([
            'buildings',
            'buildings.actualRooms',
            'buildings.fireExtinguishers.centerRoom',
            'buildings.fireExtinguishers.coveredRooms',
        ]);

        if (auth()->user()->role !== 'admin') {
            $query->where('id', auth()->user()->school_id);
        }

        $schools = $query->get();

        $calculatedPriorities = SystemConfiguration::where('config_type', 'calculated_priority')
            ->orderBy('sort_order')
            ->get();
        $roomTypes = SystemConfiguration::where('config_type', 'room_type')
            ->orderBy('sort_order')
            ->get();

        return view('fire-safety.extinguishers', [
            'schools' => $schools,
            'calculatedPriorities' => $calculatedPriorities,
            'roomTypes' => $roomTypes,
        ]);
    }

    // Get rooms for a building (AJAX)
    public function getRooms($buildingId)
    {
        $rooms = FireSafetyRoom::where('building_id', $buildingId)
            ->orderBy('floor_no')
            ->orderBy('room_name')
            ->get();

        return response()->json($rooms);
    }

    public function updateRoom(Request $request, $id)
    {
        $room = FireSafetyRoom::findOrFail($id);
        $validated = $request->validate([
            'room_code' => [
                'nullable', 'string',
                \Illuminate\Validation\Rule::unique('fire_safety_rooms')->where(function ($query) use ($room) {
                     return $query->where('school_id', $room->school_id);
                })->ignore($id)
            ],
            'room_name' => 'nullable|string',
            'nearest_extinguisher_room_id' => 'nullable|exists:fire_safety_rooms,id'
        ]);

        // Only update room_name if it was actually provided
        if (empty($validated['room_name'])) {
            unset($validated['room_name']);
        }

        $room->update($validated);

        // Sync Coverage Pivot Table
        if ($request->filled('nearest_extinguisher_room_id')) {
            // Find the extinguisher that has this nearest room as its CENTER room
            $ext = FireSafetyExtinguisher::where('room_id', $validated['nearest_extinguisher_room_id'])->first();
            if ($ext) {
                // Remove existing coverage for this room first
                DB::table('fire_safety_extinguisher_room_coverage')->where('room_id', $id)->delete();
                // Attach to the selected extinguisher
                $ext->coveredRooms()->attach($id);
            }
        } else {
            // If it was covered by something else and now is NOT, remove it
            // Only if it's NOT the center room of its own extinguisher
            $isCenter = FireSafetyExtinguisher::where('room_id', $id)->exists();
            if (!$isCenter) {
                DB::table('fire_safety_extinguisher_room_coverage')->where('room_id', $id)->delete();
            }
        }

        return response()->json(['success' => true]);
    }

    public function getNearestCandidateRooms($roomId)
    {
        $currentRoom = FireSafetyRoom::findOrFail($roomId);

        $extinguishers = FireSafetyExtinguisher::where('building_id', $currentRoom->building_id)
            ->where('status', '!=', 'missing')
            ->whereHas('centerRoom', function($q) use ($currentRoom) {
                $q->where('floor_no', $currentRoom->floor_no)
                  ->where('id', '!=', $currentRoom->id);
            })
            ->with(['centerRoom', 'coveredRooms'])
            ->get();

        $availableRooms = [];

        foreach ($extinguishers as $ext) {
            $count = $ext->coveredRooms->count();
            $centerRoomType = $ext->centerRoom->room_type;

            // Capacity limit is snapshotted per room (coverage_limit) to avoid retroactive changes when config changes.
            // Fallback to legacy rules when not set.
            $limit = (int) ($ext->centerRoom->coverage_limit ?? 0);
            if ($limit <= 0) {
                // Legacy default:
                // Shared Coverage (Classrooms, Departments, Libraries) = 3 total
                // Dedicated / Limited Shared (everything else) = 2 total
                if (in_array($centerRoomType, ['classroom', 'department', 'library'])) {
                    $limit = 3;
                } else {
                    $limit = 2;
                }
            }

            if ($count < $limit) {
                $availableRooms[] = [
                    'id' => $ext->centerRoom->id,
                    'room_name' => $ext->centerRoom->room_name,
                    'room_code' => $ext->centerRoom->room_code,
                    'extinguisher_code' => $ext->code,
                ];
            }
        }

        return response()->json($availableRooms);
    }

    // Store a room (AJAX)
public function storeRoom(Request $request)
{
    $validated = $request->validate([
        'school_id' => 'required|exists:firesafety_school_information,id',
        'building_id' => 'required|exists:firesafety_buildings,id',
        'room_code' => 'nullable|string|max:50',
        'room_name' => 'required|string|max:120',
        'room_type_config_id' => 'required|exists:system_configurations,id',
        'floor_no' => 'required|integer|min:1|max:50',
    ]);

    $roomTypeConfig = SystemConfiguration::where('id', $validated['room_type_config_id'])
        ->where('config_type', 'room_type')
        ->firstOrFail();

    $priorityConfig = null;
    if ($roomTypeConfig->parent_id) {
        $priorityConfig = SystemConfiguration::where('id', $roomTypeConfig->parent_id)
            ->where('config_type', 'calculated_priority')
            ->first();
    }
    $limit = (int) ($priorityConfig->max_rooms_covered ?? 0);
    if ($limit <= 0) {
        // safe default if misconfigured
        $limit = 2;
    }
    if ($limit > 5) {
        $limit = 5;
    }

    // Snapshot values so later config edits do NOT retroactively alter existing rooms.
    $validated['room_type'] = $roomTypeConfig->name;
    $validated['calculated_priority_label'] = $priorityConfig->name ?? null;
    $validated['coverage_limit'] = $limit;

    $building = FireSafetyBuilding::findOrFail($validated['building_id']);

    // Check building target rooms
    $totalTargetRooms = $building->rooms; // the standard number of rooms
    $totalFloors = $building->floors;
    $currentRoomsCount = $building->actualRooms()->count();

    if ($currentRoomsCount >= $totalTargetRooms) {
        return response()->json([
            'success' => false,
            'message' => "Standard room count ($totalTargetRooms) for this building reached. Cannot add more rooms."
        ], 422);
    }

    // Floor distribution constraint: at least 1 room per floor.
    // Logic: Remaining Slots >= Count of floors currently having 0 rooms (excluding the floor we are adding to if it was empty)

    $roomsByFloor = $building->actualRooms()->get()->groupBy('floor_no')->map->count();
    $emptyFloors = [];
    for ($i = 1; $i <= $totalFloors; $i++) {
        if (!isset($roomsByFloor[$i]) || $roomsByFloor[$i] === 0) {
            $emptyFloors[] = $i;
        }
    }

    $addingFloorIsEmpty = (!isset($roomsByFloor[$validated['floor_no']]) || $roomsByFloor[$validated['floor_no']] === 0);
    $otherEmptyFloorsCount = $addingFloorIsEmpty ? count($emptyFloors) - 1 : count($emptyFloors);
    $remainingSlotsAfterThis = $totalTargetRooms - $currentRoomsCount - 1;

    if ($remainingSlotsAfterThis < $otherEmptyFloorsCount) {
        return response()->json([
            'success' => false,
            'message' => 'Cannot add room to this floor. Doing so would prevent other floors from having at least one room assigned.'
        ], 422);
    }

    // Unique Room Code Check (Per School)
    if (!empty($validated['room_code'])) {
        $exists = FireSafetyRoom::where('school_id', $validated['school_id'])
            ->where('room_code', $validated['room_code'])
            ->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Room code already exists in this school.'
            ], 422);
        }
    }

    $room = FireSafetyRoom::create($validated);

    return response()->json([
        'success' => true,
        'message' => 'Room added successfully!',
        'room' => $room
    ]);
}
    // Get room details with extinguisher info
public function getRoom($id)
{
    $room = FireSafetyRoom::with(['building', 'extinguishersCoveringThisRoom'])->findOrFail($id);

    $room->is_center_room = FireSafetyExtinguisher::where('room_id', $id)->exists();

    // Find the extinguisher that covers this room
    $extinguisher = null;
    if ($room->extinguishersCoveringThisRoom->count() > 0) {
        $ext = $room->extinguishersCoveringThisRoom->first();
        $extinguisher = [
            'id' => $ext->id,
            'code' => $ext->code,
            'type' => $ext->type,
            'status' => $ext->status,
            'pressure_level' => $ext->pressure_level,
            'date_checked' => $ext->date_checked
        ];
    }

    return response()->json([
        'id' => $room->id,
        'room_code' => $room->room_code,
        'room_name' => $room->room_name,
        'room_type' => $room->room_type,
        'floor_no' => $room->floor_no,
        'nearest_extinguisher_room_id' => $room->nearest_extinguisher_room_id,
        'building' => $room->building->building_no ?? 'N/A',
        'extinguisher' => $extinguisher
    ]);
}
    // Store extinguisher (AJAX) - room-based coverage rules enforced here
    public function storeExtinguisher(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:firesafety_school_information,id',
            'building_id' => 'required|exists:firesafety_buildings,id',
            'code' => 'required|string|max:50',
            'type' => 'required|string|max:50', // ABC, CO2, etc.
            'status' => 'required|in:active,expired,maintenance,missing,purchase,decommissioned',
            'pressure_level' => 'required|integer|min:0|max:100',
            'date_checked' => 'required|date',
            'evaluation_result' => 'required|string|max:255',
            'room_id' => 'required|exists:fire_safety_rooms,id', // center room
            'covered_room_ids' => 'required|array|min:1|max:3',
            'covered_room_ids.*' => 'integer|exists:fire_safety_rooms,id',
            'remarks' => 'nullable|string',
        ]);

        // Ensure building belongs to school
        $building = FireSafetyBuilding::where('id', $validated['building_id'])
            ->where('school_id', $validated['school_id'])
            ->first();
        if (!$building) {
            return response()->json([
                'success' => false,
                'message' => 'Building does not belong to the selected school.'
            ], 422);
        }

        $centerRoom = FireSafetyRoom::where('id', $validated['room_id'])
            ->where('building_id', $validated['building_id'])
            ->where('school_id', $validated['school_id'])
            ->first();
        if (!$centerRoom) {
            return response()->json([
                'success' => false,
                'message' => 'Center room does not belong to the selected building/school.'
            ], 422);
        }

        $coveredRoomIds = array_values(array_unique($validated['covered_room_ids']));

        // Center room must be included
        if (!in_array((int) $validated['room_id'], array_map('intval', $coveredRoomIds), true)) {
            return response()->json([
                'success' => false,
                'message' => 'Covered rooms must include the center room.'
            ], 422);
        }

        // Validate all covered rooms belong to same building/school
        $coveredRooms = FireSafetyRoom::whereIn('id', $coveredRoomIds)
            ->where('building_id', $validated['building_id'])
            ->where('school_id', $validated['school_id'])
            ->get();
        if ($coveredRooms->count() !== count($coveredRoomIds)) {
            return response()->json([
                'success' => false,
                'message' => 'All covered rooms must belong to the selected building and school.'
            ], 422);
        }

        // Laboratory rule: lab can only share with ONE clinic/auxiliary room (total 2 rooms)
        if ($centerRoom->room_type === 'laboratory' && count($coveredRoomIds) > 2) {
            return response()->json([
                'success' => false,
                'message' => 'Laboratory center room can cover only itself, or itself + 1 clinic/auxiliary room.'
            ], 422);
        }
        if ($centerRoom->room_type === 'laboratory' && count($coveredRoomIds) === 2) {
            $otherRoom = $coveredRooms->firstWhere('id', '!=', $centerRoom->id);
            if (!$otherRoom || !in_array($otherRoom->room_type, ['clinic', 'auxiliary'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Laboratory can only share with a clinic or auxiliary room.'
                ], 422);
            }
        }

        // Enforce "1 extinguisher per room" coverage (no duplicate coverage)
        $alreadyCovered = DB::table('fire_safety_extinguisher_room_coverage')
            ->whereIn('room_id', $coveredRoomIds)
            ->pluck('room_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if (!empty($alreadyCovered)) {
            return response()->json([
                'success' => false,
                'message' => 'One or more selected rooms already have an extinguisher assigned.'
            ], 422);
        }

        // Ensure code is unique within the same school (simple constraint at app level)
        $codeExists = FireSafetyExtinguisher::where('school_id', $validated['school_id'])
            ->where('code', $validated['code'])
            ->exists();
        if ($codeExists) {
            return response()->json([
                'success' => false,
                'message' => 'Extinguisher code already exists for this school.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Automatic Evaluation Result: Only 'active' is Passed
            $evaluationResult = ($validated['status'] === 'active') ? 'Passed' : 'Failed';

            $ext = FireSafetyExtinguisher::create([
                'school_id' => $validated['school_id'],
                'building_id' => $validated['building_id'],
                'room_id' => $validated['room_id'],
                'code' => $validated['code'],
                'type' => $validated['type'],
                'status' => $validated['status'],
                'pressure_level' => $validated['pressure_level'],
                'date_checked' => $validated['date_checked'],
                'evaluation_result' => $evaluationResult,
                'remarks' => $validated['remarks'] ?? null,
            ]);

            $ext->coveredRooms()->sync($coveredRoomIds);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Extinguisher added successfully!',
                'extinguisher_id' => $ext->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing extinguisher: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add extinguisher: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update Extinguisher Status & Log Inspection
    public function updateExtinguisher(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,expired,maintenance,missing,purchase,decommissioned',
            'pressure_level' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $ext = FireSafetyExtinguisher::findOrFail($id);
            $ext->status = $request->status;
            $ext->pressure_level = $request->pressure_level;
            $ext->date_checked = now(); // Update checked date

            // Update Evaluation Result based on status
            $ext->evaluation_result = ($request->status === 'active') ? 'Passed' : 'Failed';

            $ext->save();

            // Log inspection
            FireSafetyExtinguisherInspection::create([
                'extinguisher_id' => $ext->id,
                'user_id' => Auth::id(),
                'inspection_date' => now(),
                'status' => $ext->status,
                'pressure_level' => $ext->pressure_level,
                'notes' => $request->notes
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Extinguisher updated successfully!']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating extinguisher: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update extinguisher.'], 500);
        }
    }

    public function removeExtinguisher(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $ext = FireSafetyExtinguisher::with(['building', 'room'])->findOrFail($id);

            // Create archive entry
            FireSafetyArchive::create([
                'school_id' => $ext->school_id,
                'type' => 'extinguisher',
                'item_id' => $ext->id,
                'item_code' => $ext->code,
                'item_data' => [
                    'type' => $ext->type,
                    'status' => $ext->status,
                    'pressure_level' => $ext->pressure_level,
                    'building_name' => $ext->building?->building_name ?? 'N/A',
                    'floor_no' => $ext->room?->floor_no ?? 'N/A',
                    'room_name' => $ext->room?->room_name ?? 'N/A'
                ],
                'reason' => $request->reason,
                'removed_at' => now()
            ]);

            // Remove coverage associations
            DB::table('fire_safety_extinguisher_room_coverage')->where('extinguisher_id', $ext->id)->delete();

            // Delete the extinguisher
            $ext->delete();

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getExtinguisherHistory($schoolId)
    {
        $archives = FireSafetyArchive::where('school_id', $schoolId)
            ->where('type', 'extinguisher')
            ->orderBy('removed_at', 'desc')
            ->get();

        return response()->json($archives);
    }

    // Get Recent Inspections (AJAX)
    public function getRecentExtinguisherInspections($schoolId)
    {
        $inspections = FireSafetyExtinguisherInspection::whereHas('extinguisher', function($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->with(['extinguisher', 'extinguisher.building', 'extinguisher.centerRoom', 'inspector'])
            ->latest('inspection_date')
            ->take(10) // Limit to last 10
            ->get()
            ->map(function($insp) {
                return [
                    'date' => \Carbon\Carbon::parse($insp->inspection_date)->format('Y-m-d'),
                    'code' => $insp->extinguisher->code,
                    'location' => ($insp->extinguisher->building->building_no ?? '?') . ' - ' . ($insp->extinguisher->centerRoom->room_name ?? '?'),
                    'inspector' => $insp->inspector->name ?? 'Unknown',
                    'status' => $insp->status,
                    'pressure_level' => $insp->pressure_level,
                    'notes' => $insp->notes
                ];
            });

        return response()->json($inspections);
    }

    public function buildings()
    {
        $query = FireSafetySchool::with(['buildings', 'buildings.alarmSystems', 'buildings.fireExtinguishers']);

        if (auth()->user()->role !== 'admin') {
            $query->where('id', auth()->user()->school_id);
        }

        $schools = $query->get();
        $buildingTypes = SystemConfiguration::where('config_type', 'building_type')->where('is_active', true)->orderBy('sort_order')->get();

        return view('fire-safety.buildings',[
            'schools' => $schools,
            'buildingTypes' => $buildingTypes
        ]);
    }

    public static function calculateBuildingCompliance($building)
    {
        // This is a simplified compliance calculation
        $score = 0;
        $maxScore = 100;

        // Check for alarms
        if ($building->alarmSystems->count() > 0) {
            $score += 30;
        }

        // Check for extinguishers
        if ($building->fireExtinguishers->count() > 0) {
            $score += 30;
        }

        // Check for emergency exits
        if ($building->emergency_exits && $building->emergency_exits > 0) {
            $score += 20;
        }

        // Check for safety features
        if ($building->features) {
            $features = explode(',', $building->features);
            $score += min(20, count($features) * 5);
        }

        return $score;
    }

    // Store new building
    public function storeBuilding(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:firesafety_school_information,id',
            'building_no' => 'required|string|max:50',
            'building_name' => 'nullable|string|max:100',
            'floors' => 'required|integer|min:1',
            'rooms' => 'required|integer|min:1',
            'year_constructed' => 'nullable|integer|min:1900|max:' . date('Y'),
            'last_renovation' => 'nullable|integer|min:1900|max:' . date('Y'),
            'emergency_exits' => 'nullable|integer|min:0',
            'building_type' => 'nullable|string',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'required_extinguishers' => 'nullable|integer|min:0'
        ]);

        // Unique Building Code Check (Per School)
        $exists = FireSafetyBuilding::where('school_id', $validated['school_id'])
            ->where('building_no', $validated['building_no'])
            ->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Building code already exists in this school.'
            ], 422);
        }

        // Ensure rooms >= floors
        if ($validated['rooms'] < $validated['floors']) {
            return response()->json([
                'success' => false,
                'message' => 'Total rooms cannot be less than total floors.'
            ], 422);
        }

        // Gymnasium & Cafeteria restriction: only 1 room
    if (in_array(strtolower($validated['building_type']), ['gymnasium', 'cafeteria'])) {
        $validated['rooms'] = 1;
    }

    // Initialize limit columns
    $validated['max_floors'] = $validated['floors'];
    $validated['max_rooms'] = $validated['rooms'];

    // Convert features array to comma-separated string
    if (isset($validated['features'])) {
        $validated['features'] = implode(',', $validated['features']);
    }
    $building = FireSafetyBuilding::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Building added successfully!'
        ]);
    }

    // Update building
    public function updateBuilding(Request $request, $id)
    {
        $building = FireSafetyBuilding::findOrFail($id);

        $validated = $request->validate([
            'building_no' => 'required|string|max:50',
            'building_name' => 'nullable|string|max:100',
            'floors' => 'nullable|integer|min:1',
            'rooms' => 'nullable|integer|min:1',
            'year_constructed' => 'nullable|integer|min:1900|max:' . date('Y'),
            'last_renovation' => 'nullable|integer|min:1900|max:' . date('Y'),
            'emergency_exits' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'removed_floor' => 'nullable|integer',
            'removed_room_id' => 'nullable|exists:fire_safety_rooms,id',
            'floor_removal_reason' => 'nullable|required_with:removed_floor|string|max:500',
            'room_removal_reason' => 'nullable|required_with:removed_room_id|string|max:500',
            'required_extinguishers' => 'nullable|integer|min:0'
        ]);

        // Check uniqueness if building_no is changing
        if ($validated['building_no'] !== $building->building_no) {
            $exists = FireSafetyBuilding::where('school_id', $building->school_id)
                ->where('building_no', $validated['building_no'])
                ->where('id', '!=', $id)
                ->exists();
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Building code already exists in this school.'
                ], 422);
            }
        }

        // Gymnasium & Cafeteria restriction is enforced at creation only
        // Building type cannot be changed after creation

        // Convert features array to comma-separated string
        if (isset($validated['features'])) {
            $validated['features'] = implode(',', $validated['features']);
        }

        // Handle Cascading Removals
        $cascadingMessages = [];

        DB::beginTransaction();
        try {
            // 1. Floor Removal
            if ($request->filled('removed_floor')) {
                $floorNo = $request->removed_floor;
                $reason = $request->floor_removal_reason;

                // Archive Floor
                FireSafetyArchive::create([
                    'school_id' => $building->school_id,
                    'type' => 'floor',
                    'item_id' => null,
                    'item_code' => "FLR-{$floorNo}",
                    'item_data' => [
                        'building_name' => $building->building_name ?? $building->building_no,
                        'building_no' => $building->building_no,
                        'floor_no' => $floorNo
                    ],
                    'reason' => $reason,
                    'removed_at' => now()
                ]);

                // Archive & Remove Alarms on this floor
                // Match location: "1st Floor", "2nd Floor", "Floor 1", "Floor 2", etc.
                $alarmsToRemove = FireSafetyAlarmSystem::where('building_id', $id)
                    ->where(function ($q) use ($floorNo) {
                        $q->where('location', 'like', "%{$floorNo}st Floor%")
                          ->orWhere('location', 'like', "%{$floorNo}nd Floor%")
                          ->orWhere('location', 'like', "%{$floorNo}rd Floor%")
                          ->orWhere('location', 'like', "%{$floorNo}th Floor%")
                          ->orWhere('location', 'like', "%Floor {$floorNo} %")
                          ->orWhere('location', 'like', "%Floor {$floorNo},%")
                          ->orWhere('location', 'like', "%Floor {$floorNo}-%")
                          ->orWhere('location', 'like', "%Floor {$floorNo}%");
                    })
                    ->get();

                foreach ($alarmsToRemove as $alarm) {
                    FireSafetyArchive::create([
                        'school_id' => $alarm->school_id,
                        'type' => 'alarm',
                        'item_id' => $alarm->id,
                        'item_code' => $alarm->code,
                        'item_data' => [
                            'alarm_type' => $alarm->alarm_type,
                            'status' => $alarm->status,
                            'building_name' => $building->building_name ?? 'N/A',
                            'manufacturer' => $alarm->manufacturer,
                            'last_test' => $alarm->last_test,
                            'cascaded_from' => "Floor {$floorNo} Removal"
                        ],
                        'reason' => "Cascading removal: Floor {$floorNo} removed. Reason: " . $reason,
                        'removed_at' => now()
                    ]);

                    $alarm->buildings()->detach();
                    $alarm->delete();
                }

                // Get rooms on this floor to remove extinguishers
                $roomsOnFloor = FireSafetyRoom::where('building_id', $id)->where('floor_no', $floorNo)->get();
                foreach ($roomsOnFloor as $room) {
                    $this->processRoomRemoval($room, "Cascading removal: Floor {$floorNo} removed. Reason: " . $reason);
                }

                $building->decrement('floors');
                $cascadingMessages[] = "Floor {$floorNo} and its associated safety systems were archived and removed.";
            }

            // 2. Room Removal
            if ($request->filled('removed_room_id')) {
                $room = FireSafetyRoom::findOrFail($request->removed_room_id);
                $reason = $request->room_removal_reason;
                $res = $this->processRoomRemoval($room, $reason);
                $building->decrement('rooms');
                if ($res) $cascadingMessages[] = $res;
            }

            // 3. Handle Floor and Room Increments (do not overwrite floors when we just removed one)
            if (!$request->filled('removed_floor') && $request->filled('floors') && (int)$request->floors > $building->floors) {
                $building->floors = (int)$request->floors;
            }

            if ($request->filled('rooms') && (int)$request->rooms > $building->rooms) {
                $building->rooms = (int)$request->rooms;
            }

            // Prepare fill data except for specific handling fields
            $fillData = collect($validated)->except(['removed_floor', 'removed_room_id', 'building_type', 'floors', 'rooms', 'floor_removal_reason', 'room_removal_reason'])->toArray();

            $building->fill($fillData);
            $building->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Building updated successfully!' . (count($cascadingMessages) > 0 ? ' ' . implode(' ', $cascadingMessages) : '')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating building: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update building: ' . $e->getMessage()
            ], 500);
        }
    }

    private function processRoomRemoval($room, $reason = 'Manual removal')
    {
        // Archive Room
        FireSafetyArchive::create([
            'school_id' => $room->school_id,
            'type' => 'room',
            'item_id' => $room->id,
            'item_code' => $room->room_code ?? "RM-{$room->id}",
            'item_data' => [
                'room_name' => $room->room_name,
                'room_type' => $room->room_type,
                'floor_no' => $room->floor_no,
                'building_name' => $room->building->building_name ?? $room->building->building_no
            ],
            'reason' => $reason,
            'removed_at' => now()
        ]);

        // Find extinguishers where this room is the center
        $extsAsCenter = FireSafetyExtinguisher::where('room_id', $room->id)->get();
        foreach ($extsAsCenter as $ext) {
            // Find other rooms covered by this extinguisher on same floor
            $otherRooms = $ext->coveredRooms()->where('fire_safety_rooms.id', '!=', $room->id)->get();
            if ($otherRooms->count() > 0) {
                $newCenter = $otherRooms->random();
                $ext->update(['room_id' => $newCenter->id]);
            } else {
                // Archive Extinguisher before delete
                FireSafetyArchive::create([
                    'school_id' => $ext->school_id,
                    'type' => 'extinguisher',
                    'item_id' => $ext->id,
                    'item_code' => $ext->code,
                    'item_data' => [
                        'type' => $ext->type,
                        'status' => $ext->status,
                        'pressure_level' => $ext->pressure_level,
                        'building_name' => $ext->building?->building_name ?? 'N/A',
                        'floor_no' => $room->floor_no ?? 'N/A',
                        'room_name' => $room->room_name ?? 'N/A',
                        'cascaded_from' => "Room {$room->room_name} Removal"
                    ],
                    'reason' => "Cascading removal: Room {$room->room_name} removed. Reason: " . $reason,
                    'removed_at' => now()
                ]);

                // Remove coverage associations
                DB::table('fire_safety_extinguisher_room_coverage')->where('extinguisher_id', $ext->id)->delete();
                $ext->delete(); // No rooms left to cover
            }
        }

        // Detach from all extinguisher coverages
        $room->extinguishersCoveringThisRoom()->detach();
        $roomName = $room->room_name;
        $room->delete();

        return "Room {$roomName} was archived and removed.";
    }

    // Get building details
    public function getBuilding($id)
    {
        Log::info('getBuilding called with id: ' . $id);
        try {
            $building = FireSafetyBuilding::with(['school', 'actualRooms'])->findOrFail($id);
            Log::info('Building found: ' . $building->id);

            // Check if user has access to this building's school
            if (auth()->user()->role !== 'admin' && $building->school_id !== auth()->user()->school_id) {
                Log::warning('Access denied for user ' . auth()->id() . ' to building ' . $id);
                return response()->json(['error' => 'Access denied'], 403);
            }

            // Add rooms_list for update modal
            $roomsList = $building->actualRooms->map(function($room) {
                // Check if room is a center room for any extinguisher
                $isCenterRoom = FireSafetyExtinguisher::where('room_id', $room->id)->exists();

                // Check if there are other rooms on the same floor
                $hasOthersOnFloor = FireSafetyRoom::where('building_id', $room->building_id)
                    ->where('floor_no', $room->floor_no)
                    ->where('id', '!=', $room->id)
                    ->exists();

                return [
                    'id' => $room->id,
                    'room_name' => $room->room_name,
                    'room_code' => $room->room_code,
                    'floor_no' => $room->floor_no,
                    'is_center_room' => $isCenterRoom,
                    'has_other_rooms_on_floor' => $hasOthersOnFloor
                ];
            });

            $buildingData = $building->toArray();
            $buildingData['rooms_list'] = $roomsList;
            $buildingData['required_extinguishers_count'] = $building->required_extinguishers_count;

            Log::info('Returning building data with rooms list');
            return response()->json($buildingData);
        } catch (\Exception $e) {
            Log::error('Error loading building details: ' . $e->getMessage());
            return response()->json(['error' => 'Building not found or error loading details'], 404);
        }
    }

    // Get inspections for a school
    public function getInspections($schoolId)
    {
        try {
            $inspections = FireSafetyInspection::where('school_id', $schoolId)
                ->with('building')
                ->orderBy('inspection_date', 'asc')
                ->get()
                ->map(function($inspection) {
                    return [
                        'id' => $inspection->id,
                        'inspection_date' => $inspection->inspection_date,
                        'building_name' => $inspection->building->building_no ?? 'N/A',
                        'inspection_type' => $inspection->inspection_type,
                        'inspector' => $inspection->inspector,
                        'status' => $inspection->status
                    ];
                });

            return response()->json($inspections);

        } catch (\Exception $e) {
            Log::error('Error loading inspections: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    // Get compliance stats for a school
    public function getComplianceStats($schoolId)
    {
        $buildings = FireSafetyBuilding::where('school_id', $schoolId)->get();

        $compliant = 0;
        $needsAttention = 0;
        $nonCompliant = 0;

        foreach ($buildings as $building) {
            $compliance = $this->calculateBuildingCompliance($building);

            if ($compliance >= 80) {
                $compliant++;
            } elseif ($compliance >= 60) {
                $needsAttention++;
            } else {
                $nonCompliant++;
            }
        }

        return response()->json([
            'compliant' => $compliant,
            'needs_attention' => $needsAttention,
            'non_compliant' => $nonCompliant
        ]);
    }

    // Get sidebar stats
    public function getSidebarStats($schoolId)
    {
        $stats = $this->getComplianceStats($schoolId);
        return response()->json(json_decode($stats->getContent()));
    }

    // Get buildings list for dropdown
    public function getBuildingsList($schoolId)
    {
        $buildings = FireSafetyBuilding::where('school_id', $schoolId)
            ->select('id', 'building_no', 'building_name', 'floors', 'rooms', 'building_type')
            ->get();

        return response()->json($buildings);
    }

    // Schedule inspection
    public function scheduleInspection(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:firesafety_school_information,id',
            'building_id' => 'required|exists:firesafety_buildings,id',
            'inspection_date' => 'required|date',
            'inspection_type' => 'required|string',
            'inspector' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $validated['status'] = 'scheduled';

        $inspection = FireSafetyInspection::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Inspection scheduled successfully!'
        ]);
    }

    // Get inspection details (AJAX) - used by Buildings page
    public function getInspection($id)
    {
        try {
            $inspection = FireSafetyInspection::with(['building', 'school'])->findOrFail($id);
            $data = $inspection->toArray();
            $data['building_name'] = $inspection->building->building_no ?? $inspection->building->building_name ?? 'N/A';
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error getting inspection: ' . $e->getMessage());
            return response()->json([
                'error' => 'Inspection not found',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    // Cancel inspection (AJAX) - used by Buildings page
    public function cancelInspection($id)
    {
        try {
            $inspection = FireSafetyInspection::findOrFail($id);
            $inspection->status = 'cancelled';
            $inspection->save();

            return response()->json([
                'success' => true,
                'message' => 'Inspection cancelled successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error cancelling inspection: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel inspection: ' . $e->getMessage()
            ], 500);
        }
    }

    // Placeholder checklist page to prevent 404 (until checklist UI is implemented)
    public function inspectionChecklist($id)
    {
        $inspection = FireSafetyInspection::with(['building', 'school'])->findOrFail($id);
        return view('fire-safety.inspection-checklist', [
            'inspection' => $inspection
        ]);
    }
    public function evacuationPlans()
    {
        $query = FireSafetySchool::with([
            'buildings' => function($query) {
                $query->with([
                    'evacuationPlan',
                    'alarmSystems' => function($q) {
                        $q->whereIn('status', ['functional', 'online']);
                    },
                    'fireExtinguishers' => function($q) {
                        $q->where('status', 'active');
                    },
                    'actualRooms'
                ]);
            },
            'buildings.evacuationPlan'
        ]);

        if (auth()->user()->role !== 'admin') {
            $query->where('id', auth()->user()->school_id);
        }

        $schools = $query->get();

        return view('fire-safety.evacuation-plans', compact('schools'));
    }
    public function storeEvacuationPlan(Request $request)
    {
        $request->validate([
            'building_id' => 'required|exists:firesafety_buildings,id',
            'plan_no' => 'required|string|max:50',
            'exits' => 'required|integer|min:0',
            'routes' => 'required|integer|min:1|max:10',
            'areas' => 'required|integer|min:1|max:10',
            'primary_route' => 'required|string',
            'primary_assembly_area' => 'required|string',
            'assembly_capacity' => 'nullable|integer|min:1',
            'status' => 'nullable|in:active,draft,review,inactive',
        ]);

        $plan = FireSafetyEvacuationPlan::create([
            'school_id' => $request->school_id,
            'building_id' => $request->building_id,
            'plan_no' => $request->plan_no,
            'exits' => $request->exits,
            'routes' => $request->routes,
            'areas' => $request->areas,
            'primary_route' => $request->primary_route,
            'secondary_route' => $request->secondary_route,
            'primary_assembly_area' => $request->primary_assembly_area,
            'secondary_assembly_area' => $request->secondary_assembly_area,
            'assembly_capacity' => $request->assembly_capacity ?? 0,
            'emergency_contacts' => $request->emergency_contacts,
            'special_instructions' => $request->special_instructions,
            'status' => $request->status ?? 'active',
            'approved_at' => $request->status === 'active' ? now() : null,
            'map_data' => $request->map_data,
        ]);

        return response()->json(['success' => true, 'message' => 'Evacuation plan created successfully']);
    }

    public function getEvacuationPlan($id)
    {
        $plan = FireSafetyEvacuationPlan::with('building')->findOrFail($id);
        return response()->json($plan);
    }

    public function getEvacuationPlanDetails($id)
    {
        $plan = FireSafetyEvacuationPlan::with(['building', 'school'])->findOrFail($id);

        // Load all buildings for the school with their safety components
        $schoolBuildings = FireSafetyBuilding::where('school_id', $plan->school_id)
            ->with(['actualRooms', 'fireExtinguishers', 'alarmSystemsMany'])
            ->get();

        return response()->json([
            'plan' => $plan,
            'building' => $plan->building,
            'school_buildings' => $schoolBuildings,
            'stats' => $plan->safety_equipment_summary,
            'status_label' => $plan->status_label,
            'status_color' => $plan->status_color
        ]);
    }

    public function updateEvacuationPlan(Request $request, $id)
    {
        $plan = FireSafetyEvacuationPlan::findOrFail($id);

        $request->validate([
            'plan_no' => 'required|string|max:50',
            'exits' => 'required|integer|min:1',
            'routes' => 'required|integer|min:1|max:5',
            'areas' => 'required|integer|min:1|max:3',
            'primary_route' => 'required|string',
            'primary_assembly_area' => 'required|string',
            'assembly_capacity' => 'required|integer|min:1',
            'status' => 'required|in:active,draft,review,inactive',
        ]);

        $data = $request->all();
        if ($request->status === 'active' && $plan->status !== 'active') {
            $data['approved_at'] = now();
        }

        $plan->update($data);

        return response()->json(['success' => true, 'message' => 'Evacuation plan updated successfully']);
    }

    public function deleteEvacuationPlan($id)
    {
        $plan = FireSafetyEvacuationPlan::findOrFail($id);
        $plan->delete();
        return response()->json(['success' => true, 'message' => 'Evacuation plan deleted successfully']);
    }

    public function checkBuildingPlan($buildingId)
    {
        $plan = FireSafetyEvacuationPlan::where('building_id', $buildingId)->first();
        return response()->json([
            'has_plan' => $plan !== null,
            'plan_id' => $plan ? $plan->id : null
        ]);
    }

    public function getBuildingEvacuationData($buildingId)
    {
        $building = FireSafetyBuilding::findOrFail($buildingId);
        $featuresRaw = $building->features;
        $featuresDisplay = 'No safety features recorded';
        if (!empty($featuresRaw)) {
            $arr = is_array($featuresRaw) ? $featuresRaw : explode(',', (string) $featuresRaw);
            $labels = [
                'sprinklers' => 'Sprinkler System',
                'emergency_lights' => 'Emergency Lighting',
                'exit_signs' => 'Exit Signs',
                'fire_doors' => 'Fire Doors',
                'two_stairways' => 'Two Stairways',
                'smoke_detectors' => 'Smoke Detectors',
                'alarm_systems' => 'Alarm Systems',
            ];
            $featuresDisplay = implode(', ', array_filter(array_map(function ($v) use ($labels) {
                $v = trim(strtolower($v));
                if (empty($v)) return null;
                // Try direct mapping first
                if (isset($labels[$v])) return $labels[$v];
                // Try cleaning up keys with spaces or underscores
                $cleanKey = str_replace([' ', '-'], '_', $v);
                if (isset($labels[$cleanKey])) return $labels[$cleanKey];
                
                return ucwords(str_replace(['_', '-'], ' ', $v));
            }, $arr)));
        }
        return response()->json([
            'building_name' => $building->building_name,
            'building_no' => $building->building_no,
            'rooms' => $building->rooms ?? 0,
            'exits' => $building->emergency_exits ?? 0,
            'alarms' => $building->functional_alarms_count,
            'extinguishers' => $building->active_extinguishers_count,
            'features' => $featuresDisplay,
        ]);
    }

    public function getDrillBuildings($schoolId)
    {
        $buildings = FireSafetyBuilding::where('school_id', $schoolId)->get();
        return response()->json($buildings);
    }

    public function scheduleDrill(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:firesafety_school_information,id',
            'drill_type' => 'required|string',
            'drill_date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'participants_count' => 'nullable|integer',
            'evacuation_time_minutes' => 'nullable|integer',
            'status' => 'required|string',
            'coordinator' => 'nullable|string',
            'remarks' => 'nullable|string',
            'notes' => 'nullable|string',
            'building_ids' => 'required|array',
        ]);

        $drill = FireSafetyEvacuationDrill::create($request->only([
            'school_id', 'drill_type', 'drill_date', 'start_time', 'end_time',
            'participants_count', 'evacuation_time_minutes', 'status',
            'remarks', 'coordinator', 'notes'
        ]));

        $drill->buildings()->attach($request->building_ids);

        return response()->json(['success' => true, 'message' => 'Drill scheduled successfully']);
    }

    public function getDrill($id)
    {
        $drill = FireSafetyEvacuationDrill::with('buildings')->findOrFail($id);
        return response()->json($drill);
    }

    public function cancelDrill($id)
    {
        $drill = FireSafetyEvacuationDrill::findOrFail($id);
        $drill->update(['status' => 'cancelled']);
        return response()->json(['success' => true, 'message' => 'Drill cancelled successfully']);
    }

    public function getEvacuationSidebarStats($schoolId)
    {
        $school = FireSafetySchool::withCount(['buildings', 'evacuationPlans'])->findOrFail($schoolId);
        $activePlans = FireSafetyEvacuationPlan::where('school_id', $schoolId)->where('status', 'active')->count();
        $draftPlans = FireSafetyEvacuationPlan::where('school_id', $schoolId)->where('status', 'draft')->count();

        return response()->json([
            'total_buildings' => $school->buildings_count,
            'total_plans' => $school->evacuation_plans_count,
            'active_plans' => $activePlans,
            'draft_plans' => $draftPlans,
            'no_plan' => $school->buildings_count - ($activePlans + $draftPlans),
        ]);
    }

    public function getPlanStats($schoolId)
    {
        $school = FireSafetySchool::withCount(['buildings', 'evacuationPlans'])->findOrFail($schoolId);

        $activePlans = FireSafetyEvacuationPlan::where('school_id', $schoolId)
            ->where('status', 'active')
            ->count();

        $draftPlans = FireSafetyEvacuationPlan::where('school_id', $schoolId)
            ->where('status', 'draft')
            ->count();

        $buildingsWithPlansIds = FireSafetyEvacuationPlan::where('school_id', $schoolId)->pluck('building_id');
        $buildingsWithoutPlans = FireSafetyBuilding::where('school_id', $schoolId)
            ->whereNotIn('id', $buildingsWithPlansIds)
            ->count();

        // Safety score average
        $buildings = FireSafetyBuilding::where('school_id', $schoolId)->with(['fireExtinguishers', 'alarmSystems'])->get();
        $totalScore = 0;
        foreach ($buildings as $building) {
            $alarmCount = $building->alarmSystems->whereIn('status', ['functional', 'online'])->count();
            $extinguisherCount = $building->fireExtinguishers->where('status', 'active')->count();
            $emergencyExits = $building->emergency_exits ?? 0;

            $score = 0;
            if($alarmCount > 0) $score += 30;
            if($extinguisherCount >= max(1, ceil(($building->rooms ?? 0) / 3))) $score += 40;
            if($emergencyExits >= min(2, ceil(($building->floors ?? 1) * 0.5))) $score += 30;
            $totalScore += $score;
        }
        $avgSafetyScore = $buildings->count() > 0 ? round($totalScore / $buildings->count()) : 0;

        return response()->json([
            'active_plans' => $activePlans,
            'draft_plans' => $draftPlans,
            'total_buildings' => $school->buildings_count,
            'no_plan' => $buildingsWithoutPlans,
            'avg_safety_score' => $avgSafetyScore
        ]);
    }

    public function getDrillHistory($schoolId)
    {
        $drills = FireSafetyEvacuationDrill::where('school_id', $schoolId)
            ->orderBy('drill_date', 'desc')
            ->limit(10)
            ->get();

        return response()->json($drills);
    }

    public function customization()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            // Load all data for admin
            $schools = FireSafetySchool::withCount(['buildings', 'rooms', 'alarmSystems', 'extinguishers as fire_extinguishers_count'])->get();
            $buildingTypes = SystemConfiguration::where('config_type', 'building_type')->orderBy('sort_order')->get();
            $alarmTypes = SystemConfiguration::where('config_type', 'alarm_type')->orderBy('id')->get();
            $alarmStatusesByType = SystemConfiguration::where('config_type', 'alarm_status')->whereNotNull('parent_id')->get()->groupBy('parent_id');
            $extinguisherTypes = SystemConfiguration::where('config_type', 'extinguisher_type')->get();
            $extinguisherStatuses = SystemConfiguration::where('config_type', 'extinguisher_status')->get();
            $safetyFeatures = SystemConfiguration::where('config_type', 'safety_feature')->orderBy('sort_order')->get();
            $calculatedPriorities = SystemConfiguration::where('config_type', 'calculated_priority')->orderBy('sort_order')->get();
            $roomTypes = SystemConfiguration::where('config_type', 'room_type')->orderBy('sort_order')->get();

            return view('fire-safety.customization', compact(
                'schools', 'buildingTypes', 'alarmTypes', 'alarmStatusesByType',
                'extinguisherTypes', 'extinguisherStatuses', 'safetyFeatures',
                'calculatedPriorities', 'roomTypes'
            ));
        } else {
            // For contributors, just load their school
            $school = FireSafetySchool::withCount(['buildings', 'rooms', 'alarmSystems', 'extinguishers as fire_extinguishers_count'])
                ->find($user->school_id);

            return view('fire-safety.customization', compact('school'));
        }
    }

    public function updateSchool(Request $request, $id)
    {
        $user = auth()->user();

        // Use route parameter (numeric id) so we never look up by school_id string (e.g. SCH-0451)
        $schoolId = (int) $id;
        if ($schoolId <= 0) {
            return response()->json(['success' => false, 'message' => 'Invalid school.'], 422);
        }

        if ($user->role !== 'admin') {
            if ((int) $user->school_id !== $schoolId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
        }

        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'address' => 'required|string',
            'school_head' => 'required|string|max:255',
            'school_drrm_coordinator' => 'required|string|max:255',
            'school_head_contact' => 'nullable|string|max:20',
            'drrm_coordinator_contact' => 'nullable|string|max:20',
            'email' => 'nullable|email',
        ]);

        $school = FireSafetySchool::findOrFail($schoolId);
        $school->update($validated);

        return response()->json(['success' => true, 'message' => 'School updated successfully']);
    }

    // API endpoints for AJAX calls
    public function getSchoolDetails($id)
    {
        $school = FireSafetySchool::withCount(['extinguishers', 'alarmSystems', 'buildings', 'evacuationPlans'])
            ->findOrFail($id);

        return response()->json([
            'id' => $school->id,
            'school_name' => $school->school_name,
            'school_id' => $school->school_id,
            'address' => $school->address,
            'school_head' => $school->school_head,
            'school_drrm_coordinator' => $school->school_drrm_coordinator,
            'status' => $school->status,
            'fire_extinguishers_count' => $school->extinguishers_count,
            'alarm_systems_count' => $school->alarm_systems_count,
            'evacuation_plans_count' => $school->evacuation_plans_count,
            'buildings_count' => $school->buildings_count,
        ]);
    }

    public function getSchoolIssues($id)
    {
        $school = FireSafetySchool::with(['extinguishers', 'alarmSystems'])->findOrFail($id);
        $issues = [];

        // Check if school is unconfigured
        if ($school->status === 'unconfigured') {
            if ($school->alarmSystems()->count() === 0){
                $issues[] = [
                    'type' => 'warning',
                    'title' => 'Setup Needed',
                    'description' => 'Alarm systems unconfigured yet',
                    'link' => route('fire-safety.alarm-systems')
                ];
            }
            if ($school->extinguishers()->count() === 0){
                $issues[] = [
                    'type' => 'warning',
                    'title' => 'Setup Needed',
                    'description' => 'No fire extinguishers',
                    'link' => route('fire-safety.extinguishers')
                ];
            }
            if ($school->evacuationPlans()->count() === 0){
                $issues[] = [
                    'type' => 'warning',
                    'title' => 'Setup Needed',
                    'description' => 'No Evactuation Plan',
                    'link' => route('fire-safety.evacuatio-plans')
                ];
            }
            if ($school->buildings()->count() === 0){
                $issues[] = [
                    'type' => 'warning',
                    'title' => 'Setup Needed',
                    'description' => 'Setup Buildings',
                    'link' => route('fire-safety.buildings')
                ];
            }
        }

        // Check extinguisher issues
        $expiredExtinguishers = $school->extinguishers()->where('status', 'expired')->get();
        foreach ($expiredExtinguishers as $extinguisher) {
            $issues[] = [
                'type' => 'danger',
                'title' => 'Expired Fire Extinguisher',
                'description' => "Code: {$extinguisher->code} - Last checked: {$extinguisher->date_checked}"
            ];
        }

        // Check alarm system issues
        $offlineAlarms = $school->alarmSystems()->where('status', 'offline')->get();
        foreach ($offlineAlarms as $alarm) {
            $issues[] = [
                'type' => 'warning',
                'title' => 'Alarm System Offline',
                'description' => "Code: {$alarm->code} - Last test: {$alarm->last_test}"
            ];
        }

        return response()->json([
            'school_name' => $school->school_name,
            'issues' => $issues
        ]);
    }

    public function storeSchool(Request $request)
    {
        $user = auth()->user();

        // Restriction: Contributor can only add one school
        if ($user->role === 'contributor') {
            $existingSchoolCount = FireSafetySchool::where('id', $user->school_id)->count();
            // If they already have a school assigned, or if they've already created one (in case school_id is not yet set)
            // Actually, if it's a contributor, they should only manage their assigned school.
            // If they are trying to create a NEW school, and they are a contributor, we should check if they already have one.
            if ($user->school_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are already assigned to a school and cannot create another one.'
                ], 403);
            }
        }

        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'school_id' => 'required|string|max:50|unique:firesafety_school_information,school_id',
            'address' => 'required|string|max:500',
            'school_head' => 'required|string|max:255',
            'school_drrm_coordinator' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        $school = FireSafetySchool::create([
            'school_name' => $validated['school_name'],
            'school_id' => $validated['school_id'],
            'address' => $validated['address'],
            'school_head' => $validated['school_head'],
            'school_drrm_coordinator' => $validated['school_drrm_coordinator'],
            'status' => $validated['status'] ?? 'unconfigured',
        ]);

        // If contributor creates the school, assign them to it automatically
        if ($user->role === 'contributor' && !$user->school_id) {
            $user->school_id = $school->id;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'School added successfully!',
            'school' => $school
        ]);
    }

    /**
     * Permanently delete a school (admin only) with password confirmation and archiving.
     */
    public function destroySchool(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'password' => 'required|string|max:255',
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password is incorrect.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $school = FireSafetySchool::withCount(['buildings', 'alarmSystems', 'extinguishers as fire_extinguishers_count', 'evacuationPlans'])
                ->findOrFail($id);

            // Capture FULL snapshot of school data before deletion
            $fullSchoolData = FireSafetySchool::with([
                'buildings.actualRooms',
                'buildings.fireExtinguishers',
                'buildings.alarmSystemsMany',
                'buildings.evacuationPlan',
                'alarmSystems',
                'extinguishers',
                'evacuationPlans',
                'rooms', // Direct rooms if any
                'inspections',
                'drills.buildings'
            ])->findOrFail($id);

            $reason = $request->input('reason') ?: ('School deleted by ' . $user->name);

            $archiveData = [
                'school_name' => $school->school_name,
                'school_code' => $school->school_id,
                'school_head' => $school->school_head,
                'drrm_coordinator' => $school->school_drrm_coordinator,
                'address' => $school->address,
                'buildings' => $school->buildings_count ?? 0,
                'alarm_systems' => $school->alarm_systems_count ?? 0,
                'extinguishers' => $school->fire_extinguishers_count ?? 0,
                'evacuation_plans' => $school->evacuation_plans_count ?? 0,
                'evacuation_coverage_status' => $school->coverageStatus,
            ];

            // Save to dedicated snapshots table for full data retention
            FireSafetySchoolSnapshot::create([
                'school_id_code' => $school->school_id,
                'school_name' => $school->school_name,
                'full_data' => $fullSchoolData->toArray(),
                'deleted_by' => $user->name,
                'reason' => $reason,
                'deleted_at' => now(),
            ]);

            // Also keep a record in general archives for the history log
            FireSafetyArchive::create([
                'school_id' => null, // School will be gone
                'type' => 'school_deletion',
                'item_id' => $school->id,
                'item_code' => $school->school_id,
                'item_data' => $archiveData,
                'reason' => $reason,
                'removed_at' => now(),
            ]);

            // Database level cascade ($table->onDelete('cascade')) is now active via migration,
            // but we still do manual cleanup to trigger any model-level events or handle pivots.
            $school->buildings()->each(function($b) {
                // Clear pivot relationships for drills and alarms
                $b->drills()->detach();
                $b->alarmSystemsMany()->detach();
                
                // Sub-items (handled by cascade too, but safe for events)
                $b->actualRooms()->delete();
                $b->fireExtinguishers()->delete();
                $b->alarmSystems()->delete();
                $b->evacuationPlan()->delete();
                
                $b->delete();
            });

            // Clean up remaining school relationships
            $school->alarmSystems()->delete();
            $school->extinguishers()->delete();
            $school->evacuationPlans()->delete();
            $school->rooms()->delete();
            $school->inspections()->delete();
            
            // Clean up drills (already detached from buildings above)
            $school->drills()->each(function($d) {
                $d->buildings()->detach();
                $d->delete();
            });
            
            $school->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'School deleted successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting school: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete school.'
            ], 500);
        }
    }

    /**
     * Get archived schools history (admin only).
     */
    public function getSchoolHistory()
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $archives = FireSafetyArchive::where('type', 'school')
            ->orderBy('removed_at', 'desc')
            ->get();

        return response()->json($archives);
    }

    public function storeAlert(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:firesafety_school_information,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string|in:danger,warning,info'
        ]);

        $school = FireSafetySchool::findOrFail($validated['school_id']);

        $alerts = $school->alerts ?? [];
        $alerts[] = [
            'id' => uniqid(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'created_at' => now()->toDateTimeString()
        ];

        $school->alerts = $alerts;
        $school->save();

        return response()->json(['success' => true, 'message' => 'Alert added successfully!', 'alerts' => $alerts]);
    }

    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:firesafety_school_information,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'time' => 'nullable'
        ]);

        $school = FireSafetySchool::findOrFail($validated['school_id']);

        $events = $school->events ?? [];
        $events[] = [
            'id' => uniqid(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'date' => $validated['date'],
            'time' => $validated['time'],
            'created_at' => now()->toDateTimeString()
        ];

        $school->events = $events;
        $school->save();

        return response()->json(['success' => true, 'message' => 'Event added successfully!', 'events' => $events]);
    }

    public function checkAlarmCode($schoolId, $code)
    {
        $exists = FireSafetyAlarmSystem::where('school_id', $schoolId)
            ->where('code', $code)
            ->exists();
        return response()->json(['exists' => $exists]);
    }

    // User Management Methods
    // Configuration Management Methods
    public function updateConfigOrder(Request $request, $type)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $order = $request->input('order');
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'No order provided'], 400);
        }

        foreach ($order as $index => $id) {
            SystemConfiguration::where('id', $id)
                ->where('config_type', str_replace('-', '_', $type))
                ->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    public function storeConfig(Request $request, $type)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:100',
            'color_class' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean'
        ];
        $configType = str_replace('-', '_', $type);
        if ($configType === 'alarm_type') {
            $rules['statuses'] = 'required|array';
            $rules['statuses.*'] = 'nullable|string|max:255';
        }
        if ($configType === 'building_type') {
            $rules['min_floors'] = 'nullable|integer|min:0';
            $rules['total_rooms'] = 'nullable|integer|min:0';
        }
        if ($configType === 'extinguisher_status') {
            $rules['pressure_min'] = 'required|numeric|min:0';
            $rules['pressure_max'] = 'required|numeric|min:0';
        }
        if ($configType === 'calculated_priority') {
            $rules['max_rooms_covered'] = 'required|integer|min:1|max:5';
        }
        if ($configType === 'room_type') {
            $rules['parent_id'] = 'required|exists:system_configurations,id';
        }
        $validated = $request->validate($rules);

        $data = [
            'config_type' => $configType,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'code' => $validated['code'] ?? null,
            'category' => $validated['category'] ?? null,
            'color_class' => $validated['color_class'] ?? null,
            'is_active' => $request->has('is_active') ? $request->is_active : true,
            'sort_order' => SystemConfiguration::where('config_type', $configType)->count()
        ];
        if ($configType === 'building_type') {
            $v = $request->input('min_floors');
            $data['min_floors'] = ($v !== '' && $v !== null) ? (int) $v : null;
            $v = $request->input('total_rooms');
            $data['total_rooms'] = ($v !== '' && $v !== null) ? (int) $v : null;
        }
        if ($configType === 'extinguisher_status') {
            $data['pressure_min'] = $request->input('pressure_min') !== null && $request->input('pressure_min') !== '' ? (float) $request->input('pressure_min') : null;
            $data['pressure_max'] = $request->input('pressure_max') !== null && $request->input('pressure_max') !== '' ? (float) $request->input('pressure_max') : null;
        }
        if ($configType === 'calculated_priority') {
            $data['max_rooms_covered'] = (int) $validated['max_rooms_covered'];
        }
        if ($configType === 'room_type') {
            $parent = SystemConfiguration::where('id', $validated['parent_id'])
                ->where('config_type', 'calculated_priority')
                ->firstOrFail();
            $data['parent_id'] = $parent->id;
        }
        $config = SystemConfiguration::create($data);

        if ($configType === 'alarm_type') {
            $statuses = array_values(array_filter(array_map('trim', $validated['statuses'] ?? [])));
            if (count($statuses) === 0) {
                return response()->json(['success' => false, 'message' => 'At least one alarm status is required.'], 422);
            }
            foreach ($statuses as $i => $statusName) {
                SystemConfiguration::create([
                    'config_type' => 'alarm_status',
                    'parent_id' => $config->id,
                    'name' => $statusName,
                    'sort_order' => $i,
                    'is_active' => true,
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Configuration saved successfully', 'config' => $config]);
    }

    public function updateConfig(Request $request, $type, $id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable'
        ];
        $configType = str_replace('-', '_', $type);
        if ($configType === 'building_type') {
            $rules['min_floors'] = 'nullable|integer|min:0';
            $rules['total_rooms'] = 'nullable|integer|min:0';
        }
        if ($configType === 'extinguisher_status') {
            $rules['pressure_min'] = 'required|numeric|min:0';
            $rules['pressure_max'] = 'required|numeric|min:0';
        }
        if ($configType === 'safety_feature') {
            $rules['category'] = 'nullable|string|max:100';
        }
        if ($configType === 'calculated_priority') {
            $rules['max_rooms_covered'] = 'required|integer|min:1|max:5';
        }
        if ($configType === 'room_type') {
            $rules['parent_id'] = 'required|exists:system_configurations,id';
        }
        $validated = $request->validate($rules);

        $config = SystemConfiguration::where('id', $id)
            ->where('config_type', $configType)
            ->firstOrFail();

        $update = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ];
        if (in_array($configType, ['building_type', 'alarm_type'], true)) {
            $update['is_active'] = $request->has('is_active') ? ($request->is_active == 'on' || $request->is_active == '1') : false;
        }
        if ($configType === 'building_type') {
            $v = $request->input('min_floors');
            $update['min_floors'] = ($v !== '' && $v !== null) ? (int) $v : null;
            $v = $request->input('total_rooms');
            $update['total_rooms'] = ($v !== '' && $v !== null) ? (int) $v : null;
        }
        if ($configType === 'extinguisher_status') {
            $update['pressure_min'] = $request->input('pressure_min') !== null && $request->input('pressure_min') !== '' ? (float) $request->input('pressure_min') : null;
            $update['pressure_max'] = $request->input('pressure_max') !== null && $request->input('pressure_max') !== '' ? (float) $request->input('pressure_max') : null;
        }
        if ($configType === 'safety_feature') {
            $update['category'] = $request->input('category') ?: null;
        }
        if ($configType === 'calculated_priority') {
            $update['max_rooms_covered'] = (int) $validated['max_rooms_covered'];
        }
        if ($configType === 'room_type') {
            $parent = SystemConfiguration::where('id', $validated['parent_id'])
                ->where('config_type', 'calculated_priority')
                ->firstOrFail();
            $update['parent_id'] = $parent->id;
        }
        $config->update($update);

        return response()->json(['success' => true, 'message' => 'Configuration updated successfully']);
    }

    public function deleteConfig($type, $id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        SystemConfiguration::where('id', $id)
            ->where('config_type', str_replace('-', '_', $type))
            ->delete();

        return response()->json(['success' => true, 'message' => 'Configuration deleted successfully']);
    }

    private function fireSafetyBackupTables(): array
    {
        return [
            // Core data
            'firesafety_school_information',
            'firesafety_buildings',
            'fire_safety_rooms',
            'firesafety_alarm_systems',
            'firesafety_fire_extinguishers',
            'firesafety_evacuationplans',
            // Related
            'fire_safety_inspections',
            'fire_safety_evacuation_drills',
            'fire_safety_drill_building',
            'fire_safety_alarm_building',
            'fire_safety_extinguisher_room_coverage',
            // Config + archives
            'system_configurations',
            'fire_safety_archives',
        ];
    }

    public function listFireSafetyBackups(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $dir = 'fire-safety-backups';
        if (!Storage::disk('local')->exists($dir)) {
            Storage::disk('local')->makeDirectory($dir);
        }

        $files = collect(Storage::disk('local')->files($dir))
            ->filter(fn($p) => str_ends_with($p, '.json'))
            ->map(function ($p) {
                return [
                    'name' => basename($p),
                    'path' => $p,
                    'size' => Storage::disk('local')->size($p),
                    'last_modified' => Storage::disk('local')->lastModified($p),
                ];
            })
            ->sortByDesc('last_modified')
            ->values();

        return response()->json(['success' => true, 'files' => $files]);
    }

    public function createFireSafetyBackup(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $tables = $this->fireSafetyBackupTables();
        $payload = [
            'module' => 'fire_safety',
            'generated_at' => now()->toIso8601String(),
            'generated_by' => $request->user()->email ?? null,
            'tables' => [],
        ];

        foreach ($tables as $t) {
            try {
                $rows = DB::table($t)->get()->map(fn($r) => (array) $r)->all();
                $payload['tables'][$t] = $rows;
            } catch (\Throwable $e) {
                // If a table doesn't exist yet (older schema), skip it.
                $payload['tables'][$t] = [];
            }
        }

        $dir = 'fire-safety-backups';
        if (!Storage::disk('local')->exists($dir)) {
            Storage::disk('local')->makeDirectory($dir);
        }
        $fileName = 'fire-safety-backup-' . now()->format('Ymd_His') . '.json';
        $path = $dir . '/' . $fileName;
        Storage::disk('local')->put($path, json_encode($payload, JSON_PRETTY_PRINT));

        return response()->json(['success' => true, 'file' => $fileName]);
    }

    public function restoreFireSafetyBackup(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'file' => 'required|string',
        ]);

        $fileName = basename($validated['file']);
        $path = 'fire-safety-backups/' . $fileName;
        if (!Storage::disk('local')->exists($path)) {
            return response()->json(['success' => false, 'message' => 'Backup file not found.'], 404);
        }

        $contents = Storage::disk('local')->get($path);
        $data = json_decode($contents, true);
        if (!is_array($data) || !isset($data['tables']) || !is_array($data['tables'])) {
            return response()->json(['success' => false, 'message' => 'Invalid backup file format.'], 422);
        }

        $tables = $this->fireSafetyBackupTables();
        DB::transaction(function () use ($data, $tables) {
            // MySQL: safe truncate/restore
            try { DB::statement('SET FOREIGN_KEY_CHECKS=0'); } catch (\Throwable $e) {}

            foreach ($tables as $t) {
                try {
                    // Use DELETE instead of TRUNCATE to avoid implicit commits that break active transactions
                    DB::table($t)->delete();
                } catch (\Throwable $e) {
                    // ignore missing tables
                }
            }

            foreach ($tables as $t) {
                $rows = $data['tables'][$t] ?? [];
                if (!is_array($rows) || count($rows) === 0) continue;
                // Insert in chunks
                foreach (array_chunk($rows, 500) as $chunk) {
                    DB::table($t)->insert($chunk);
                }
            }

            try { DB::statement('SET FOREIGN_KEY_CHECKS=1'); } catch (\Throwable $e) {}
        });

        return response()->json(['success' => true]);
    }

    public function exportSchools()
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $schools = FireSafetySchool::all();
        $filename = "schools_export_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');

        // Add CSV header
        fputcsv($handle, ['ID', 'School ID', 'Name', 'Address', 'Head', 'DRRM Coordinator', 'Status', 'Last Updated']);

        foreach ($schools as $school) {
            fputcsv($handle, [
                $school->id,
                $school->school_id,
                $school->school_name,
                $school->address,
                $school->school_head,
                $school->school_drrm_coordinator,
                $school->status,
                $school->updated_at
            ]);
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fclose($handle);
        exit;
    }
    public function getBuildingHistory($schoolId)
    {
        $archives = FireSafetyArchive::where('school_id', $schoolId)
            ->whereIn('type', ['floor', 'room'])
            ->orderBy('removed_at', 'desc')
            ->get();

        return response()->json($archives);
    }

    // MAP: Get Full Data for School Map
    public function getSchoolMapData($schoolId)
    {
        $school = FireSafetySchool::with([
            'buildings.actualRooms',
            'buildings.alarmSystems',
            'buildings.fireExtinguishers',
            'buildings.evacuationPlan'
        ])->findOrFail($schoolId);

        return response()->json($school);
    }

    // MAP: Save Layout Coordinates
    public function saveMapLayout(Request $request, $schoolId)
    {
        $request->validate([
            'layout' => 'required|array'
        ]);

        $school = FireSafetySchool::findOrFail($schoolId);
        $school->evacuation_map_layout = $request->layout;
        $school->save();

        return response()->json(['success' => true, 'message' => 'Map layout saved successfully!']);
    }

    // --- Report Printing Methods ---

    public function printSchoolSummary()
    {
        $schools = FireSafetySchool::with([
            'buildings.actualRooms',
            'buildings.fireExtinguishers',
            'buildings.alarmSystems'
        ])->get();

        return view('fire-safety.reports.school-summary', compact('schools'));
    }

    public function printBuildingSummary($schoolId)
    {
        $school = FireSafetySchool::with([
            'buildings.actualRooms',
            'buildings.fireExtinguishers',
            'buildings.alarmSystems'
        ])->findOrFail($schoolId);

        return view('fire-safety.reports.building-summary', compact('school'));
    }

    public function printAlarmDetails($schoolId)
    {
        $school = FireSafetySchool::findOrFail($schoolId);
        $alarms = FireSafetyAlarmSystem::where('school_id', $schoolId)
            ->with('building')
            ->get();

        return view('fire-safety.reports.alarm-details', compact('school', 'alarms'));
    }

    public function printExtinguisherDetails($schoolId)
    {
        $school = FireSafetySchool::findOrFail($schoolId);
        $extinguishers = FireSafetyExtinguisher::where('school_id', $schoolId)
            ->with(['building', 'centerRoom', 'coveredRooms'])
            ->get();

        return view('fire-safety.reports.extinguisher-details', compact('school', 'extinguishers'));
    }
}
