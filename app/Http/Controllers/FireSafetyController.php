<?php

namespace App\Http\Controllers;

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
use Carbon\Carbon;
use App\Models\User;
use App\Models\SystemConfiguration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
                    $expiredExtinguishers = $school->extinguishers()
                        ->where('status', 'expired')
                        ->count();

                    $offlineAlarms = $school->alarmSystems()
                        ->where('status', 'offline')
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

        return view('fire-safety.dashboard', ['schools' => $schools]);
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

        return view('fire-safety.alarm-systems', [
            'schools' => $schools
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
            'status' => 'required|string',
            'last_test' => 'nullable|date',
            'next_test_due' => 'required|date',
            'notes' => 'nullable|string',
            'building_ids' => 'nullable|array',
            'building_ids.*' => 'exists:firesafety_buildings,id'
        ]);

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

    // Delete alarm
    public function destroyAlarm($id)
    {
        $alarm = FireSafetyAlarmSystem::findOrFail($id);
        $alarm->delete();

        return response()->json([
            'success' => true,
            'message' => 'Alarm system removed successfully!'
        ]);
    }

    public function extinguishers()
    {
        $query = FireSafetySchool::with([
            'buildings',
            'buildings.rooms',
            'buildings.fireExtinguishers.centerRoom',
            'buildings.fireExtinguishers.coveredRooms',
        ]);

        if (auth()->user()->role !== 'admin') {
            $query->where('id', auth()->user()->school_id);
        }

        $schools = $query->get();

        return view('fire-safety.extinguishers', [
            'schools' => $schools
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

    // Store a room (AJAX)
    public function storeRoom(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:firesafety_school_information,id',
            'building_id' => 'required|exists:firesafety_buildings,id',
            'room_code' => 'nullable|string|max:50',
            'room_name' => 'required|string|max:120',
            'room_type' => 'required|in:classroom,laboratory,clinic,department,library,storage,others',
            'floor_no' => 'nullable|integer|min:1|max:50',
        ]);

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

        $room = FireSafetyRoom::create($validated);

        // Update building's total rooms count to stay in sync
        $building->increment('rooms');

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
            'status' => 'required|in:active,expired,maintenance,missing',
            'pressure_level' => 'required|integer|min:0|max:100',
            'date_checked' => 'required|date',
            'evaluation_result' => 'required|string|max:255',
            'room_id' => 'required|exists:fire_safety_rooms,id', // center room
            'covered_room_ids' => 'required|array|min:1|max:3',
            'covered_room_ids.*' => 'integer|exists:fire_safety_rooms,id',
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
            $ext = FireSafetyExtinguisher::create([
                'school_id' => $validated['school_id'],
                'building_id' => $validated['building_id'],
                'room_id' => $validated['room_id'],
                'code' => $validated['code'],
                'type' => $validated['type'],
                'status' => $validated['status'],
                'pressure_level' => $validated['pressure_level'],
                'date_checked' => $validated['date_checked'],
                'evaluation_result' => $validated['evaluation_result'],
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
            'status' => 'required|in:active,expired,maintenance,missing',
            'pressure_level' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $ext = FireSafetyExtinguisher::findOrFail($id);
            $ext->status = $request->status;
            $ext->pressure_level = $request->pressure_level;
            $ext->date_checked = now(); // Update checked date
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
                    'pressure_level' => $insp->pressure_level
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

        return view('fire-safety.buildings',[
            'schools' => $schools
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
            'features' => 'nullable|array'
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

        // Gymnasium & Cafeteria restriction: only 1 room
        if (in_array(strtolower($validated['building_type']), ['gymnasium', 'cafeteria'])) {
            $validated['rooms'] = 1;
        }

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
            'year_constructed' => 'nullable|integer|min:1900|max:' . date('Y'),
            'last_renovation' => 'nullable|integer|min:1900|max:' . date('Y'),
            'emergency_exits' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'removed_floor' => 'nullable|integer',
            'removed_room_id' => 'nullable|exists:fire_safety_rooms,id'
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

        // 1. Floor Removal
        if ($request->filled('removed_floor')) {
            $floorNo = $request->removed_floor;
            // Remove Alarms on this floor
            $locationPrefix = "Floor " . $floorNo . " -";
            $alarmsToRemove = FireSafetyAlarmSystem::where('building_id', $id)
                ->where('location', 'like', $locationPrefix . '%')
                ->get();
            foreach ($alarmsToRemove as $alarm) {
                $alarm->buildings()->detach();
                $alarm->delete();
            }

            // Get rooms on this floor to remove extinguishers
            $roomsOnFloor = FireSafetyRoom::where('building_id', $id)->where('floor_no', $floorNo)->get();
            foreach ($roomsOnFloor as $room) {
                $this->processRoomRemoval($room);
            }

            $building->decrement('floors');
            $cascadingMessages[] = "Floor {$floorNo} and its associated safety systems were removed.";
        }

        // 2. Room Removal
        if ($request->filled('removed_room_id')) {
            $room = FireSafetyRoom::findOrFail($request->removed_room_id);
            $res = $this->processRoomRemoval($room);
            $building->decrement('rooms');
            if ($res) $cascadingMessages[] = $res;
        }

        $building->update(collect($validated)->except(['removed_floor', 'removed_room_id', 'building_type'])->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Building updated successfully!' . (count($cascadingMessages) > 0 ? ' ' . implode(' ', $cascadingMessages) : '')
        ]);
    }

    private function processRoomRemoval($room)
    {
        // Find extinguishers where this room is the center
        $extsAsCenter = FireSafetyExtinguisher::where('room_id', $room->id)->get();
        foreach ($extsAsCenter as $ext) {
            // Find other rooms covered by this extinguisher on same floor
            $otherRooms = $ext->coveredRooms()->where('fire_safety_rooms.id', '!=', $room->id)->get();
            if ($otherRooms->count() > 0) {
                $newCenter = $otherRooms->random();
                $ext->update(['room_id' => $newCenter->id]);
            } else {
                $ext->delete(); // No rooms left to cover
            }
        }

        // Detach from all extinguisher coverages
        $room->extinguishersCoveringThisRoom()->detach();
        $roomName = $room->room_name;
        $room->delete();

        return "Room {$roomName} was removed and safety equipment was re-assigned/removed.";
    }

    // Get building details
    public function getBuilding($id)
    {
        Log::info('getBuilding called with id: ' . $id);
        try {
            $building = FireSafetyBuilding::with(['school'])->findOrFail($id);
            Log::info('Building found: ' . $building->id);

            // Check if user has access to this building's school
            if (auth()->user()->role !== 'admin' && $building->school_id !== auth()->user()->school_id) {
                Log::warning('Access denied for user ' . auth()->id() . ' to building ' . $id);
                return response()->json(['error' => 'Access denied'], 403);
            }

            Log::info('Returning building data');
            return response()->json($building);
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
            ->select('id', 'building_no', 'building_name')
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
            return response()->json($inspection);
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
                    'rooms'
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
            'exits' => 'required|integer|min:1',
            'routes' => 'required|integer|min:1|max:5',
            'areas' => 'required|integer|min:1|max:3',
            'primary_route' => 'required|string',
            'primary_assembly_area' => 'required|string',
            'assembly_capacity' => 'required|integer|min:1',
            'status' => 'required|in:active,draft,review,inactive',
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
            'assembly_capacity' => $request->assembly_capacity,
            'emergency_contacts' => $request->emergency_contacts,
            'special_instructions' => $request->special_instructions,
            'status' => $request->status,
            'approved_at' => $request->status === 'active' ? now() : null,
            'map_data' => $request->map_data,
        ]);

        return response()->json(['success' => true, 'message' => 'Evacuation plan created successfully']);
    }

    public function getEvacuationPlan($id)
    {
        $plan = FireSafetyEvacuationPlan::findOrFail($id);
        return response()->json($plan);
    }

    public function getEvacuationPlanDetails($id)
    {
        $plan = FireSafetyEvacuationPlan::with(['building', 'school'])->findOrFail($id);

        // Load all buildings for the school with their safety components
        $schoolBuildings = FireSafetyBuilding::where('school_id', $plan->school_id)
            ->with(['rooms', 'fireExtinguishers', 'alarmSystemsMany'])
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
        return response()->json([
            'building_name' => $building->building_name,
            'building_no' => $building->building_no,
            'rooms' => $building->rooms_count,
            'exits' => $building->emergency_exits,
            'alarms' => $building->functional_alarms_count,
            'extinguishers' => $building->active_extinguishers_count,
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
            'building_ids' => 'required|array',
            'coordinator' => 'required|string',
        ]);

        $drill = FireSafetyEvacuationDrill::create([
            'school_id' => $request->school_id,
            'drill_type' => $request->drill_type,
            'drill_date' => $request->drill_date,
            'status' => 'scheduled',
            'coordinator' => $request->coordinator,
            'notes' => $request->notes,
        ]);

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

        return response()->json([
            'total_buildings' => $school->buildings_count,
            'total_plans' => $school->evacuation_plans_count,
            'active_plans' => $activePlans,
            'buildings_without_plans' => $school->buildings_count - $school->evacuation_plans_count,
        ]);
    }
    public function getPlanStats($schoolId)
    {
        $school = FireSafetySchool::withCount(['buildings'])->findOrFail($schoolId);

        $activePlans = FireSafetyEvacuationPlan::where('school_id', $schoolId)
            ->where('status', 'active')
            ->count();

        $draftPlans = FireSafetyEvacuationPlan::where('school_id', $schoolId)
            ->where('status', 'draft')
            ->count();

        return response()->json([
            'active_plans' => $activePlans,
            'draft_plans' => $draftPlans,
            'total_buildings' => $school->buildings_count,
            'no_plan' => $school->buildings_count - ($activePlans + $draftPlans),
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
            $alarmTypes = SystemConfiguration::where('config_type', 'alarm_type')->get();
            $alarmStatuses = SystemConfiguration::where('config_type', 'alarm_status')->get();
            $extinguisherTypes = SystemConfiguration::where('config_type', 'extinguisher_type')->get();
            $extinguisherStatuses = SystemConfiguration::where('config_type', 'extinguisher_status')->get();
            $safetyFeatures = SystemConfiguration::where('config_type', 'safety_feature')->orderBy('sort_order')->get();

            return view('fire-safety.customization', compact(
                'schools', 'buildingTypes', 'alarmTypes', 'alarmStatuses',
                'extinguisherTypes', 'extinguisherStatuses', 'safetyFeatures'
            ));
        } else {
            // For contributors, just load their school
            $school = FireSafetySchool::withCount(['buildings', 'rooms', 'alarmSystems', 'extinguishers as fire_extinguishers_count'])
                ->find($user->school_id);

            return view('fire-safety.customization', compact('school'));
        }
    }

    public function updateSchool(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            // Admin can update any school
            $schoolId = $request->school_id;
        } else {
            // Contributors can only update their own school
            $schoolId = $user->school_id;
            if ($request->school_id != $schoolId) {
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
            'name' => $school->school_name,
            'school_id' => $school->school_id,
            'school_head' => $school->school_head,
            'drrm_coordinator' => $school->school_drrm_coordinator,
            'fire_extinguishers_count' => $school->extinguishers_count,
            'alarm_systems_count' => $school->alarm_systems_count,
            'evacuation_plans_count' => $school->evacuation_plans_count,
            'buildings_count' => $school->buildings_count
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
            'name' => 'required|string|max:255',
            'school_id' => 'required|string|max:50|unique:firesafety_school_information,school_id',
            'address' => 'required|string|max:500',
            'school_head' => 'required|string|max:255',
            'drrm_coordinator' => 'required|string|max:255'
        ]);

        $school = FireSafetySchool::create([
            'school_name' => $validated['name'],
            'school_id' => $validated['school_id'],
            'address' => $validated['address'],
            'school_head' => $validated['school_head'],
            'school_drrm_coordinator' => $validated['drrm_coordinator'],
            'status' => 'unconfigured'
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

    public function checkAlarmCode($code)
    {
        $exists = FireSafetyAlarmSystem::where('code', $code)->exists();
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

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:100',
            'color_class' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean'
        ]);

        $config = SystemConfiguration::create([
            'config_type' => str_replace('-', '_', $type),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'code' => $validated['code'] ?? null,
            'category' => $validated['category'] ?? null,
            'color_class' => $validated['color_class'] ?? null,
            'is_active' => $request->has('is_active') ? $request->is_active : true,
            'sort_order' => SystemConfiguration::where('config_type', str_replace('-', '_', $type))->count()
        ]);

        return response()->json(['success' => true, 'message' => 'Configuration saved successfully', 'config' => $config]);
    }

    public function updateConfig(Request $request, $type, $id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable'
        ]);

        $config = SystemConfiguration::where('id', $id)
            ->where('config_type', str_replace('-', '_', $type))
            ->firstOrFail();

        $config->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active') ? ($request->is_active == 'on' || $request->is_active == '1') : false
        ]);

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
}
