<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ComprehensiveAssessment;
use App\Models\ComprehensiveAssessmentItem;
use App\Models\ComprehensiveFacility;
use App\Models\ComprehensiveStudent;
use App\Models\School;
use App\Models\SchoolSpecificsInformation;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Shuchkin\SimpleXLSX;

class ComprehensiveSchoolSafetyController extends Controller
{
    private const QUESTIONNAIRE_TEMPLATE_KEY = 'assessment_questionnaire_template';
    private const STUDENT_IMPORT_ATTACHMENTS_KEY = 'student_import_attachments';
    private const ASSESSMENT_SUMMARY_KEY_PREFIX = 'assessment_summary_';
    private const CATEGORY_DIVIDER = ':::';

    private function ensureAdmin(): void
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Only administrators can manage school directory records.');
        }
    }

    private function resolveContributorSchool(): ?School
    {
        $user = Auth::user();

        if (!$user || $user->role === 'admin') {
            return null;
        }

        if (!empty($user->school_id)) {
            return School::find($user->school_id);
        }

        return null;
    }

    private function findSchoolOrAbortForUser(int $schoolId): School
    {
        $school = School::findOrFail($schoolId);
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

    private function getDefaultAssessmentSections(): array
    {
        return [
            'enabling_environment' => [
                'title' => 'Enabling Environment',
                'division' => 'checklist_tools',
                'items' => [
                    ['text' => 'Adopted/Localized policies relating to DRRM/CCA/EIE on education/school safety', 'default_points' => 1],
                    ['text' => 'Formed School DRRM Team, with a focal person and consisting of personnel from different offices', 'default_points' => 1],
                    ['text' => 'Has a comprehensive School DRRM Plan, which includes CCA and EIE measures', 'default_points' => 1],
                    ['text' => 'School budget supports regular DRRM activities', 'default_points' => 1],
                    ['text' => 'Conducted student-led school watching and hazard mapping', 'default_points' => 1],
                    ['text' => 'Incorporated results of student-led school watching and hazard mapping in the SIP', 'default_points' => 1],
                    ['text' => 'Data collection and consolidation of programs and activities on DRRM', 'default_points' => 1],
                ],
            ],
            'safe_learning_facility' => [
                'title' => 'Safe Learning Facilities',
                'division' => 'checklist_tools',
                'items' => [
                    ['text' => 'School building/classroom components are according to DepEd/National Building Code', 'default_points' => 1],
                    ['text' => 'School conducted risk assessment of buildings', 'default_points' => 1],
                    ['text' => 'School has taken appropriate action with respect to unsafe school buildings', 'default_points' => 1],
                    ['text' => 'Undertaken regular inspection and repair of minor classroom damages', 'default_points' => 1],
                    ['text' => 'School heads and teachers have training on psychosocial support', 'default_points' => 1],
                    ['text' => 'Classrooms have usually 2 doors that swing out', 'default_points' => 1],
                    ['text' => 'Wide corridors for easy movement', 'default_points' => 1],
                ],
            ],
            'disaster_risk_management' => [
                'title' => 'Disaster Risk Management',
                'division' => 'checklist_tools',
                'items' => [
                    ['text' => 'School has a contingency plan for various hazards', 'default_points' => 1],
                    ['text' => '95% of students and personnel participated in drills', 'default_points' => 1],
                    ['text' => 'School has a functional early warning system', 'default_points' => 1],
                    ['text' => 'School has available emergency kits/equipment', 'default_points' => 1],
                    ['text' => 'School has a trained emergency response team', 'default_points' => 1],
                ],
            ],
            'pillar1_school_building_components' => [
                'title' => '1.0 School Building Components',
                'division' => 'pillar_1',
                'items' => [
                    ['text' => 'School building/classroom components is/are according to the DepED and/or National Building Code approved/standard design and specifications.', 'default_points' => 1],
                    ['text' => 'Academic or Instructional Rooms - a. Wall Finish', 'default_points' => 1],
                    ['text' => 'Academic or Instructional Rooms - b. Flooring', 'default_points' => 1],
                    ['text' => 'Academic or Instructional Rooms - c. Ceiling', 'default_points' => 1],
                    ['text' => 'Academic or Instructional Rooms - d. Window/Ventilations', 'default_points' => 1],
                    ['text' => 'Academic or Instructional Rooms - e. Roofing', 'default_points' => 1],
                    ['text' => 'Academic or Instructional Rooms - f. Corridor', 'default_points' => 1],
                    ['text' => 'Academic or Instructional Rooms - g. 2-Doors per classroom', 'default_points' => 1],
                    ['text' => 'Academic or Instructional Rooms - h. Railings/handrails/ramps', 'default_points' => 1],
                    ['text' => 'Academic or Instructional Rooms - i. Standard room dimensions', 'default_points' => 1],
                    ['text' => 'Academic or Instructional Rooms - j. Presence of emergency fire exits and signages', 'default_points' => 1],
                ],
            ],
            'pillar1_ancillary_facilities' => [
                'title' => '2.0 Ancillary Facilities',
                'division' => 'pillar_1',
                'items' => [
                    ['text' => 'Provisions/presence of School Health Clinic', 'default_points' => 1],
                    ['text' => 'Provisions/presence of Guidance and Counselling', 'default_points' => 1],
                    ['text' => 'Provisions/presence of School Canteen', 'default_points' => 1],
                    ['text' => 'Provisions/presence of Home Economics Building/Room', 'default_points' => 1],
                    ['text' => 'Provisions/presence of Science Laboratory Room/Bldg.', 'default_points' => 1],
                ],
            ],
        ];
    }

    private function normalizeSectionItems(array $items): array
    {
        $normalized = [];

        foreach ($items as $item) {
            if (is_array($item)) {
                $text = trim((string) ($item['text'] ?? ''));
                $defaultPoints = (float) ($item['default_points'] ?? 0);
            } else {
                $text = trim((string) $item);
                $defaultPoints = 0;
            }

            if ($text === '') {
                continue;
            }

            $normalized[] = [
                'text' => $text,
                'default_points' => $defaultPoints,
            ];
        }

        return $normalized;
    }

    private function normalizeAssessmentSections(array $sections): array
    {
        $normalized = [];

        foreach ($sections as $key => $section) {
            $sectionKey = is_string($key) && $key !== ''
                ? $key
                : ('section_' . (count($normalized) + 1));

            $title = trim((string) ($section['title'] ?? ''));
            $division = (string) ($section['division'] ?? 'checklist_tools');
            $items = $this->normalizeSectionItems((array) ($section['items'] ?? []));

            if ($title === '' || empty($items)) {
                continue;
            }

            $normalized[$sectionKey] = [
                'title' => $title,
                'division' => $division === 'pillar_1' ? 'pillar_1' : 'checklist_tools',
                'items' => $items,
            ];
        }

        return $normalized;
    }

    private function getAssessmentSections(?int $schoolId = null): array
    {
        if (!$schoolId) {
            return $this->normalizeAssessmentSections($this->getDefaultAssessmentSections());
        }

        $record = SchoolSpecificsInformation::query()
            ->where('school_id', $schoolId)
            ->where('module', 'comprehensive')
            ->where('key', self::QUESTIONNAIRE_TEMPLATE_KEY)
            ->first();

        $decoded = json_decode((string) ($record->value ?? ''), true);
        if (is_array($decoded) && !empty($decoded)) {
            return $this->normalizeAssessmentSections($decoded);
        }

        return $this->normalizeAssessmentSections($this->getDefaultAssessmentSections());
    }

    private function composeStoredCategory(string $division, string $title): string
    {
        $normalizedDivision = $division === 'pillar_1' ? 'pillar_1' : 'checklist_tools';
        return $normalizedDivision . self::CATEGORY_DIVIDER . $title;
    }

    private function parseStoredCategory(string $stored): array
    {
        if (str_contains($stored, self::CATEGORY_DIVIDER)) {
            [$division, $title] = explode(self::CATEGORY_DIVIDER, $stored, 2);
            return [
                'division' => $division === 'pillar_1' ? 'pillar_1' : 'checklist_tools',
                'title' => trim($title),
            ];
        }

        return [
            'division' => 'checklist_tools',
            'title' => trim($stored),
        ];
    }

    private function getAssessmentSummaryKey(int $assessmentId): string
    {
        return self::ASSESSMENT_SUMMARY_KEY_PREFIX . $assessmentId;
    }

    private function getAssessmentSummary(int $schoolId, int $assessmentId): string
    {
        $record = SchoolSpecificsInformation::query()
            ->where('school_id', $schoolId)
            ->where('module', 'comprehensive')
            ->where('key', $this->getAssessmentSummaryKey($assessmentId))
            ->first();

        return (string) ($record->value ?? '');
    }

    private function saveAssessmentSummary(int $schoolId, int $assessmentId, ?string $summary): void
    {
        SchoolSpecificsInformation::updateOrCreate(
            [
                'school_id' => $schoolId,
                'module' => 'comprehensive',
                'key' => $this->getAssessmentSummaryKey($assessmentId),
            ],
            [
                'value' => trim((string) ($summary ?? '')),
            ]
        );
    }

    private function getAssessmentCode(int $assessmentId): string
    {
        return 'ASMT-' . str_pad((string) $assessmentId, 3, '0', STR_PAD_LEFT);
    }

    private function collectAssessmentResponses(ComprehensiveAssessment $assessment): array
    {
        return $assessment->items
            ->mapWithKeys(function ($item) {
                $parsed = $this->parseStoredCategory((string) $item->category);
                $categoryKey = str($parsed['division'] . '_' . $parsed['title'])->lower()->replace([' ', '/', '-'], '_')->value();
                $criteriaKey = md5((string) $item->criteria);

                return [
                    $categoryKey . ':' . $criteriaKey => [
                        'is_compliant' => $item->is_compliant === null ? null : (bool) $item->is_compliant,
                        'points' => (float) $item->points,
                        'remarks' => $item->remarks,
                    ],
                ];
            })
            ->all();
    }

    private function getAssessmentSectionsForAssessment(ComprehensiveAssessment $assessment): array
    {
        $sections = [];

        foreach ($assessment->items->sortBy('id') as $item) {
            $parsed = $this->parseStoredCategory((string) $item->category);
            $baseKey = str($parsed['division'] . '_' . $parsed['title'])->lower()->replace([' ', '/', '-'], '_')->value();
            $sectionKey = $baseKey;
            $suffix = 1;

            while (isset($sections[$sectionKey]) && $sections[$sectionKey]['title'] !== $parsed['title']) {
                $suffix++;
                $sectionKey = $baseKey . '_' . $suffix;
            }

            if (!isset($sections[$sectionKey])) {
                $sections[$sectionKey] = [
                    'title' => $parsed['title'] !== '' ? $parsed['title'] : 'Section',
                    'division' => $parsed['division'],
                    'items' => [],
                ];
            }

            $sections[$sectionKey]['items'][] = [
                'text' => (string) $item->criteria,
                'default_points' => (float) $item->points,
            ];
        }

        return $sections;
    }

    public function dashboard(Request $request)
    {
        if (!Schema::hasTable('schools')) {
            return view('comprehensive-school-safety.module-dashboard', [
                'stats' => [
                    'directory_total' => 0,
                    'registered_comprehensive' => 0,
                    'pending_registration' => 0,
                ],
                'recentSchools' => collect(),
                'directorySchoolsForComprehensiveRegistration' => collect(),
                'setupNotice' => 'School tables are not yet migrated. Run php artisan migrate to complete setup.',
            ]);
        }

        $user = Auth::user();

        if ($user && $user->role === 'admin' && $request->filled('school_id')) {
            $switchSchoolId = (int) $request->input('school_id');
            $switchSchool = School::find($switchSchoolId);
            if ($switchSchool) {
                session(['csss_active_school_id' => $switchSchool->id]);
            }
        }

        if ($user && $user->role !== 'admin') {
            $assignedSchool = $this->resolveContributorSchool();

            if ($assignedSchool) {
                return redirect()->route('comprehensive-school-safety.school.dashboard', $assignedSchool->id);
            }

            return view('comprehensive-school-safety.module-dashboard', [
                'stats' => [
                    'directory_total' => 0,
                    'registered_comprehensive' => 0,
                    'pending_registration' => 0,
                ],
                'recentSchools' => collect(),
                'directorySchoolsForComprehensiveRegistration' => collect(),
                'setupNotice' => 'No school assignment found for your account. Please contact an administrator.',
            ]);
        }

        $directoryTotal = School::count();

        $registeredComprehensiveQuery = School::whereHas('specifics', function ($q) {
            $q->where('module', 'comprehensive')->where('key', 'original_cmpr_school_id');
        });

        $registeredComprehensiveCount = (clone $registeredComprehensiveQuery)->count();

        $directorySchoolsForComprehensiveRegistration = School::query()
            ->whereDoesntHave('specifics', function ($q) {
                $q->where('module', 'comprehensive')->where('key', 'original_cmpr_school_id');
            })
            ->orderBy('school_name')
            ->get([
                'id', 'school_name', 'school_id', 'school_id_number', 'address', 'school_head',
                'drrm_coordinator', 'contact_number', 'district', 'division', 'region',
            ]);

        $stats = [
            'directory_total' => $directoryTotal,
            'registered_comprehensive' => $registeredComprehensiveCount,
            'pending_registration' => $directorySchoolsForComprehensiveRegistration->count(),
        ];

        $recentSchools = (clone $registeredComprehensiveQuery)->latest()->take(8)->get();

        return view('comprehensive-school-safety.module-dashboard', compact(
            'stats',
            'recentSchools',
            'directorySchoolsForComprehensiveRegistration'
        ));
    }

    /**
     * Link an existing main-directory school to Comprehensive School Safety (no new school row).
     */
    public function registerSchoolFromDirectory(Request $request)
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'unified_school_id' => 'required|integer|exists:schools,id',
        ]);

        $school = School::findOrFail($validated['unified_school_id']);

        $exists = SchoolSpecificsInformation::where('school_id', $school->id)
            ->where('module', 'comprehensive')
            ->where('key', 'original_cmpr_school_id')
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'This school is already registered for Comprehensive School Safety.',
            ], 422);
        }

        SchoolSpecificsInformation::updateOrInsert(
            [
                'school_id' => $school->id,
                'module' => 'comprehensive',
                'key' => 'original_cmpr_school_id',
            ],
            [
                'value' => (string) $school->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        ActivityLog::log('comprehensive_safety', 'Registered school for Comprehensive School Safety (from directory): ' . $school->school_name, [
            'school_id' => $school->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'School registered for Comprehensive School Safety.',
            'school' => $school->fresh(),
        ]);
    }

    public function schools()
    {
        $schools = School::orderBy('school_name')->paginate(15);

        return view('comprehensive-school-safety.schools-index', compact('schools'));
    }

    public function schoolDashboard($schoolId)
    {
        $user = Auth::user();
        $school = $this->findSchoolOrAbortForUser((int) $schoolId);
        $allSchools = School::orderBy('school_name')->get();

        if ($user && $user->role === 'admin') {
            session(['csss_active_school_id' => (int) $school->id]);
        }

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

        $assessments->getCollection()->transform(function ($assessment) {
            $assessment->assessment_code = $this->getAssessmentCode((int) $assessment->id);
            return $assessment;
        });

        return view('comprehensive-school-safety.school-assessments', compact('school', 'assessments'));
    }

    public function newSafetyAssessmentForm($schoolId)
    {
        $school = $this->findSchoolOrAbortForUser((int) $schoolId);
        $assessmentSections = $this->getAssessmentSections((int) $school->id);
        $assessmentSummary = '';

        return view('comprehensive-school-safety.school-assessment-form', compact(
            'school',
            'assessmentSections',
            'assessmentSummary'
        ));
    }

    public function viewAssessment($schoolId, $assessmentId)
    {
        $school = $this->findSchoolOrAbortForUser((int) $schoolId);
        $assessment = ComprehensiveAssessment::with('items')
            ->where('school_id', $school->id)
            ->findOrFail((int) $assessmentId);

        $assessmentSections = $this->getAssessmentSectionsForAssessment($assessment);
        $assessmentResponses = $this->collectAssessmentResponses($assessment);
        $assessmentCode = $this->getAssessmentCode((int) $assessment->id);
        $assessmentSummary = $this->getAssessmentSummary((int) $school->id, (int) $assessment->id);
        $formMode = 'view';

        return view('comprehensive-school-safety.school-assessment-form', compact(
            'school',
            'assessment',
            'assessmentSections',
            'assessmentResponses',
            'assessmentCode',
            'assessmentSummary',
            'formMode'
        ));
    }

    public function editAssessmentForm($schoolId, $assessmentId)
    {
        $school = $this->findSchoolOrAbortForUser((int) $schoolId);
        $assessment = ComprehensiveAssessment::with('items')
            ->where('school_id', $school->id)
            ->findOrFail((int) $assessmentId);

        $assessmentSections = $this->getAssessmentSectionsForAssessment($assessment);
        $assessmentResponses = $this->collectAssessmentResponses($assessment);
        $assessmentCode = $this->getAssessmentCode((int) $assessment->id);
        $assessmentSummary = $this->getAssessmentSummary((int) $school->id, (int) $assessment->id);
        $formMode = 'edit';

        return view('comprehensive-school-safety.school-assessment-form', compact(
            'school',
            'assessment',
            'assessmentSections',
            'assessmentResponses',
            'assessmentCode',
            'assessmentSummary',
            'formMode'
        ));
    }

    public function storeAssessment(Request $request, $schoolId)
    {
        $school = $this->findSchoolOrAbortForUser((int) $schoolId);
        $assessmentSections = $this->getAssessmentSections((int) $school->id);

        $validated = $request->validate([
            'date_visited' => ['required', 'date'],
            'assessed_by' => ['required', 'string', 'max:255'],
            'summary_sheet' => ['nullable', 'string'],
        ]);

        $totalScore = 0;

        DB::transaction(function () use ($request, $school, $validated, $assessmentSections, &$totalScore) {
            $assessment = ComprehensiveAssessment::create([
                'school_id' => $school->id,
                'date_visited' => $validated['date_visited'],
                'assessed_by' => $validated['assessed_by'],
                'total_score' => 0,
                'status' => 'completed',
            ]);

            foreach ($assessmentSections as $categoryKey => $section) {
                foreach ($section['items'] as $index => $item) {
                    $criteria = (string) $item['text'];
                    $fieldName = $categoryKey . '_' . $index;
                    $isCompliant = $request->input($fieldName) === 'yes';
                    $points = (float) $request->input($categoryKey . '_points_' . $index, $item['default_points'] ?? 0);
                    if ($points < 0) {
                        $points = 0;
                    }
                    $totalScore += $points;

                    ComprehensiveAssessmentItem::create([
                        'assessment_id' => $assessment->id,
                        'category' => $this->composeStoredCategory((string) ($section['division'] ?? 'checklist_tools'), (string) $section['title']),
                        'criteria' => $criteria,
                        'is_compliant' => $isCompliant,
                        'points' => $points,
                        'remarks' => $request->input($categoryKey . '_remarks_' . $index),
                    ]);
                }
            }

            $assessment->update([
                'total_score' => $totalScore,
            ]);

            $this->saveAssessmentSummary((int) $school->id, (int) $assessment->id, $validated['summary_sheet'] ?? '');
        });

        return redirect()
            ->route('comprehensive-school-safety.school.assessments', $school->id)
            ->with('success', 'Assessment saved successfully.');
    }

    public function updateAssessment(Request $request, $schoolId, $assessmentId)
    {
        $school = $this->findSchoolOrAbortForUser((int) $schoolId);
        $assessment = ComprehensiveAssessment::where('school_id', $school->id)->findOrFail((int) $assessmentId);
        $assessmentSections = $this->getAssessmentSectionsForAssessment($assessment);

        $validated = $request->validate([
            'date_visited' => ['required', 'date'],
            'assessed_by' => ['required', 'string', 'max:255'],
            'summary_sheet' => ['nullable', 'string'],
        ]);

        $totalScore = 0;

        DB::transaction(function () use ($request, $school, $assessment, $validated, $assessmentSections, &$totalScore) {
            $assessment->items()->delete();

            foreach ($assessmentSections as $categoryKey => $section) {
                foreach ($section['items'] as $index => $item) {
                    $criteria = (string) $item['text'];
                    $fieldName = $categoryKey . '_' . $index;
                    $isCompliant = $request->input($fieldName) === 'yes';
                    $points = (float) $request->input($categoryKey . '_points_' . $index, $item['default_points'] ?? 0);
                    if ($points < 0) {
                        $points = 0;
                    }
                    $totalScore += $points;

                    ComprehensiveAssessmentItem::create([
                        'assessment_id' => $assessment->id,
                        'category' => $this->composeStoredCategory((string) ($section['division'] ?? 'checklist_tools'), (string) $section['title']),
                        'criteria' => $criteria,
                        'is_compliant' => $isCompliant,
                        'points' => $points,
                        'remarks' => $request->input($categoryKey . '_remarks_' . $index),
                    ]);
                }
            }

            $assessment->update([
                'date_visited' => $validated['date_visited'],
                'assessed_by' => $validated['assessed_by'],
                'total_score' => $totalScore,
            ]);

            $this->saveAssessmentSummary((int) $school->id, (int) $assessment->id, $validated['summary_sheet'] ?? '');
        });

        return redirect()
            ->route('comprehensive-school-safety.school.assessments', $school->id)
            ->with('success', 'Assessment updated successfully.');
    }

    public function editAssessmentQuestionnaire($schoolId)
    {
        $this->ensureAdmin();
        $school = $this->findSchoolOrAbortForUser((int) $schoolId);
        $assessmentSections = $this->getAssessmentSections((int) $school->id);

        return view('comprehensive-school-safety.assessment-questionnaire-editor', compact('school', 'assessmentSections'));
    }

    public function updateAssessmentQuestionnaire(Request $request, $schoolId)
    {
        $this->ensureAdmin();
        $school = $this->findSchoolOrAbortForUser((int) $schoolId);

        $sections = null;

        if ($request->filled('sections_json')) {
            $validated = $request->validate([
                'sections_json' => ['required', 'string'],
            ]);

            $decoded = json_decode($validated['sections_json'], true);
            if (is_array($decoded)) {
                $sections = $decoded;
            }
        }

        if ($sections === null) {
            $validated = $request->validate([
                'sections' => ['required', 'array', 'min:1'],
                'sections.*.title' => ['required', 'string'],
                'sections.*.division' => ['required', 'string'],
                'sections.*.items' => ['required', 'array', 'min:1'],
                'sections.*.items.*.text' => ['nullable', 'string'],
                'sections.*.items.*.default_points' => ['nullable', 'numeric', 'min:0'],
            ]);

            $sections = [];
            foreach ($validated['sections'] as $index => $section) {
                $sections[] = [
                    'key' => 'section_' . ($index + 1),
                    'title' => $section['title'] ?? '',
                    'division' => $section['division'] ?? 'checklist_tools',
                    'items' => $section['items'] ?? [],
                ];
            }
        }

        if (!is_array($sections) || empty($sections)) {
            return redirect()
                ->route('comprehensive-school-safety.school.assessments.questionnaire.edit', $school->id)
                ->with('error', 'Invalid questionnaire payload.');
        }

        $cleaned = [];
        foreach ($sections as $section) {
            $key = (string) ($section['key'] ?? '');
            $title = trim((string) ($section['title'] ?? ''));
            $division = (string) ($section['division'] ?? 'checklist_tools');
            $items = collect($section['items'] ?? [])
                ->map(function ($item) {
                    if (is_array($item)) {
                        $text = trim((string) ($item['text'] ?? ''));
                        $points = (float) ($item['default_points'] ?? 0);
                    } else {
                        $text = trim((string) $item);
                        $points = 0;
                    }

                    if ($text === '') {
                        return null;
                    }

                    return [
                        'text' => $text,
                        'default_points' => max(0, $points),
                    ];
                })
                ->filter()
                ->values()
                ->all();

            if ($key === '' || $title === '' || empty($items)) {
                continue;
            }

            $cleaned[$key] = [
                'title' => $title,
                'division' => $division === 'pillar_1' ? 'pillar_1' : 'checklist_tools',
                'items' => $items,
            ];
        }

        if (empty($cleaned)) {
            return redirect()
                ->route('comprehensive-school-safety.school.assessments.questionnaire.edit', $school->id)
                ->with('error', 'Questionnaire must contain at least one section with one question.');
        }

        SchoolSpecificsInformation::updateOrCreate(
            [
                'school_id' => $school->id,
                'module' => 'comprehensive',
                'key' => self::QUESTIONNAIRE_TEMPLATE_KEY,
            ],
            [
                'value' => json_encode($cleaned),
            ]
        );

        return redirect()
            ->route('comprehensive-school-safety.school.assessments', $school->id)
            ->with('success', 'Assessment questionnaire updated.');
    }

    public function schoolStudents($schoolId)
    {
        $school = $this->findSchoolOrAbortForUser((int) $schoolId);
        $students = $school->students()->latest()->paginate(15);
        $studentImportAttachments = SchoolSpecificsInformation::query()
            ->where('school_id', $school->id)
            ->where('module', 'comprehensive')
            ->where('key', self::STUDENT_IMPORT_ATTACHMENTS_KEY)
            ->first();

        $attachments = collect(json_decode($studentImportAttachments->value ?? '[]', true) ?: []);

        return view('comprehensive-school-safety.school-students', compact('school', 'students', 'attachments'));
    }

    public function storeStudent(Request $request, $schoolId)
    {
        $school = $this->findSchoolOrAbortForUser((int) $schoolId);

        $validated = $request->validate([
            'student_name' => ['required', 'string', 'max:255'],
            'grade_level' => ['nullable', 'string', 'max:255'],
            'section' => ['nullable', 'string', 'max:255'],
            'student_lrn' => ['nullable', 'string', 'max:255'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_contact' => ['nullable', 'string', 'max:255'],
        ]);

        ComprehensiveStudent::create([
            'school_id' => $school->id,
            'name' => $validated['student_name'],
            'grade_level' => $validated['grade_level'] ?? null,
            'section' => $validated['section'] ?? null,
            'student_lrn' => $validated['student_lrn'] ?? null,
            'guardian_name' => $validated['guardian_name'] ?? null,
            'guardian_contact' => $validated['guardian_contact'] ?? null,
        ]);

        return redirect()
            ->route('comprehensive-school-safety.school.students', $school->id)
            ->with('success', 'Student added successfully.');
    }

    private function normalizeHeader(string $value): string
    {
        $value = strtolower(trim($value));
        $value = preg_replace('/[^a-z0-9]+/', '_', $value) ?? '';
        return trim($value, '_');
    }

    private function resolveHeaderValue(array $row, array $candidates): ?string
    {
        foreach ($candidates as $candidate) {
            $key = $this->normalizeHeader($candidate);
            if (array_key_exists($key, $row)) {
                $val = trim((string) $row[$key]);
                return $val === '' ? null : $val;
            }
        }

        return null;
    }

    private function parseDelimitedStudentsFile(UploadedFile $file): array
    {
        $rows = [];
        $headers = [];

        $handle = fopen($file->getRealPath(), 'r');
        if ($handle === false) {
            return [];
        }

        while (($raw = fgetcsv($handle)) !== false) {
            if (empty($headers)) {
                $headers = array_map(fn ($h) => $this->normalizeHeader((string) $h), $raw);
                continue;
            }

            $assoc = [];
            foreach ($headers as $index => $header) {
                if ($header === '') {
                    continue;
                }
                $assoc[$header] = isset($raw[$index]) ? trim((string) $raw[$index]) : null;
            }

            if (collect($assoc)->filter(fn ($v) => !is_null($v) && $v !== '')->isEmpty()) {
                continue;
            }

            $rows[] = $assoc;
        }

        fclose($handle);

        return $rows;
    }

    private function parseXlsxStudentsFile(UploadedFile $file): array
    {
        $xlsx = SimpleXLSX::parse($file->getRealPath());
        if ($xlsx === false) {
            return [];
        }

        $allRows = $xlsx->rows();
        if (empty($allRows)) {
            return [];
        }

        $headers = array_map(fn ($h) => $this->normalizeHeader((string) $h), (array) ($allRows[0] ?? []));
        $rows = [];

        foreach (array_slice($allRows, 1) as $raw) {
            $assoc = [];
            foreach ($headers as $index => $header) {
                if ($header === '') {
                    continue;
                }
                $assoc[$header] = isset($raw[$index]) ? trim((string) $raw[$index]) : null;
            }

            if (collect($assoc)->filter(fn ($v) => !is_null($v) && $v !== '')->isEmpty()) {
                continue;
            }

            $rows[] = $assoc;
        }

        return $rows;
    }

    private function parseStudentsImportFile(UploadedFile $file): array
    {
        $extension = strtolower((string) $file->getClientOriginalExtension());
        if ($extension === 'xlsx') {
            return $this->parseXlsxStudentsFile($file);
        }

        return $this->parseDelimitedStudentsFile($file);
    }

    public function importStudents(Request $request, $schoolId)
    {
        $school = $this->findSchoolOrAbortForUser((int) $schoolId);

        $validated = $request->validate([
            'student_list_file' => ['required', 'file', 'mimes:csv,txt,xlsx'],
        ]);

        $rows = $this->parseStudentsImportFile($validated['student_list_file']);
        if (empty($rows)) {
            return redirect()
                ->route('comprehensive-school-safety.school.students', $school->id)
                ->with('error', 'No student rows were found in the imported file.');
        }

        $hasFirstName = Schema::hasColumn('cmpr_schl_sfty_students', 'first_name');
        $hasMiddleName = Schema::hasColumn('cmpr_schl_sfty_students', 'middle_name');
        $hasLastName = Schema::hasColumn('cmpr_schl_sfty_students', 'last_name');
        $hasName = Schema::hasColumn('cmpr_schl_sfty_students', 'name');

        $created = 0;
        $updated = 0;

        foreach ($rows as $row) {
            $studentLrn = $this->resolveHeaderValue($row, ['student_lrn', 'lrn', 'student lrn', 'learner reference number']);
            $firstName = $this->resolveHeaderValue($row, ['first_name', 'firstname', 'first name', 'given_name']);
            $middleName = $this->resolveHeaderValue($row, ['middle_name', 'middlename', 'middle name', 'mi']);
            $lastName = $this->resolveHeaderValue($row, ['last_name', 'lastname', 'last name', 'surname']);
            $fullName = $this->resolveHeaderValue($row, ['name', 'student_name', 'student name']);
            $gradeLevel = $this->resolveHeaderValue($row, ['grade_level', 'grade', 'grade level']);
            $section = $this->resolveHeaderValue($row, ['section', 'class_section', 'class section']);
            $guardianName = $this->resolveHeaderValue($row, ['guardian_name', 'guardian', 'guardian name', 'parent_name']);
            $guardianContact = $this->resolveHeaderValue($row, ['guardian_contact', 'guardian contact', 'contact_number', 'guardian phone']);

            if (!$fullName) {
                $fullName = trim(collect([$firstName, $middleName, $lastName])->filter()->implode(' '));
            }

            if (!$fullName) {
                $fullName = 'Unnamed Student';
            }

            $payload = [
                'school_id' => $school->id,
                'student_lrn' => $studentLrn,
                'grade_level' => $gradeLevel,
                'section' => $section,
                'guardian_name' => $guardianName,
                'guardian_contact' => $guardianContact,
            ];

            if ($hasFirstName) {
                $payload['first_name'] = $firstName;
            }
            if ($hasMiddleName) {
                $payload['middle_name'] = $middleName;
            }
            if ($hasLastName) {
                $payload['last_name'] = $lastName;
            }
            if ($hasName) {
                $payload['name'] = $fullName;
            }

            if ($studentLrn) {
                $student = ComprehensiveStudent::where('school_id', $school->id)
                    ->where('student_lrn', $studentLrn)
                    ->first();

                if ($student) {
                    $student->update($payload);
                    $updated++;
                } else {
                    ComprehensiveStudent::create($payload);
                    $created++;
                }
            } else {
                ComprehensiveStudent::create($payload);
                $created++;
            }
        }

        // Keep import-file history, while manual attachment uploads are removed.
        $historyRecord = SchoolSpecificsInformation::firstOrCreate(
            [
                'school_id' => $school->id,
                'module' => 'comprehensive',
                'key' => self::STUDENT_IMPORT_ATTACHMENTS_KEY,
            ],
            [
                'value' => '[]',
            ]
        );

        $history = collect(json_decode($historyRecord->value ?? '[]', true) ?: []);
        $history->prepend([
            'name' => $validated['student_list_file']->getClientOriginalName(),
            'url' => null,
            'uploaded_at' => now()->toDateTimeString(),
        ]);

        $historyRecord->value = $history->take(30)->values()->toJson();
        $historyRecord->save();

        return redirect()
            ->route('comprehensive-school-safety.school.students', $school->id)
            ->with('success', "Student list imported. {$created} added, {$updated} updated.");
    }

    public function updateStudent(Request $request, $schoolId, $studentId)
    {
        $school = $this->findSchoolOrAbortForUser((int) $schoolId);
        $student = ComprehensiveStudent::where('school_id', $school->id)->findOrFail((int) $studentId);

        $validated = $request->validate([
            'student_lrn' => ['nullable', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'grade_level' => ['nullable', 'string', 'max:255'],
            'section' => ['nullable', 'string', 'max:255'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_contact' => ['nullable', 'string', 'max:255'],
        ]);

        $payload = [];
        $columns = [
            'student_lrn',
            'first_name',
            'middle_name',
            'last_name',
            'grade_level',
            'section',
            'guardian_name',
            'guardian_contact',
        ];

        foreach ($columns as $column) {
            if (Schema::hasColumn('cmpr_schl_sfty_students', $column)) {
                $payload[$column] = $validated[$column] ?? null;
            }
        }

        if (Schema::hasColumn('cmpr_schl_sfty_students', 'name')) {
            $payload['name'] = trim(collect([
                $validated['first_name'] ?? null,
                $validated['middle_name'] ?? null,
                $validated['last_name'] ?? null,
            ])->filter()->implode(' '));
        }

        $student->update($payload);

        return redirect()
            ->route('comprehensive-school-safety.school.students', $school->id)
            ->with('success', 'Student record updated.');
    }

    public function exportStudents($schoolId)
    {
        $school = $this->findSchoolOrAbortForUser((int) $schoolId);
        $studentsQuery = $school->students();

        if (Schema::hasColumn('cmpr_schl_sfty_students', 'last_name')) {
            $studentsQuery->orderBy('last_name');
        }
        if (Schema::hasColumn('cmpr_schl_sfty_students', 'first_name')) {
            $studentsQuery->orderBy('first_name');
        }
        if (Schema::hasColumn('cmpr_schl_sfty_students', 'name')) {
            $studentsQuery->orderBy('name');
        }

        $students = $studentsQuery->get();

        $filename = 'students_' . preg_replace('/[^a-z0-9]+/i', '_', strtolower($school->name ?? 'school')) . '_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($students) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Student LRN', 'First Name', 'Middle Name', 'Last Name', 'Grade Level', 'Section', 'Guardian Name', 'Guardian Contact']);

            foreach ($students as $student) {
                fputcsv($out, [
                    $student->student_lrn,
                    $student->first_name,
                    $student->middle_name,
                    $student->last_name,
                    $student->grade_level,
                    $student->section,
                    $student->guardian_name,
                    $student->guardian_contact,
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function schoolFacilities($schoolId)
    {
        $school = $this->findSchoolOrAbortForUser((int) $schoolId);
        $fireSafetyBuildings = $school->buildings()->latest()->get();
        $fireSafetyPlan = $school->schoolEvacuationPlan;

        $riskRegister = $fireSafetyBuildings->map(function ($building) use ($fireSafetyPlan) {
            $riskLevel = $building->safetyStatus;

            return [
                'title' => $building->building_name ?? ('Building ' . $building->building_no),
                'score' => $building->safety_score,
                'status' => $building->safetyStatusLabel,
                'color' => $building->safetyStatusColor,
                'summary' => trim(($building->compliance_reason ?? 'No compliance notes recorded.') . ' ' . ($building->evacuationPlan ? '' : 'No building evacuation plan linked.')),
                'exits' => (int) ($building->emergency_exits ?? 0),
                'extinguishers' => $building->active_extinguishers_count,
                'alarms' => $building->functional_alarms_count,
                'needs_attention' => $riskLevel !== 'good' || !$building->evacuationPlan,
                'action' => $building->evacuationPlan ? 'Review and validate existing fire safety records.' : 'Create or attach a building evacuation plan.',
            ];
        })->sortByDesc('needs_attention')->values();

        $referenceFacilities = collect([
            [
                'label' => 'Evacuation Center',
                'value' => $school->evacuation_location ?: 'No evacuation center linked yet',
                'meta' => $school->evacuation_capacity ? 'Capacity: ' . $school->evacuation_capacity : 'Read-only reference from school profile',
            ],
            [
                'label' => 'Primary Assembly Area',
                'value' => $fireSafetyPlan?->primary_assembly_area ?? 'No assembly area linked yet',
                'meta' => $fireSafetyPlan ? 'From Fire Safety evacuation plan' : 'No fire safety map/plan attached yet',
            ],
            [
                'label' => 'Secondary Assembly Area',
                'value' => $fireSafetyPlan?->secondary_assembly_area ?? 'No secondary assembly area linked yet',
                'meta' => $fireSafetyPlan ? 'From Fire Safety evacuation plan' : 'No fire safety map/plan attached yet',
            ],
        ]);

        $assessmentSummary = [
            'building_count' => $fireSafetyBuildings->count(),
            'average_score' => $fireSafetyBuildings->count() > 0 ? round($fireSafetyBuildings->avg('safety_score'), 1) : 0,
            'good_count' => $fireSafetyBuildings->where('safetyStatus', 'good')->count(),
            'fair_count' => $fireSafetyBuildings->where('safetyStatus', 'fair')->count(),
            'poor_count' => $fireSafetyBuildings->where('safetyStatus', 'poor')->count(),
        ];

        $actionItems = $riskRegister
            ->filter(fn ($item) => $item['needs_attention'])
            ->map(function ($item) {
                return [
                    'title' => $item['title'],
                    'action' => $item['action'],
                    'status' => 'Open',
                    'color' => 'danger',
                ];
            })
            ->values();

        return view('comprehensive-school-safety.school-facilities', compact(
            'school',
            'fireSafetyBuildings',
            'fireSafetyPlan',
            'assessmentSummary',
            'riskRegister',
            'referenceFacilities',
            'actionItems'
        ));
    }

    public function storeFacility(Request $request, $schoolId)
    {
        $school = $this->findSchoolOrAbortForUser((int) $schoolId);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'condition' => ['required', 'string', 'in:good,fair,needs_repair,condemned'],
            'description' => ['nullable', 'string'],
        ]);

        ComprehensiveFacility::create([
            'school_id' => $school->id,
            'name' => $validated['name'],
            'type' => $validated['type'],
            'condition' => $validated['condition'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('comprehensive-school-safety.school.facilities', $school->id)
            ->with('success', 'Facility added successfully.');
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
