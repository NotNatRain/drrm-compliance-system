<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncidentCalendar;
use App\Models\IncidentType;
use App\Models\IncidentStatus;
use App\Models\IncidentSchool;
use App\Models\IncidentChecklist;
use App\Models\FireSafetySchool;
use App\Models\TypFldEvacuationCenter;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\PhpWord;

class IncidentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the incidents dashboard.
     */
    public function dashboard(Request $request)
    {
        $year = (int) $request->input('year', date('Y'));
        $month = (int) $request->input('month', date('n'));

        // Role-based redirection
        $user = auth()->user();
        if (in_array($user->role, ['contributor', 'viewer'], true)) {
            $user->load('incidentSchool');
            $assignedIncidentSchoolName = $user->incidentSchool?->name;

            $weekOffset = (int) $request->input('week_offset', 0);
            $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->addWeeks($weekOffset)->startOfDay();
            $weekEnd = (clone $weekStart)->endOfWeek(Carbon::SUNDAY)->endOfDay();

            $myReports = IncidentCalendar::where('contributor_id', $user->id)
                ->with(['incidentType', 'incidentStatus'])
                ->whereBetween('incident_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
                ->orderBy('incident_date', 'desc')
                ->get();
            
            // Re-using same logic for dropdowns
            $incidentTypes = IncidentType::orderBy('priority')->get();
            $incidentStatuses = IncidentStatus::orderBy('name')->get();
            $fireSafetySchools = !$assignedIncidentSchoolName
                ? $this->getUnifiedIncidentSchoolOptions()
                : collect();

            return view('incidents.reporting_dashboard', compact(
                'myReports',
                'incidentTypes',
                'incidentStatuses',
                'fireSafetySchools',
                'assignedIncidentSchoolName',
                'weekOffset',
                'weekStart',
                'weekEnd'
            ));
        }

        // Admin/Viewer Logic
        // Get calendar data
        $calendarData = $this->getCalendarData($year, $month);

        // Get incidents for the month
        $incidents = IncidentCalendar::forMonth($year, $month)
            ->with(['incidentType', 'incidentStatus'])
            ->orderBy('incident_date', 'desc')
            ->get();

        // Get statistics (including chart-ready data)
        $stats = $this->getMonthlyStats($year, $month);

        // Quick Compliance Checklist for current user & day
        $checklistDate = Carbon::today()->toDateString();
        
        // Default checklist items
        $defaultLabels = [
            'Daily Monitoring Report Submitted',
            'Incident Verification Completed',
            'Victim Assistance Log Updated',
            'School Head Confirmation Received',
        ];
        
        // 1. Get today's non-deleted items
        $checklistItems = IncidentChecklist::where('user_id', $request->user()->id)
            ->whereDate('checklist_date', $checklistDate)
            ->where('is_deleted', false)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
            
        // 2. If it's a new day (no items yet), bring over yesterday's non-deleted custom items
        //    and create missing default items
        if (IncidentChecklist::where('user_id', $request->user()->id)->whereDate('checklist_date', $checklistDate)->count() == 0) {
            
            $yesterdayDate = Carbon::yesterday()->toDateString();
            
            // Get yesterday's non-deleted items (both default and custom)
            $yesterdayItems = IncidentChecklist::where('user_id', $request->user()->id)
                ->whereDate('checklist_date', $yesterdayDate)
                ->where('is_deleted', false)
                ->orderBy('sort_order')
                ->get();
                
            $carriedOverLabels = [];
            $nextSortOrder = 0;
            
            foreach ($yesterdayItems as $item) {
                // Determine if it was a default item (even if is_default wasn't properly set before, check by label)
                $isDefault = in_array($item->label, $defaultLabels) || $item->is_default;
                
                $checklistItems->push(
                    IncidentChecklist::create([
                        'user_id' => $request->user()->id,
                        'checklist_date' => $checklistDate,
                        'label' => $item->label,
                        'is_completed' => false, // Reset completion status
                        'is_default' => $isDefault,
                        'is_deleted' => false,
                        'sort_order' => $nextSortOrder++,
                    ])
                );
                $carriedOverLabels[] = $item->label;
            }
            
            // Ensure any default labels that weren't carried over (perhaps deleted previously but need to come back if requested? 
            // Wait, the user said: "the default task are going to be real time too so if i delete that it will no longer appear until I added a task similar to that again". 
            // So if they were deleted yesterday, they SHOULD NOT reappear automatically today.
            // But if this user has ZERO history (first time login), we should create all defaults.
            $hasAnyHistory = IncidentChecklist::where('user_id', $request->user()->id)->exists();
            
            if (!$hasAnyHistory) {
                foreach ($defaultLabels as $label) {
                    if (!in_array($label, $carriedOverLabels)) {
                        $checklistItems->push(
                            IncidentChecklist::create([
                                'user_id' => $request->user()->id,
                                'checklist_date' => $checklistDate,
                                'label' => $label,
                                'is_completed' => false,
                                'is_default' => true,
                                'is_deleted' => false,
                                'sort_order' => $nextSortOrder++,
                            ])
                        );
                    }
                }
            }
        }

        // Yesterday's Checklist (for display "What you did yesterday")
        $yesterdayDate = Carbon::yesterday()->toDateString();
        $yesterdayItems = IncidentChecklist::where('user_id', $request->user()->id)
            ->whereDate('checklist_date', $yesterdayDate)
            ->where('is_deleted', false)
            ->get();

        // Historical Checklist for Modal (Last 30 Days)
        $historyData = IncidentChecklist::where('user_id', $request->user()->id)
            ->whereDate('checklist_date', '<', $checklistDate)
            ->whereDate('checklist_date', '>=', Carbon::today()->subDays(30))
            ->orderBy('checklist_date', 'desc')
            ->get()
            ->groupBy(function($item) {
                return $item->checklist_date->format('Y-m-d');
            })
            ->sortKeysDesc();

        // Get all types and statuses for dropdowns
        $incidentTypes = IncidentType::orderBy('priority')->get();
        $incidentStatuses = IncidentStatus::orderBy('name')->get();

        // Get unique schools for autocomplete
        $schools = IncidentSchool::orderBy('name')->pluck('name')->toArray();

        // Unified schools for dropdown (Fire Safety + Typhoon/Flood centers + Incident inputs)
        $fireSafetySchools = $this->getUnifiedIncidentSchoolOptions();

        return view('incidents.dashboard', compact(
            'calendarData',
            'incidents',
            'stats',
            'incidentTypes',
            'incidentStatuses',
            'schools',
            'fireSafetySchools',
            'year',
            'month',
            'checklistItems',
            'checklistDate',
            'yesterdayItems',
            'yesterdayDate',
            'historyData'
        ));
    }

    /**
     * Create a new incident type (for legend & dropdown).
     */
    public function storeIncidentType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $maxPriority = IncidentType::max('priority');

        $type = IncidentType::create([
            'name' => $validated['name'],
            'color_class' => 'type-others',
            'description' => null,
            'priority' => is_null($maxPriority) ? 1 : $maxPriority + 1,
        ]);

        ActivityLog::log('incident_checklist', 'Created incident type: ' . $type->name);

        return response()->json([
            'success' => true,
            'type' => $type,
        ]);
    }

    /**
     * Update an incident type.
     */
    public function updateIncidentType(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $type = IncidentType::findOrFail($id);
        $type->update([
            'name' => $validated['name'],
        ]);

        ActivityLog::log('incident_checklist', 'Updated incident type: ' . $type->name);

        return response()->json([
            'success' => true,
            'type' => $type,
        ]);
    }

    /**
     * Create a new incident status / event (for legend & dropdown).
     */
    public function storeIncidentStatus(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $status = IncidentStatus::create([
            'name' => $validated['name'],
            'color_class' => 'status-no-suspension',
            'short_code' => strtoupper(substr($validated['name'], 0, 1)),
            'is_compliance' => true,
        ]);

        ActivityLog::log('incident_checklist', 'Created incident status: ' . $status->name);

        return response()->json([
            'success' => true,
            'status' => $status,
        ]);
    }

    /**
     * Update an incident status / event.
     */
    public function updateIncidentStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $status = IncidentStatus::findOrFail($id);
        $status->update([
            'name' => $validated['name'],
        ]);

        ActivityLog::log('incident_checklist', 'Updated incident status: ' . $status->name);

        return response()->json([
            'success' => true,
            'status' => $status,
        ]);
    }

    /**
     * API: Get checklist items for a specific date.
     */
    public function getChecklist(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = $request->input('date');
        
        $defaultLabels = [
            'Daily Monitoring Report Submitted',
            'Incident Verification Completed',
            'Victim Assistance Log Updated',
            'School Head Confirmation Received',
        ];

        $items = IncidentChecklist::where('user_id', $request->user()->id)
            ->whereDate('checklist_date', $date)
            ->where('is_deleted', false)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if (IncidentChecklist::where('user_id', $request->user()->id)->whereDate('checklist_date', $date)->count() == 0) {
            
            $yesterdayDate = Carbon::parse($date)->subDay()->toDateString();
            
            $yesterdayItems = IncidentChecklist::where('user_id', $request->user()->id)
                ->whereDate('checklist_date', $yesterdayDate)
                ->where('is_deleted', false)
                ->orderBy('sort_order')
                ->get();
                
            $carriedOverLabels = [];
            $nextSortOrder = 0;
            
            foreach ($yesterdayItems as $item) {
                $isDefault = in_array($item->label, $defaultLabels) || $item->is_default;
                
                $items->push(
                    IncidentChecklist::create([
                        'user_id' => $request->user()->id,
                        'checklist_date' => $date,
                        'label' => $item->label,
                        'is_completed' => false,
                        'is_default' => $isDefault,
                        'is_deleted' => false,
                        'sort_order' => $nextSortOrder++,
                    ])
                );
                $carriedOverLabels[] = $item->label;
            }
            
            $hasAnyHistory = IncidentChecklist::where('user_id', $request->user()->id)->exists();
            
            if (!$hasAnyHistory) {
                foreach ($defaultLabels as $label) {
                    if (!in_array($label, $carriedOverLabels)) {
                        $items->push(
                            IncidentChecklist::create([
                                'user_id' => $request->user()->id,
                                'checklist_date' => $date,
                                'label' => $label,
                                'is_completed' => false,
                                'is_default' => true,
                                'is_deleted' => false,
                                'sort_order' => $nextSortOrder++,
                            ])
                        );
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'items' => $items,
        ]);
    }

    /**
     * API: Store a new checklist item.
     */
    public function storeChecklistItem(Request $request)
    {
        $validated = $request->validate([
            'checklist_date' => 'required|date',
            'label' => 'required|string|max:255',
        ]);

        $maxOrder = IncidentChecklist::where('user_id', $request->user()->id)
            ->whereDate('checklist_date', $validated['checklist_date'])
            ->max('sort_order');

        $item = IncidentChecklist::create([
            'user_id' => $request->user()->id,
            'checklist_date' => $validated['checklist_date'],
            'label' => $validated['label'],
            'is_completed' => false,
            'is_default' => false,
            'is_deleted' => false,
            'sort_order' => is_null($maxOrder) ? 0 : $maxOrder + 1,
        ]);

        ActivityLog::log('incident_checklist', 'Added checklist item: ' . $item->label);

        return response()->json([
            'success' => true,
            'item' => $item,
        ]);
    }

    /**
     * API: Update checklist item (label or completion).
     */
    public function updateChecklistItem(Request $request, $id)
    {
        $item = IncidentChecklist::where('user_id', $request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'label' => 'sometimes|required|string|max:255',
            'is_completed' => 'sometimes|boolean',
        ]);

        $item->fill($validated);
        $item->save();

        ActivityLog::log('incident_checklist', 'Updated checklist item: ' . $item->label . ($item->is_completed ? ' (Completed)' : ' (Uncompleted)'));

        return response()->json([
            'success' => true,
            'item' => $item,
        ]);
    }

    /**
     * API: Delete checklist item.
     */
    public function destroyChecklistItem(Request $request, $id)
    {
        $item = IncidentChecklist::where('user_id', $request->user()->id)->findOrFail($id);
        $label = $item->label;
        
        $item->is_deleted = true;
        $item->save();

        ActivityLog::log('incident_checklist', 'Deleted checklist item: ' . $label);

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * API: Get checklist history for a specific month.
     */
    public function getHistory(Request $request)
    {
        $year = (int) $request->input('year', date('Y'));
        $month = (int) $request->input('month', date('n'));
        $today = Carbon::today()->toDateString();

        $historyData = IncidentChecklist::where('user_id', $request->user()->id)
            ->whereYear('checklist_date', $year)
            ->whereMonth('checklist_date', $month)
            ->whereDate('checklist_date', '<', $today)
            ->orderBy('checklist_date', 'desc')
            ->get()
            ->groupBy(function($item) {
                return $item->checklist_date->format('Y-m-d');
            })
            ->sortKeysDesc();

        return response()->json([
            'success' => true,
            'year' => $year,
            'month' => $month,
            'month_name' => Carbon::create($year, $month, 1)->format('F Y'),
            'history' => $historyData
        ]);
    }

    /**
     * Store a new incident.
     */
    public function store(Request $request)
    {
        // Allow empty/blank affected personnel and students (treat as 0)
        $incidentTypeRaw = $request->input('incident_type_id');
        $incidentStatusRaw = $request->input('incident_status_id');

        $request->merge([
            'affected_personnel' => $request->filled('affected_personnel') ? (int) $request->input('affected_personnel') : 0,
            'affected_students' => $request->filled('affected_students') ? (int) $request->input('affected_students') : 0,
            'incident_type_id' => $incidentTypeRaw === 'others' ? null : $incidentTypeRaw,
            'incident_status_id' => $incidentStatusRaw === 'others' ? null : $incidentStatusRaw,
        ]);

        $validated = $request->validate([
            'incident_date' => 'required|date',
            'school_name' => 'required|string|max:255',
            'entry_type' => 'required|in:incident,compliance',
            'incident_type_id' => 'nullable|exists:incident_types,id',
            'incident_status_id' => 'nullable|exists:incident_statuses,id',
            'incident_other_type' => 'nullable|string|max:255',
            'compliance_other_status' => 'nullable|string|max:255',
            'remarks' => 'required|string|max:1000',
            'reported_by' => 'nullable|string|max:255',
            'affected_personnel' => 'nullable|integer|min:0',
            'affected_students' => 'nullable|integer|min:0',
            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:10240', // 10MB in KB
            ],
        ]);

        if ($validated['entry_type'] === 'incident' && empty($validated['incident_type_id']) && empty($validated['incident_other_type'])) {
            return response()->json([
                'success' => false,
                'message' => 'Please select an Incident Type or choose Others and specify.',
            ], 422);
        }

        if ($validated['entry_type'] === 'compliance' && empty($validated['incident_status_id']) && empty($validated['compliance_other_status'])) {
            return response()->json([
                'success' => false,
                'message' => 'Please select a Compliance Status/Event or choose Others and specify.',
            ], 422);
        }

        // Add contributor info and status
        $user = auth()->user();
        $user->load('incidentSchool');
        $validated['contributor_id'] = $user->id;
        $validated['status'] = 'accepted';

        // Extract school name if using existing school
        if ($request->input('incident_source_type') === 'existing') {
            $validated['school_name'] = $request->input('school_name_existing');
        } elseif ($request->input('incident_source_type') === 'all') {
             $validated['school_name'] = 'All Schools';
        } else {
            $validated['school_name'] = $request->input('school_name_manual');
        }

        // Compliance extraction fixes as well for source
        if ($validated['entry_type'] === 'compliance') {
             if ($request->input('compliance_source_type') === 'existing') {
                $validated['school_name'] = $request->input('compliance_school_name_existing');
            } elseif ($request->input('compliance_source_type') === 'all') {
                 $validated['school_name'] = 'All Schools';
            } else {
                $validated['school_name'] = $request->input('compliance_school_name_manual');
            }
        }

        // Contributors/viewers with Incident Checklist access must report only to assigned incident school.
        if ($user->role !== 'admin') {
            if (!$user->incidentSchool) {
                return response()->json([
                    'success' => false,
                    'message' => 'No Incident Checklist school is assigned to your account. Please contact an administrator.',
                ], 422);
            }

            $validated['school_name'] = $user->incidentSchool->name;
        }

        if (empty($validated['school_name'])) {
            return response()->json([
                'success' => false,
                'message' => 'Please select a school before submitting.',
            ], 422);
        }

        if ($user->role !== 'admin' && Carbon::parse($validated['incident_date'])->isAfter(Carbon::today())) {
            return response()->json([
                'success' => false,
                'message' => 'Future dates are not allowed for logging incidents/compliance events.',
            ], 422);
        }

        if (!empty($validated['incident_other_type']) && $validated['entry_type'] === 'incident') {
            $validated['remarks'] = '[Other Incident Type: ' . trim($validated['incident_other_type']) . '] ' . $validated['remarks'];
        }

        if (!empty($validated['compliance_other_status']) && $validated['entry_type'] === 'compliance') {
            $validated['remarks'] = '[Other Compliance Status/Event: ' . trim($validated['compliance_other_status']) . '] ' . $validated['remarks'];
        }

        unset($validated['incident_other_type'], $validated['compliance_other_status']);

        // Store the incident
        $incident = IncidentCalendar::create($validated);

        ActivityLog::log('incident_checklist', 'Logged ' . $incident->entry_type . ': ' . ($incident->incidentType?->name ?? $incident->incidentStatus?->name ?? 'Entry') . ' at ' . $incident->school_name, [
            'school_name' => $incident->school_name,
            'notes' => $incident->remarks,
        ]);

        // Update or create school record (only if it's a real school name)
        if ($validated['school_name'] !== 'All Schools') {
            $this->updateSchoolRecord($validated['school_name']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Incident logged successfully!',
            'data' => $incident->load(['incidentType', 'incidentStatus'])
        ]);
    }

    /**
     * Update an existing incident or compliance event.
     */
    public function update(Request $request, $id)
    {
        $incident = IncidentCalendar::findOrFail($id);
        
        // Allow empty/blank affected personnel and students (treat as 0)
        $request->merge([
            'affected_personnel' => $request->filled('affected_personnel') ? (int) $request->input('affected_personnel') : 0,
            'affected_students' => $request->filled('affected_students') ? (int) $request->input('affected_students') : 0,
        ]);

        $validated = $request->validate([
            'incident_date' => 'required|date',
            'school_name' => 'required|string|max:255',
            'entry_type' => 'required|in:incident,compliance',
            'incident_type_id' => 'required_if:entry_type,incident|nullable|exists:incident_types,id',
            'incident_status_id' => 'required_if:entry_type,compliance|nullable|exists:incident_statuses,id',
            'remarks' => 'required|string|max:1000',
            'reported_by' => 'nullable|string|max:255',
            'affected_personnel' => 'nullable|integer|min:0',
            'affected_students' => 'nullable|integer|min:0',
            'attachment' => [
                'nullable', // Attachment is optional during update
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:10240',
            ],
        ]);

        // Handle file upload if a new one is provided
        if ($request->hasFile('attachment')) {
            // Delete old file if exists
            if ($incident->attachment_path && Storage::disk('public')->exists($incident->attachment_path)) {
                Storage::disk('public')->delete($incident->attachment_path);
            }
            
            $file = $request->file('attachment');
            $path = $file->store('incident-attachments/' . date('Y/m'), 'public');
            $validated['attachment_path'] = $path;
            $validated['attachment_name'] = $file->getClientOriginalName();
            $validated['attachment_size'] = $file->getSize();
            $validated['attachment_mime'] = $file->getMimeType();
        }

        $incident->update($validated);

        // Update or create school record (if name changed or new)
        $this->updateSchoolRecord($validated['school_name']);

        ActivityLog::log('incident_checklist', 'Updated ' . $incident->entry_type . ': ' . $incident->school_name);

        return response()->json([
            'success' => true,
            'message' => 'Record updated successfully!',
            'data' => $incident->load(['incidentType', 'incidentStatus'])
        ]);
    }

    /**
     * Get incidents for a specific date.
     */
    public function getDateIncidents($date)
    {
        $incidents = IncidentCalendar::forDate($date)
            ->with(['incidentType', 'incidentStatus', 'contributor'])
            ->orderBy('created_at', 'desc')
            ->get();

        $grouped = [
            'incidents' => $incidents->where('entry_type', 'incident')->values(),
            'compliance' => $incidents->where('entry_type', 'compliance')->values()
        ];

        return response()->json($grouped);
    }

    /**
     * Delete an incident.
     */
    public function destroy($id)
    {
        $incident = IncidentCalendar::findOrFail($id);
        $typeName = $incident->incidentType?->name ?? 'Incident';
        $schoolName = $incident->school_name;
        $incident->delete();

        ActivityLog::log('incident_checklist', 'Deleted incident: ' . $typeName . ' at ' . $schoolName);

        return response()->json(['success' => true, 'message' => 'Incident deleted successfully!']);
    }

    /**
     * Get calendar data for a specific month.
     */
    private function getCalendarData($year, $month)
    {
        $year = (int) $year;
        $month = (int) $month;
        $date = Carbon::createFromDate($year, $month, 1);
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        // Get all accepted incidents for the month
        $incidents = IncidentCalendar::whereBetween('incident_date', [$startOfMonth, $endOfMonth])
            ->where('status', 'accepted')
            ->with(['incidentType', 'incidentStatus'])
            ->get()
            ->groupBy(function($item) {
                return $item->incident_date->format('Y-m-d');
            });

        // Create calendar grid (week starts Sunday to match view headers)
        $calendar = [];
        $currentDay = $startOfMonth->copy()->startOfWeek(Carbon::SUNDAY);

        for ($week = 0; $week < 6; $week++) {
            $weekDays = [];
            for ($day = 0; $day < 7; $day++) {
                $dateString = $currentDay->format('Y-m-d');
                $isCurrentMonth = $currentDay->month == $month;

                $dayIncidents = $incidents->get($dateString, collect());

                $weekDays[] = [
                    'date' => $dateString,
                    'day' => $currentDay->day,
                    'is_current_month' => $isCurrentMonth,
                    'incidents' => $dayIncidents->where('entry_type', 'incident')->take(3),
                    'compliance' => $dayIncidents->where('entry_type', 'compliance')->take(2),
                    'has_more' => $dayIncidents->count() > 5
                ];

                $currentDay->addDay();
            }
            $calendar[] = $weekDays;

            // Stop if we've moved past the end of month
            if ($currentDay->month != $month && $currentDay->day > 7) {
                break;
            }
        }

        return $calendar;
    }

    /**
     * Get monthly statistics.
     */
    private function getMonthlyStats($year, $month)
    {
        $incidents = IncidentCalendar::forMonth($year, $month)
            ->where('status', 'accepted')
            ->with(['incidentType', 'incidentStatus'])
            ->get();

        $total = $incidents->count();
        $incidentCount = $incidents->where('entry_type', 'incident')->count();
        $complianceCount = $incidents->where('entry_type', 'compliance')->count();
        $schoolsAffected = $incidents->pluck('school_name')->unique()->count();

        // Type distribution for incidents only
        $typeGroups = $incidents
            ->where('entry_type', 'incident')
            ->groupBy('incident_type_id');

        $typeLabels = [];
        $typeValues = [];
        foreach ($typeGroups as $typeId => $group) {
            $typeName = optional($group->first()->incidentType)->name ?? 'Unspecified';
            $typeLabels[] = $typeName;
            $typeValues[] = $group->count();
        }

        // Compliance Status distribution (New for Bar Chart)
        $complianceGroups = $incidents
            ->where('entry_type', 'compliance')
            ->groupBy('incident_status_id');

        $complianceLabels = [];
        $complianceValues = [];
        foreach ($complianceGroups as $statusId => $group) {
            $statusName = optional($group->first()->incidentStatus)->name ?? 'Unspecified';
            $complianceLabels[] = $statusName;
            $complianceValues[] = $group->count();
        }

        // Daily trend (incidents only) within the month
        $dailyGroups = $incidents
            ->where('entry_type', 'incident')
            ->groupBy(function ($item) {
                return $item->incident_date->format('Y-m-d');
            })
            ->sortKeys();

        $trendLabels = [];
        $trendValues = [];
        foreach ($dailyGroups as $date => $group) {
            $trendLabels[] = $date;
            $trendValues[] = $group->count();
        }

        return [
            'total' => $total,
            'incidents' => $incidentCount,
            'compliance' => $complianceCount,
            'schools_affected' => $schoolsAffected,
            'type_distribution' => [
                'labels' => $typeLabels,
                'values' => $typeValues,
            ],
            'compliance_distribution' => [
                'labels' => $complianceLabels,
                'values' => $complianceValues,
            ],
            'trend' => [
                'labels' => $trendLabels,
                'values' => $trendValues,
            ],
        ];
    }

    /**
     * Update or create school record.
     */
    private function updateSchoolRecord($schoolName)
    {
        $school = IncidentSchool::firstOrCreate(
            ['name' => $schoolName],
            ['district' => 'Unknown']
        );

        $school->increment('incident_count');
        $school->last_incident_date = now();
        $school->save();
    }

    /**
     * Build deduplicated school options for Incident Checklist selectors.
     */
    private function getUnifiedIncidentSchoolOptions()
    {
        $sourceNames = collect();

        $sourceNames = $sourceNames
            ->merge(FireSafetySchool::whereNotNull('school_name')->pluck('school_name'))
            ->merge(IncidentSchool::whereNotNull('name')->pluck('name'))
            ->merge(
                TypFldEvacuationCenter::with('school:id,school_name')
                    ->get()
                    ->map(function ($center) {
                        return $center->school?->school_name ?: $center->identification;
                    })
            );

        $normalized = [];
        foreach ($sourceNames as $name) {
            $clean = trim((string) $name);
            if ($clean === '') {
                continue;
            }

            $key = mb_strtolower(preg_replace('/\s+/', ' ', $clean));
            if (!isset($normalized[$key])) {
                $normalized[$key] = $clean;
            }
        }

        foreach ($normalized as $schoolName) {
            IncidentSchool::firstOrCreate(
                ['name' => $schoolName, 'district' => 'Unknown'],
                ['division' => null, 'region' => null, 'school_id' => null]
            );
        }

        return collect(array_values($normalized))
            ->sort()
            ->values()
            ->map(function ($name) {
                return (object) ['school_name' => $name];
            });
    }

    /**
     * Search schools for autocomplete.
     */
    public function searchSchools(Request $request)
    {
        $query = $request->input('q');
        $schools = IncidentSchool::where('name', 'like', "%{$query}%")
            ->limit(10)
            ->get(['name', 'district']);

        return response()->json($schools);
    }

    /**
     * Export all incident-related data as a JSON backup.
     */
    public function export(Request $request)
    {
        $payload = [
            'generated_at' => now()->toIso8601String(),
            'generated_by' => $request->user()->email ?? null,
            'incident_calendars' => IncidentCalendar::all(),
            'incident_types' => IncidentType::all(),
            'incident_statuses' => IncidentStatus::all(),
            'incident_schools' => IncidentSchool::all(),
        ];

        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $fileName = 'incidents-backup-' . now()->format('Ymd_His') . '.json';

        return response($json, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
        ]);
    }

    /**
     * Import incident-related data from a previously exported JSON backup.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        $contents = file_get_contents($request->file('file')->getRealPath());
        $data = json_decode($contents, true);

        if (!is_array($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid backup file format.',
            ], 422);
        }

        DB::transaction(function () use ($data) {
            // Disable foreign key checks to allow safe truncation/reseed
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Truncate child table first to satisfy existing FKs
            if (isset($data['incident_calendars']) && is_array($data['incident_calendars'])) {
                IncidentCalendar::truncate();
            }
            if (isset($data['incident_types']) && is_array($data['incident_types'])) {
                IncidentType::truncate();
            }
            if (isset($data['incident_statuses']) && is_array($data['incident_statuses'])) {
                IncidentStatus::truncate();
            }
            if (isset($data['incident_schools']) && is_array($data['incident_schools'])) {
                IncidentSchool::truncate();
            }

            // Restore lookup tables first
            if (isset($data['incident_types']) && is_array($data['incident_types'])) {
                foreach ($data['incident_types'] as $row) {
                    IncidentType::create([
                        'id' => $row['id'] ?? null,
                        'name' => $row['name'] ?? '',
                        'color_class' => $row['color_class'] ?? null,
                        'description' => $row['description'] ?? null,
                        'priority' => $row['priority'] ?? 0,
                    ]);
                }
            }

            if (isset($data['incident_statuses']) && is_array($data['incident_statuses'])) {
                foreach ($data['incident_statuses'] as $row) {
                    IncidentStatus::create([
                        'id' => $row['id'] ?? null,
                        'name' => $row['name'] ?? '',
                        'color_class' => $row['color_class'] ?? null,
                        'short_code' => $row['short_code'] ?? null,
                        'is_compliance' => $row['is_compliance'] ?? true,
                    ]);
                }
            }

            if (isset($data['incident_schools']) && is_array($data['incident_schools'])) {
                foreach ($data['incident_schools'] as $row) {
                    IncidentSchool::create($row);
                }
            }

            if (isset($data['incident_calendars']) && is_array($data['incident_calendars'])) {
                foreach ($data['incident_calendars'] as $row) {
                    IncidentCalendar::create($row);
                }
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        });

        ActivityLog::log('incident_checklist', 'Imported backup data', [
            'notes' => 'Full data restore from backup file',
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Generate a monthly incidents/events calendar report as DOCX (or HTML fallback).
     */
    public function printMonth(Request $request)
    {
        $year = (int) $request->input('year', date('Y'));
        $month = (int) $request->input('month', date('n'));
        $monthName = Carbon::create($year, $month, 1)->format('F Y');

        $records = IncidentCalendar::forMonth($year, $month)
            ->with(['incidentType', 'incidentStatus'])
            ->orderBy('incident_date')
            ->orderBy('school_name')
            ->get();

        // Prefer DOCX if PhpWord is available, otherwise fall back to HTML table
        if (class_exists(PhpWord::class)) {
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();

            $section->addTitle('Incidents & Events Calendar Report', 1);
            $section->addText(
                sprintf('Month: %s %d', Carbon::create($year, $month, 1)->format('F'), $year),
                ['bold' => true]
            );
            $section->addTextBreak(1);

            $table = $section->addTable([
                'borderSize' => 6,
                'borderColor' => '999999',
                'cellMargin' => 80,
                'width' => 100 * 50,
            ]);

            // Header row
            $table->addRow();
            $table->addCell()->addText('Date', ['bold' => true]);
            $table->addCell()->addText('School Name', ['bold' => true]);
            $table->addCell()->addText('Category', ['bold' => true]);
            $table->addCell()->addText('Event Classification', ['bold' => true]);
            $table->addCell()->addText('Remarks', ['bold' => true]);

            foreach ($records as $rec) {
                $table->addRow();
                $dateText = $rec->incident_date ? $rec->incident_date->format('Y-m-d') : '';
                $category = $rec->entry_type === 'incident'
                    ? 'Incident'
                    : 'Compliance Status/Events';
                $classification = $rec->entry_type === 'incident'
                    ? optional($rec->incidentType)->name
                    : optional($rec->incidentStatus)->name;

                $table->addCell()->addText($dateText);
                $table->addCell()->addText($rec->school_name ?? '');
                $table->addCell()->addText($category);
                $table->addCell()->addText($classification ?? '');
                $table->addCell()->addText($rec->remarks ?? '');
            }

            $fileName = sprintf('Incidents-Calendar-%d-%02d.docx', $year, $month);
            $tempPath = storage_path('app/' . $fileName);
            $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save($tempPath);

            return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
        }

        // HTML fallback if PhpWord is not installed
        return view('incidents.print-html', compact('records', 'year', 'month', 'monthName'));
    }

    /**
     * Print analytics distribution for incident type or compliance status/events.
     */
    public function printAnalytics(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:incident_type,compliance_status',
            'year' => 'nullable|integer|min:2000|max:2100',
            'month' => 'nullable|integer|min:1|max:12',
        ]);

        $chartKey = $request->input('chart_key');

        $year = (int) ($validated['year'] ?? date('Y'));
        $month = (int) ($validated['month'] ?? date('n'));
        $monthName = Carbon::create($year, $month, 1)->format('F Y');
        $stats = $this->getMonthlyStats($year, $month);

        $isIncidentType = $validated['type'] === 'incident_type';
        $chartData = $isIncidentType
            ? ($stats['type_distribution'] ?? ['labels' => [], 'values' => []])
            : ($stats['compliance_distribution'] ?? ['labels' => [], 'values' => []]);

        $labels = $chartData['labels'] ?? [];
        $values = $chartData['values'] ?? [];
        $total = array_sum($values);

        $rows = collect($labels)->map(function ($label, $index) use ($values, $total) {
            $count = (int) ($values[$index] ?? 0);
            $percent = $total > 0 ? round(($count / $total) * 100, 2) : 0;

            return [
                'label' => $label,
                'count' => $count,
                'percent' => $percent,
            ];
        })->sortByDesc('count')->values();

        $reportTitle = $isIncidentType
            ? 'Incident Type Distribution'
            : 'Compliance Status / Events Distribution';

        return view('incidents.print-analytics', compact(
            'year',
            'month',
            'monthName',
            'reportTitle',
            'rows',
            'total',
            'chartKey'
        ));
    }

    /**
     * Print contributor report for daily, weekly, or monthly periods.
     */
    public function printContributorReport(Request $request)
    {
        $validated = $request->validate([
            'period' => 'required|in:daily,weekly,monthly',
            'date' => 'nullable|date',
        ]);

        $period = $validated['period'];
        $baseDate = Carbon::parse($validated['date'] ?? now()->toDateString());

        if ($period === 'daily') {
            $start = $baseDate->copy()->startOfDay();
            $end = $baseDate->copy()->endOfDay();
            $periodLabel = 'Daily';
        } elseif ($period === 'weekly') {
            $start = $baseDate->copy()->startOfWeek(Carbon::MONDAY)->startOfDay();
            $end = $baseDate->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay();
            $periodLabel = 'Weekly';
        } else {
            $start = $baseDate->copy()->startOfMonth()->startOfDay();
            $end = $baseDate->copy()->endOfMonth()->endOfDay();
            $periodLabel = 'Monthly';
        }

        $user = $request->user();

        $records = IncidentCalendar::where('contributor_id', $user->id)
            ->with(['incidentType', 'incidentStatus'])
            ->whereBetween('incident_date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('incident_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $summary = [
            'total' => $records->count(),
            'incidents' => $records->where('entry_type', 'incident')->count(),
            'compliance' => $records->where('entry_type', 'compliance')->count(),
            'accepted' => $records->where('status', 'accepted')->count(),
            'pending' => $records->where('status', 'pending')->count(),
            'rejected' => $records->where('status', 'rejected')->count(),
        ];

        $rangeLabel = $period === 'daily'
            ? $start->format('F d, Y')
            : $start->format('F d, Y') . ' - ' . $end->format('F d, Y');

        return view('incidents.print-contributor-report', compact(
            'records',
            'period',
            'periodLabel',
            'rangeLabel',
            'summary',
            'user'
        ));
    }

    /**
     * API: Get pending reports for admin review.
     */
    public function getPendingReports()
    {
        $pending = IncidentCalendar::where('status', 'pending')
            ->with(['incidentType', 'incidentStatus', 'contributor'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $pending->count(),
            'reports' => $pending
        ]);
    }

    /**
     * API: Accept a contributor report.
     */
    public function acceptReport($id)
    {
        $report = IncidentCalendar::findOrFail($id);
        $report->status = 'accepted';
        $report->save();

        // No need for explicit activity log if already using status
        // ActivityLog::log...
        
        return response()->json([
            'success' => true,
            'message' => 'Report accepted and added to calendar.'
        ]);
    }

    /**
     * API: Reject a contributor report.
     */
    public function rejectReport(Request $request, $id)
    {
        $report = IncidentCalendar::findOrFail($id);
        $report->status = 'rejected';
        $report->rejection_reason = $request->input('reason');
        $report->save();

        return response()->json([
            'success' => true,
            'message' => 'Report rejected and contributor notified.'
        ]);
    }
}
