<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncidentCalendar;
use App\Models\IncidentType;
use App\Models\IncidentStatus;
use App\Models\IncidentSchool;
use App\Models\IncidentChecklist;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
        $checklistItems = IncidentChecklist::where('user_id', $request->user()->id)
            ->whereDate('checklist_date', $checklistDate)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($checklistItems->isEmpty()) {
            $defaultLabels = [
                'Daily Monitoring Report Submitted',
                'Incident Verification Completed',
                'Victim Assistance Log Updated',
                'School Head Confirmation Received',
            ];
            foreach ($defaultLabels as $index => $label) {
                $checklistItems->push(
                    IncidentChecklist::create([
                        'user_id' => $request->user()->id,
                        'checklist_date' => $checklistDate,
                        'label' => $label,
                        'is_completed' => false,
                        'sort_order' => $index,
                    ])
                );
            }
        }

        // Get all types and statuses for dropdowns
        $incidentTypes = IncidentType::orderBy('priority')->get();
        $incidentStatuses = IncidentStatus::orderBy('name')->get();

        // Get unique schools for autocomplete
        $schools = IncidentSchool::orderBy('name')->pluck('name')->toArray();

        return view('incidents.dashboard', compact(
            'calendarData',
            'incidents',
            'stats',
            'incidentTypes',
            'incidentStatuses',
            'schools',
            'year',
            'month',
            'checklistItems',
            'checklistDate'
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

        $items = IncidentChecklist::where('user_id', $request->user()->id)
            ->whereDate('checklist_date', $date)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($items->isEmpty()) {
            $defaultLabels = [
                'Daily Monitoring Report Submitted',
                'Incident Verification Completed',
                'Victim Assistance Log Updated',
                'School Head Confirmation Received',
            ];
            foreach ($defaultLabels as $index => $label) {
                $items->push(
                    IncidentChecklist::create([
                        'user_id' => $request->user()->id,
                        'checklist_date' => $date,
                        'label' => $label,
                        'is_completed' => false,
                        'sort_order' => $index,
                    ])
                );
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
        $item->delete();

        ActivityLog::log('incident_checklist', 'Deleted checklist item: ' . $label);

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Store a new incident.
     */
    public function store(Request $request)
    {
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
                $request->input('entry_type') === 'incident' ? 'required' : 'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:10240', // 10MB in KB
            ],
        ]);

        // Add reported by (current user)
        $validated['reported_by'] = auth()->user()->name;

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('incident-attachments/' . date('Y/m'), 'public');
            $validated['attachment_path'] = $path;
            $validated['attachment_name'] = $file->getClientOriginalName();
            $validated['attachment_size'] = $file->getSize();
            $validated['attachment_mime'] = $file->getMimeType();
        }

        // Store the incident
        $incident = IncidentCalendar::create($validated);

        ActivityLog::log('incident_checklist', 'Logged incident: ' . ($incident->incidentType?->name ?? 'Incident') . ' at ' . $incident->school_name, [
            'school_name' => $incident->school_name,
            'notes' => $incident->remarks,
        ]);

        // Update or create school record
        $this->updateSchoolRecord($validated['school_name']);

        return response()->json([
            'success' => true,
            'message' => 'Incident logged successfully!',
            'data' => $incident->load(['incidentType', 'incidentStatus'])
        ]);
    }

    /**
     * Get incidents for a specific date.
     */
    public function getDateIncidents($date)
    {
        $incidents = IncidentCalendar::forDate($date)
            ->with(['incidentType', 'incidentStatus'])
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

        // Get all incidents for the month
        $incidents = IncidentCalendar::whereBetween('incident_date', [$startOfMonth, $endOfMonth])
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
        $incidents = IncidentCalendar::forMonth($year, $month)->with('incidentType')->get();

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
        $monthName = Carbon::create($year, $month, 1)->format('F Y');

        return view('incidents.print-html', compact('records', 'year', 'month', 'monthName'));
    }
}
