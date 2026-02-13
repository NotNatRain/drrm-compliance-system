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
use App\Models\PiePraScenario;
use App\Models\PiePraRecommendation;
use App\Models\PiePraVolunteer;
use App\Models\PiePraVolunteerSkill;
use App\Models\PiePraVolunteerAssignment;

class PiePraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $user = auth()->user();

        $scenarios = PiePraScenario::withCount('recommendations')
            ->latest()
            ->take(5)
            ->get();

        $volunteerCount = PiePraVolunteer::count();
        $activeAssignments = PiePraVolunteerAssignment::whereNull('check_out_at')->count();

        $schools = FireSafetySchool::with(['buildings', 'rooms'])->get();

        return view('pie-pra.dashboard', [
            'scenarios' => $scenarios,
            'volunteerCount' => $volunteerCount,
            'activeAssignments' => $activeAssignments,
            'schools' => $schools,
        ]);
    }

    public function runPredictor(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'hazard_type' => 'required|string|in:typhoon,flood,earthquake,other',
            'lead_time_hours' => 'required|integer|min:0|max:168',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $scenario = null;

        DB::transaction(function () use (&$scenario, $data) {
            $scenario = PiePraScenario::create([
                'name' => $data['name'],
                'hazard_type' => $data['hazard_type'],
                'lead_time_hours' => $data['lead_time_hours'],
                'status' => 'draft',
                'created_by' => auth()->id(),
            ]);

            $schools = FireSafetySchool::with('buildings')->get();
            foreach ($schools as $school) {
                // Simple heuristic scoring:
                $capacity = TypFldEvacuationCenter::where('school_id', $school->id)->value('capacity') ?? 0;
                $buildings = $school->buildings->count();

                $score = 0;
                $score += min($capacity / 100, 50); // capacity up to 50 pts
                $score += min($buildings * 5, 30);  // buildings up to 30 pts

                // Prefer schools already used as evacuation centers (families exist)
                $hasFamilies = TypFldFamily::whereHas('evacuationCenter', function ($q) use ($school) {
                    $q->where('school_id', $school->id);
                })->exists();
                if ($hasFamilies) {
                    $score += 10;
                }

                // Placeholder hazard adjustment
                if ($data['hazard_type'] === 'flood') {
                    $score -= 5;
                }

                $priority = max(0, min(100, (int) round($score)));

                PiePraRecommendation::create([
                    'scenario_id' => $scenario->id,
                    'school_id' => $school->id,
                    'activate_as_evac_center' => $priority >= 50,
                    'priority_score' => $priority,
                    'recommended_suspend_classes_at' => Carbon::now()->addHours($data['lead_time_hours'] - 6),
                    'recommended_start_evac_at' => Carbon::now()->addHours($data['lead_time_hours'] - 3),
                    'preposition_resources' => [
                        'food_packs' => $capacity > 0 ? ceil($capacity * 0.8) : 0,
                        'water_liters' => $capacity > 0 ? ceil($capacity * 3) : 0,
                        'sleeping_kits' => $capacity > 0 ? ceil($capacity * 0.7) : 0,
                    ],
                    'academic_continuity_notes' => 'Prepare modular learning packets and remote options for up to 3 days.',
                ]);
            }
        });

        return redirect()->route('pie-pra.scenario.show', $scenario->id)
            ->with('success', 'PIE-PRA scenario created with recommendations.');
    }

    public function showScenario($id)
    {
        $scenario = PiePraScenario::with(['recommendations.school'])->findOrFail($id);
        return view('pie-pra.scenario', compact('scenario'));
    }

    public function volunteers()
    {
        $skills = PiePraVolunteerSkill::orderBy('name')->get();
        $volunteers = PiePraVolunteer::with('skills')->latest()->paginate(20);

        return view('pie-pra.volunteers', compact('skills', 'volunteers'));
    }

    public function storeVolunteer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'skills' => 'array',
            'skills.*' => 'integer|exists:pie_pra_volunteer_skills,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first())->withInput();
        }

        $token = bin2hex(random_bytes(16));

        $volunteer = PiePraVolunteer::create([
            'name' => $request->name,
            'contact' => $request->contact,
            'barangay' => $request->barangay,
            'qr_token' => $token,
            'status' => 'available',
        ]);

        if ($request->filled('skills')) {
            $volunteer->skills()->sync($request->skills);
        }

        return redirect()->route('pie-pra.volunteers')->with('success', 'Volunteer registered successfully.');
    }

    public function matchVolunteers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'scenario_id' => 'required|integer|exists:pie_pra_scenarios,id',
            'school_id' => 'required|integer|exists:firesafety_school_information,id',
            'skill_id' => 'required|integer|exists:pie_pra_volunteer_skills,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $volunteers = PiePraVolunteer::whereHas('skills', function ($q) use ($request) {
                $q->where('pie_pra_volunteer_skills.id', $request->skill_id);
            })
            ->where('status', 'available')
            ->limit(10)
            ->get();

        return response()->json($volunteers);
    }

    public function qrCheckIn($token)
    {
        $volunteer = PiePraVolunteer::where('qr_token', $token)->firstOrFail();
        $volunteer->status = 'on-duty';
        $volunteer->save();

        return view('pie-pra.qr-check', [
            'volunteer' => $volunteer,
            'action' => 'check-in',
        ]);
    }

    public function qrCheckOut($token)
    {
        $volunteer = PiePraVolunteer::where('qr_token', $token)->firstOrFail();
        $volunteer->status = 'available';
        $volunteer->save();

        return view('pie-pra.qr-check', [
            'volunteer' => $volunteer,
            'action' => 'check-out',
        ]);
    }

    public function assignmentCertificate($id)
    {
        $assignment = PiePraVolunteerAssignment::with(['volunteer', 'school', 'scenario'])->findOrFail($id);
        return view('pie-pra.certificate', compact('assignment'));
    }
}

