<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncidentCalendar;
use App\Models\IncidentType;
use App\Models\IncidentStatus;
use App\Models\IncidentSchool;
use Carbon\Carbon;

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
            
        // Get statistics
        $stats = $this->getMonthlyStats($year, $month);
        
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
            'month'
        ));
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
        ]);
        
        // Add reported by (current user)
        $validated['reported_by'] = auth()->user()->name;
        
        // Store the incident
        $incident = IncidentCalendar::create($validated);
        
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
        $incident->delete();
        
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
        $incidents = IncidentCalendar::forMonth($year, $month)->get();
        
        return [
            'total' => $incidents->count(),
            'incidents' => $incidents->where('entry_type', 'incident')->count(),
            'compliance' => $incidents->where('entry_type', 'compliance')->count(),
            'schools_affected' => $incidents->pluck('school_name')->unique()->count(),
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
}