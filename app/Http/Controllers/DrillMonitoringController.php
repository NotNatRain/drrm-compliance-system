<?php

namespace App\Http\Controllers;

use App\Models\DrillMonitoring;
use App\Models\School;
use App\Models\FireSafetyEvacuationDrill;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
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
     */
    public function dashboard(Request $request)
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
            ->get();

        $stats = [
            'total_monitored' => DrillMonitoring::where('unified_school_id', $activeSchool->id)->count(),
            'avg_participants' => DrillMonitoring::where('unified_school_id', $activeSchool->id)
                ->selectRaw('AVG(no_of_students + no_of_personnel) as avg')
                ->value('avg') ?? 0
        ];

        return view('DrillMonitoring.dashboard', compact('schools', 'activeSchool', 'monitorings', 'stats', 'upcomingDrills'));
    }

    /**
     * Store a new monitoring record.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unified_school_id' => 'required|exists:schools,id',
            'drill_id' => 'nullable|exists:fire_safety_evacuation_drills,id',
            'drill_type' => 'required|string',
            'monitoring_date' => 'required|date',
            'monitoring_time' => 'required',
            'no_of_students' => 'required|integer|min:0',
            'no_of_personnel' => 'required|integer|min:0',
            'monitored_by' => 'required|string|max:255',
            'status' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $monitoring = DrillMonitoring::create($request->all());

        ActivityLog::log('drill_monitoring', 'Recorded drill monitoring: ' . $monitoring->drill_type, [
            'school_id' => $monitoring->unified_school_id,
            'notes' => $monitoring->remarks
        ]);

        return response()->json(['success' => true, 'message' => 'Monitoring record saved successfully.', 'data' => $monitoring]);
    }

    /**
     * Show specific monitoring record details.
     */
    public function show($id)
    {
        $monitoring = DrillMonitoring::with(['school', 'scheduledDrill'])->findOrFail($id);
        return response()->json($monitoring);
    }

    /**
     * Remove a monitoring record.
     */
    public function destroy($id)
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
}