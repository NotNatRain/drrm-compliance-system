<?php

namespace App\Http\Controllers;

use App\Models\DrillMonitoring;
use App\Models\School;
use App\Models\FireSafetyEvacuationDrill;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\Inspection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DrillMonitoringController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the drill monitoring dashboard.
     * Display a listing of inspections for a school.
     */
    public function dashboard(Request $request)
    public function index($schoolId)
    {
        $user = Auth::user();
        $schoolId = $request->input('school_id');

        if ($user->role === 'admin') {
            $schools = School::orderBy('school_name')->get();
            $activeSchool = $schoolId ? School::find($schoolId) : $schools->first();
        } else {
            $activeSchool = School::find($user->school_id);
            $schools = collect([$activeSchool]);
        }

        if (!$activeSchool) {
            return redirect()->route('dashboard')->with('error', 'No school context available.');
        }

        $monitorings = DrillMonitoring::where('unified_school_id', $activeSchool->id)
            ->with('scheduledDrill')
            ->orderBy('monitoring_date', 'desc')
            ->paginate(15);

        $upcomingDrills = FireSafetyEvacuationDrill::where('unified_school_id', $activeSchool->id)
            ->where('drill_date', '>=', Carbon::today())
            ->where('status', 'scheduled')
        $inspections = Inspection::where('unified_school_id', $schoolId)
            ->orderBy('inspection_date', 'desc')
            ->get();

        $stats = [
            'total_monitored' => DrillMonitoring::where('unified_school_id', $activeSchool->id)->count(),
            'avg_participants' => DrillMonitoring::where('unified_school_id', $activeSchool->id)
                ->selectRaw('AVG(no_of_students + no_of_personnel) as avg')
                ->value('avg') ?? 0
        ];

        return view('DrillMonitoring.dashboard', compact('schools', 'activeSchool', 'monitorings', 'stats', 'upcomingDrills'));
        return response()->json($inspections);
    }

    /**
     * Store a new monitoring record.
     * Store a newly created inspection record.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unified_school_id' => 'required|exists:schools,id',
            'drill_id' => 'nullable|exists:fire_safety_evacuation_drills,id',
        $request->validate([
            'unified_school_id' => 'required',
            'drill_type' => 'required|string',
            'monitoring_date' => 'required|date',
            'monitoring_time' => 'required',
            'no_of_students' => 'required|integer|min:0',
            'no_of_personnel' => 'required|integer|min:0',
            'inspection_date' => 'required|date',
            'inspection_time' => 'required',
            'monitored_by' => 'required|string|max:255',
            'status' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }
        $inspection = Inspection::create($request->all());

        $monitoring = DrillMonitoring::create($request->all());

        ActivityLog::log('drill_monitoring', 'Recorded drill monitoring: ' . $monitoring->drill_type, [
            'school_id' => $monitoring->unified_school_id,
            'notes' => $monitoring->remarks
        return response()->json([
            'success' => true,
            'message' => 'Inspection record saved successfully.',
            'inspection' => $inspection
        ]);

        return response()->json(['success' => true, 'message' => 'Monitoring record saved successfully.', 'data' => $monitoring]);
    }

    /**
     * Show specific monitoring record details.
     * Display the specified inspection record.
     */
    public function show($id)
    {
        $monitoring = DrillMonitoring::with(['school', 'scheduledDrill'])->findOrFail($id);
        return response()->json($monitoring);
        $inspection = Inspection::with('school')->findOrFail($id);
        return response()->json($inspection);
    }

    /**
     * Remove a monitoring record.
     * Update the specified inspection record.
     */
    public function destroy($id)
    public function update(Request $request, $id)
    {
        $monitoring = DrillMonitoring::findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'admin' && (int)$user->school_id !== (int)$monitoring->unified_school_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        ActivityLog::log('drill_monitoring', 'Deleted drill monitoring: ' . $monitoring->drill_type, [
            'school_id' => $monitoring->unified_school_id
        ]);

        $monitoring->delete();
        return response()->json(['success' => true, 'message' => 'Monitoring record deleted.']);
    }


    // Store inspection (Inspect Now)
    public function storeInspection(Request $request)
    {
        if (auth()->user()->role === 'viewer') {
            return response()->json(['success' => false, 'message' => 'Viewers cannot save inspections.'], 403);
        }

        if (auth()->user()->role !== 'admin' && (int)$request->unified_school_id !== (int)auth()->user()->school_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access to this school.'], 403);
        }

        $validated = $request->validate ([
            'unified_school_id' => 'required|exists:schools,id',
        ]);
        $inspection = Inspection::findOrFail($id);
        
        $request->validate([
            'drill_type' => 'required|string',
            'inspection_date' => 'required|date',
            'inspection_time' => 'required',
            'time_started' => 'nullable',
            'time_finished' => 'nullable',
            'elapsed_time' => 'nullable',
            'no_of_exits' => 'nullable|integer',
            'no_of_buildings' => 'nullable|integer',
            'no_of_students' => 'nullable|integer',
            'no_of_personnel' => 'nullable|integer',
            'monitored_by' => 'required|string',
            'monitored_by_position' => 'nullable|string',
            'checklist_data' => 'nullable|array',
            'observers_data' => 'nullable|array',
            'remarks' => 'nullable|string',
            'coordinator_name' => 'nullable|string',
            'school_head_name' => 'nullable|string',
        ]);

        $inspection = FireSafetyInspection::create($validated);
        $inspection->update($request->all());

        // Create notification for inspection
        $school = School::find($validated['unified_school_id']);
        self::createFireSafetyNotification(
            'inspection',
            'Inspection Completed: ' . $validated['drill_type'],
            $validated['drill_type'] . ' inspection at ' . ($school->school_name ?? 'Unknown School') . ' on ' . $validated['inspection_date'] . '. Monitored by: ' . $validated['monitored_by'],
            $validated['unified_school_id'],
            'see_inspection',
            ['inspection_id' => $inspection->id, 'unified_school_id' => $validated['unified_school_id']]
        );

        ActivityLog::log('fire_safety', 'Created inspection: ' . $validated['drill_type'], [
            'unified_school_id' => $validated['unified_school_id'],
            'notes' => $validated['remarks'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Inspection saved successfully!',
            'inspection' => $inspection
            'message' => 'Inspection record updated successfully.'
        ]);
    }

    // Update inspection
    public function updateInspection(Request $request, $id)
    /**
     * Remove the specified inspection record.
     */
    public function destroy($id)
    {
        if (auth()->user()->role === 'viewer') {
            return response()->json(['success' => false, 'message' => 'Viewers cannot update inspections.'], 403);
        }
        $inspection = Inspection::findOrFail($id);
        $inspection->delete();

        $inspection = FireSafetyInspection::findOrFail($id);

        if (auth()->user()->role !== 'admin' && (int)$inspection->unified_school_id !== (int)auth()->user()->school_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access to this inspection.'], 403);
        }

        $validated = $request->validate([
            'unified_school_id' => 'required|exists:schools,id',
            'drill_type' => 'required|string',
            'inspection_date' => 'required|date',
            'inspection_time' => 'required',
            'time_started' => 'nullable',
            'time_finished' => 'nullable',
            'elapsed_time' => 'nullable|string',
            'no_of_exits' => 'nullable|integer',
            'no_of_buildings' => 'nullable|integer',
            'no_of_students' => 'nullable|integer',
            'no_of_personnel' => 'nullable|integer',
            'monitored_by' => 'required|string',
            'monitored_by_position' => 'nullable|string',
            'checklist_data' => 'nullable|array',
            'observers_data' => 'nullable|array',
            'remarks' => 'nullable|string',
            'coordinator_name' => 'nullable|string',
            'school_head_name' => 'nullable|string',
        ]);

        $inspection->update($validated);

        ActivityLog::log('fire_safety', 'Updated inspection: ' . $validated['drill_type'], [
            'unified_school_id' => $validated['unified_school_id'],
            'notes' => $validated['remarks'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Inspection updated successfully!',
            'inspection' => $inspection
            'message' => 'Inspection has been removed.'
        ]);
    }

    // Placeholder checklist page to prevent 404 (until checklist UI is implemented)
    public function inspectionChecklist($id)
    {
        $inspection = FireSafetyInspection::with(['building', 'school'])->findOrFail($id);
        return view('fire-safety.inspection-checklist', [
            'inspection' => $inspection
        ]);
    }
}