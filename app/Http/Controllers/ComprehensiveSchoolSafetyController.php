<?php

namespace App\Http\Controllers;

use App\Models\ComprehensiveSchool;
use App\Models\FireSafetySchool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ComprehensiveSchoolSafetyController extends Controller
{
    private function ensureAdmin(): void
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Only administrators can manage school directory records.');
        }
    }

    private function resolveContributorSchool(): ?ComprehensiveSchool
    {
        $user = Auth::user();

        if (!$user || $user->role === 'admin') {
            return null;
        }

        $fireSafetySchool = !empty($user->school_id)
            ? FireSafetySchool::find($user->school_id)
            : null;

        if ($fireSafetySchool) {
            $school = ComprehensiveSchool::where('school_id_number', $fireSafetySchool->school_id)->first();
            if ($school) {
                return $school;
            }

            $schoolByName = ComprehensiveSchool::where('name', $fireSafetySchool->school_name)->first();
            if ($schoolByName) {
                return $schoolByName;
            }
        }

        if (!empty($user->school_id)) {
            return ComprehensiveSchool::find($user->school_id);
        }

        return null;
    }

    private function findSchoolOrAbortForUser(int $schoolId): ComprehensiveSchool
    {
        $school = ComprehensiveSchool::findOrFail($schoolId);
        $user = Auth::user();

        if ($user && $user->role !== 'admin') {
            $allowedSchool = $this->resolveContributorSchool();
            if (!$allowedSchool || (int) $allowedSchool->id !== (int) $school->id) {
                abort(403, 'You are not allowed to access this school.');
            }
        } elseif ($user && $user->role === 'admin') {
            session(['csss_active_school_id' => (int) $school->id]);
        }

        return $school;
    }

    public function dashboard()
    {
        if (!Schema::hasTable('cmpr_schl_sfty_schools')) {
            return view('comprehensive-school-safety.module-dashboard', [
                'stats' => [
                    'total_schools' => 0,
                    'registered_from_fire_safety' => 0,
                    'manually_created' => 0,
                ],
                'recentSchools' => collect(),
                'fireSafetySchools' => collect(),
                'registeredSchoolIds' => collect(),
                'setupNotice' => 'Comprehensive School Safety tables are not yet migrated. Run php artisan migrate to complete setup.',
            ]);
        }

        $user = Auth::user();

        if ($user && $user->role !== 'admin') {
            $assignedSchool = $this->resolveContributorSchool();

            if ($assignedSchool) {
                return redirect()->route('comprehensive-school-safety.school.dashboard', $assignedSchool->id);
            }

            return view('comprehensive-school-safety.module-dashboard', [
                'stats' => [
                    'total_schools' => 0,
                    'registered_from_fire_safety' => 0,
                    'manually_created' => 0,
                ],
                'recentSchools' => collect(),
                'fireSafetySchools' => collect(),
                'registeredSchoolIds' => collect(),
                'setupNotice' => 'No school assignment found for your account. Please contact an administrator.',
            ]);
        }

        $stats = [
            'total_schools' => ComprehensiveSchool::count(),
            'registered_from_fire_safety' => ComprehensiveSchool::whereNotNull('school_id_number')->count(),
            'manually_created' => ComprehensiveSchool::whereNull('school_id_number')->count(),
        ];

        $recentSchools = ComprehensiveSchool::latest()->take(8)->get();
        $registeredSchoolIds = ComprehensiveSchool::whereNotNull('school_id_number')
            ->pluck('school_id_number')
            ->filter()
            ->map(fn ($id) => (string) $id)
            ->values();
        $fireSafetySchools = FireSafetySchool::query()
            ->orderBy('school_name')
            ->get();

        return view('comprehensive-school-safety.module-dashboard', compact('stats', 'recentSchools', 'fireSafetySchools', 'registeredSchoolIds'));
    }

    public function schools()
    {
        $schools = ComprehensiveSchool::orderBy('name')->paginate(15);

        return view('comprehensive-school-safety.schools-index', compact('schools'));
    }

    public function storeSchool(Request $request)
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'school_id_number' => 'required|string|max:255|unique:cmpr_schl_sfty_schools,school_id_number',
            'address' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'school_head' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
        ]);

        ComprehensiveSchool::create($validated);

        return redirect()
            ->route('comprehensive-school-safety.dashboard')
            ->with('success', 'New Comprehensive School Safety record created successfully.');
    }

    public function registerExistingForm()
    {
        $this->ensureAdmin();

        $registeredSchoolIds = ComprehensiveSchool::whereNotNull('school_id_number')
            ->pluck('school_id_number')
            ->filter()
            ->values();

        $fireSafetySchools = FireSafetySchool::query()
            ->whereNotIn('school_id', $registeredSchoolIds)
            ->orderBy('school_name')
            ->get();

        return view('comprehensive-school-safety.register-existing-school', compact('fireSafetySchools'));
    }

    public function registerExistingStore(Request $request)
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'fire_safety_school_id' => 'required|integer|exists:firesafety_school_information,id',
            'district' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'school_head' => 'nullable|string|max:255',
        ]);

        $fireSafetySchool = FireSafetySchool::findOrFail($validated['fire_safety_school_id']);

        ComprehensiveSchool::updateOrCreate(
            ['school_id_number' => $fireSafetySchool->school_id],
            [
                'name' => $fireSafetySchool->school_name,
                'address' => $fireSafetySchool->address,
                'district' => $validated['district'],
                'division' => $validated['division'],
                'region' => $validated['region'],
                'school_head' => $validated['school_head'] ?: $fireSafetySchool->school_head,
                'contact_number' => $validated['contact_number'],
            ]
        );

        return redirect()
            ->route('comprehensive-school-safety.dashboard')
            ->with('success', 'Existing Fire Safety school was registered to Comprehensive School Safety successfully.');
    }

    public function schoolDashboard($schoolId)
    {
        $user = Auth::user();
        if ($user && $user->role === 'admin') {
            $school = ComprehensiveSchool::findOrFail((int) $schoolId);
            session(['csss_active_school_id' => (int) $school->id]);

            return redirect()->route('comprehensive-school-safety.dashboard');
        }

        $school = $this->findSchoolOrAbortForUser((int) $schoolId);
        $allSchools = ComprehensiveSchool::orderBy('name')->get();

        $stats = [
            'total_students' => $school->students()->count(),
            'total_facilities' => $school->facilities()->count(),
            'avg_safety_score' => round($school->assessments()->avg('total_score') ?? 0, 2),
            'pending_assessments' => $school->assessments()->where('status', '!=', 'completed')->count(),
        ];

        $recentAssessments = $school->assessments()->latest()->take(5)->get();

        return view('comprehensive-school-safety.school-dashboard', compact('school', 'stats', 'recentAssessments', 'allSchools'));
    }

    public function schoolAssessments($schoolId)
    {
        $school = $this->findSchoolOrAbortForUser((int) $schoolId);
        $assessments = $school->assessments()->latest()->paginate(15);
        return view('comprehensive-school-safety.school-assessments', compact('school', 'assessments'));
    }

    public function newSafetyAssessmentForm($schoolId)
    {
        $school = $this->findSchoolOrAbortForUser((int) $schoolId);

        $enablingEnvironmentItems = [
            'Adopted/Localized policies relating to DRRM/CCA/EIE on education/school safety',
            'Formed School DRRM Team, with a focal person and consisting of personnel from different offices',
            'Has a comprehensive School DRRM Plan, which includes CCA and EIE measures',
            'School budget supports regular DRRM activities',
            'Conducted student-led school watching and hazard mapping',
            'Incorporated results of student-led school watching and hazard mapping in the SIP',
            'Data collection and consolidation of programs and activities on DRRM',
        ];

        $safeLearningFacilityItems = [
            'School building/classroom components are according to DepEd/National Building Code',
            'School conducted risk assessment of buildings',
            'School has taken appropriate action with respect to unsafe school buildings',
            'Undertaken regular inspection and repair of minor classroom damages',
            'School heads and teachers have training on psychosocial support',
            'Classrooms have usually 2 doors that swing out',
            'Wide corridors for easy movement',
        ];

        $drmItems = [
            'School has a contingency plan for various hazards',
            '95% of students and personnel participated in drills',
            'School has a functional early warning system',
            'School has available emergency kits/equipment',
            'School has a trained emergency response team',
        ];

        return view('comprehensive-school-safety.school-assessment-form', compact(
            'school',
            'enablingEnvironmentItems',
            'safeLearningFacilityItems',
            'drmItems'
        ));
    }

    public function schoolStudents($schoolId)
    {
        $school = $this->findSchoolOrAbortForUser((int) $schoolId);
        $students = $school->students()->latest()->paginate(15);
        return view('comprehensive-school-safety.school-students', compact('school', 'students'));
    }

    public function schoolFacilities($schoolId)
    {
        $school = $this->findSchoolOrAbortForUser((int) $schoolId);
        $facilities = $school->facilities()->latest()->paginate(15);
        $user = Auth::user();
        $allSchools = $user && $user->role === 'admin'
            ? ComprehensiveSchool::orderBy('name')->get(['id', 'name'])
            : collect([$school])->map(fn ($s) => (object) ['id' => $s->id, 'name' => $s->name]);

        return view('comprehensive-school-safety.school-facilities', compact('school', 'facilities', 'allSchools'));
    }

    public function schoolReports($schoolId)
    {
        $school = $this->findSchoolOrAbortForUser((int) $schoolId);

        $reportStats = [
            'assessments_completed' => $school->assessments()->where('status', 'completed')->count(),
            'total_assessments' => $school->assessments()->count(),
            'total_students' => $school->students()->count(),
            'total_facilities' => $school->facilities()->count(),
        ];

        return view('comprehensive-school-safety.school-reports', compact('school', 'reportStats'));
    }
}


