<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Only admin and viewer can see activity logs; contributors cannot.
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role === 'contributor') {
                abort(403, 'You do not have permission to view activity logs.');
            }
            return $next($request);
        });
    }

    /**
     * Display the activity log table (Date, User, Role, Activity, School, Module, Notes).
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with(['user', 'school'])
            ->orderBy('created_at', 'desc');

        // Filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }
        if ($request->filled('school')) {
            $query->where(function ($q) use ($request) {
                $q->where('school_name', 'like', '%' . $request->school . '%')
                    ->orWhere('school_id', $request->school);
            });
        }
        if ($request->filled('activity')) {
            $query->where('activity', 'like', '%' . $request->activity . '%');
        }

        $logs = $query->paginate(25)->withQueryString();

        $users = User::orderBy('name')->get(['id', 'name']);
        $modules = [
            'fire_safety' => 'Fire Safety',
            'typhoon_flood' => 'Typhoon & Flood',
            'incident_checklist' => 'Incident Checklist',
            'comprehensive_safety' => 'Comprehensive School Safety',
            'hazard_mapping' => 'Hazard Mapping',
        ];

        return view('activity-logs.index', compact('logs', 'users', 'modules'));
    }
}
