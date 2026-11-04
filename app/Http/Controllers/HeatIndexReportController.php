<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HeatIndexReport;
use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HeatIndexReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display Heat Index Dashboard or Page
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';

        $assignedSchool = null;
        if (!$isAdmin) {
            $assignedSchoolId = $user->school_id ?: ($user->typhoon_school_id ?: $user->incident_school_id);
            if ($assignedSchoolId) {
                $assignedSchool = School::find((int) $assignedSchoolId);
            }
        }

        $schools = $isAdmin ? School::orderBy('school_name')->get() : ($assignedSchool ? collect([$assignedSchool]) : collect());

        $submissionsQuery = HeatIndexReport::with('school', 'user')->latest();
        if (!$isAdmin && $assignedSchool) {
            $submissionsQuery->where('school_id', $assignedSchool->id);
        }
        $submissions = $submissionsQuery->get();

        return view('heat-index-reports.heat-index_page', compact('schools', 'user', 'isAdmin', 'assignedSchool', 'submissions'));
    }

    /**
     * Fetch submissions list via JSON
     */
    public function getSubmissions(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';

        $assignedSchoolId = null;
        if (!$isAdmin) {
            $assignedSchoolId = $user->school_id ?: ($user->typhoon_school_id ?: $user->incident_school_id);
        }

        $query = HeatIndexReport::with('school', 'user')->latest();

        if (!$isAdmin) {
            if (!$assignedSchoolId) {
                return response()->json(['success' => true, 'data' => []]);
            }
            $query->where('school_id', $assignedSchoolId);
        } elseif ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $submissions = $query->get();

        return response()->json([
            'success' => true,
            'data' => $submissions,
            'is_admin' => $isAdmin
        ]);
    }

    /**
     * Store a new heat index submission
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';

        // Resolve assigned school for non-admin
        $assignedSchool = null;
        if (!$isAdmin) {
            $assignedSchoolId = $user->school_id ?: ($user->typhoon_school_id ?: $user->incident_school_id);
            if (!$assignedSchoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have an assigned school. Only assigned school heads or coordinators can submit reports.'
                ], 422);
            }
            $assignedSchool = School::findOrFail((int) $assignedSchoolId);
            $schoolId = $assignedSchool->id;
            $schoolName = $assignedSchool->school_name;
            $schoolHead = $assignedSchool->school_head ?: $user->name;
        } else {
            $rules = [
                'school_id' => 'required|exists:schools,id',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $school = School::findOrFail((int) $request->school_id);
            $schoolId = $school->id;
            $schoolName = $school->school_name;
            $schoolHead = $request->input('school_head', $school->school_head ?: 'School Head');
        }

        $validator = Validator::make($request->all(), [
            'report_date' => 'required|date',
            'report_time' => 'required|string',
            'temperature' => 'required|numeric|min:15|max:60',
            'humidity' => 'required|numeric|min:5|max:100',
            'action_taken' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $temp = (float) $request->temperature;
        $hum = (int) $request->humidity;
        $heatIndex = (float) $request->input('heat_index', $this->calculateHeatIndex($temp, $hum));

        // Evaluate Category and badge color
        if ($heatIndex >= 52) {
            $category = 'Extreme Danger';
            $badgeColor = '#7F1D1D';
        } elseif ($heatIndex >= 42) {
            $category = 'Danger';
            $badgeColor = '#EF4444';
        } elseif ($heatIndex >= 33) {
            $category = 'Extreme Caution';
            $badgeColor = '#F97316';
        } else {
            $category = 'Caution';
            $badgeColor = '#EAB308';
        }

        $report = HeatIndexReport::create([
            'school_id' => $schoolId,
            'school_name' => $schoolName,
            'school_head' => $schoolHead,
            'submitted_by' => $user->name,
            'user_id' => $user->id,
            'report_date' => $request->report_date,
            'report_time' => $request->report_time,
            'temperature' => $temp,
            'humidity' => $hum,
            'heat_index' => $heatIndex,
            'category' => $category,
            'badge_color' => $badgeColor,
            'action_taken' => $request->action_taken ?: 'Normal Classes with Hydration',
            'remarks' => $request->remarks,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Heat Index Report for {$schoolName} successfully submitted!",
            'data' => $report,
        ]);
    }

    /**
     * Delete a submission (Admin only)
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $report = HeatIndexReport::findOrFail($id);
        $report->delete();

        return response()->json([
            'success' => true,
            'message' => 'Heat Index Report submission removed successfully.'
        ]);
    }

    /**
     * Rothfusz Heat Index Formula in Celsius
     */
    private function calculateHeatIndex(float $t, int $r): float
    {
        if ($t < 26.7) {
            return round($t, 1);
        }

        $c1 = -8.78469475556;
        $c2 = 1.61139411;
        $c3 = 2.33854883889;
        $c4 = -0.14611605;
        $c5 = -0.012308094;
        $c6 = -0.0164248277778;
        $c7 = 0.002211732;
        $c8 = 0.00072546;
        $c9 = -0.000003582;

        $hi = $c1 + ($c2 * $t) + ($c3 * $r) + ($c4 * $t * $r) + ($c5 * $t * $t)
            + ($c6 * $r * $r) + ($c7 * $t * $t * $r) + ($c8 * $t * $r * $r) + ($c9 * $t * $t * $r * $r);

        return round($hi, 1);
    }
}
