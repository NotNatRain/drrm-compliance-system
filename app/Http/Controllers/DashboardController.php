<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FireSafetySchool;
use App\Models\ComprehensiveSchool;
use App\Models\TypFldEvacuationCenter;
use App\Models\IncidentSchool;
use App\Models\Announcement;
use App\Models\School;
use App\Models\SchoolSpecificsInformation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private function resolveComprehensiveSchoolIdFromFireSafetyId(?int $fireSafetySchoolId): ?int
    {
        if (!$fireSafetySchoolId) {
            return null;
        }

        $fireSafetySchool = FireSafetySchool::find($fireSafetySchoolId);
        if (!$fireSafetySchool) {
            return null;
        }

        $comprehensiveSchool = ComprehensiveSchool::where('school_id_number', $fireSafetySchool->school_id)->first();
        if ($comprehensiveSchool) {
            return (int) $comprehensiveSchool->id;
        }

        $fallbackByName = ComprehensiveSchool::where('name', $fireSafetySchool->school_name)->first();

        return $fallbackByName ? (int) $fallbackByName->id : null;
    }

    private function resolveFireSafetySchoolIdFromComprehensiveSchoolId(?int $comprehensiveSchoolId): ?int
    {
        if (!$comprehensiveSchoolId) {
            return null;
        }

        $comprehensiveSchool = ComprehensiveSchool::find($comprehensiveSchoolId);
        if (!$comprehensiveSchool) {
            return null;
        }

        if (!empty($comprehensiveSchool->school_id_number)) {
            $fireByCode = FireSafetySchool::where('school_id', $comprehensiveSchool->school_id_number)->first();
            if ($fireByCode) {
                return (int) $fireByCode->id;
            }
        }

        $fireByName = FireSafetySchool::where('school_name', $comprehensiveSchool->name)->first();

        return $fireByName ? (int) $fireByName->id : null;
    }

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        
        $schools = FireSafetySchool::all();
        // If user is contributor, only pass their assigned school
        if ($user->role === 'contributor' && $user->school_id) {
            $schools = FireSafetySchool::where('id', $user->school_id)->get();
        }
        
        $allSchools = [];
        if ($isAdmin) {
            $allSchools = School::orderBy('school_name')->get();
        }
        
        $announcements = Announcement::where('is_active', true)->latest()->get();
        return view('dashboard', compact('schools', 'announcements', 'allSchools', 'isAdmin'));
    }

    /**
     * Get detailed school info for the View Details modal
     */
    public function getUnifiedSchoolDetails($id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $school = School::with('specifics')->findOrFail($id);
        
        // Modules registration status mapping
        $modulesSatus = [
            'fire_safety' => $school->specifics->where('module', 'fire_safety')->where('key', 'original_fire_safety_id')->isNotEmpty(),
            'typhoon_flood' => $school->specifics->where('module', 'typhoon_flood')->where('key', 'original_evacuation_center_id')->isNotEmpty(),
            'incident_checklist' => $school->specifics->where('module', 'incident')->where('key', 'original_incident_school_id')->isNotEmpty(),
            'comprehensive_school_safety' => $school->specifics->where('module', 'comprehensive')->where('key', 'original_cmpr_school_id')->isNotEmpty(),
            'hazard_mapping' => false // Still to be developed
        ];

        return response()->json([
            'school' => $school,
            'modules' => $modulesSatus
        ]);
    }

    /**
     * Store a new school from the Schools tab
     */
    public function storeUnifiedSchool(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'school_name' => 'required|string|max:255|unique:schools,school_name',
            'school_id' => 'nullable|string|max:255',
            'address' => 'required|string',
            'school_head' => 'nullable|string|max:255',
            'drrm_coordinator' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'division' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $school = School::create($request->all());

        return response()->json(['success' => true, 'message' => 'School added successfully!', 'school' => $school]);
    }

    /**
     * Update school details
     */
    public function updateUnifiedSchool(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $school = School::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'school_name' => 'required|string|max:255|unique:schools,school_name,' . $id,
            'school_id' => 'nullable|string|max:255',
            'address' => 'required|string',
            'school_head' => 'nullable|string|max:255',
            'drrm_coordinator' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'division' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:255',
            'contact_number_2' => 'nullable|string|max:255',
            'evacuation_capacity' => 'nullable|integer',
            'emergency_resources' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $school->update($request->all());

        return response()->json(['success' => true, 'message' => 'School updated successfully!']);
    }

    public function getUsers(Request $request)
    {
        $isAdmin = Auth::user()->role === 'admin';

        if ($isAdmin) {
            $query = User::with(['school', 'typhoonSchool.school', 'incidentSchool']);

            // Filters
            if ($request->filled('role')) {
                $query->where('role', $request->role);
            }

            $sort = $request->get('sort', 'name');
            $order = $request->get('order', 'asc');

            if ($sort === 'created_at') {
                $query->orderBy('created_at', $order);
            } else {
                $query->orderBy('name', $order);
            }

            $users = $query->get();
        } else {
            $users = User::with(['school', 'typhoonSchool.school', 'incidentSchool'])->whereKey(Auth::id())->get();
        }

        $schools = FireSafetySchool::all();
        $comprehensiveSchools = ComprehensiveSchool::orderBy('name')->get(['id', 'name', 'school_id_number', 'district', 'division']);
        $typhoonSchools = TypFldEvacuationCenter::with('school')->get();
        $this->syncIncidentSchoolsFromSources();
        $incidentSchools = IncidentSchool::selectRaw('MIN(id) as id, name')
            ->groupBy('name')
            ->orderBy('name')
            ->get();
        $adminCount = User::where('role', 'admin')->count();

        if ($request->expectsJson()) {
            return response()->json($users);
        }

        return view('users.index', compact('users', 'schools', 'comprehensiveSchools', 'typhoonSchools', 'incidentSchools', 'adminCount', 'isAdmin'));
    }

    /**
     * Keep IncidentSchool options in sync with Fire Safety and Typhoon/Flood sources.
     */
    private function syncIncidentSchoolsFromSources(): void
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
    }

    public function storeUser(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,contributor,viewer',
            'admin_confirmation' => 'required_if:role,admin'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        if ($request->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount >= 2) {
                return response()->json(['success' => false, 'message' => 'The system is limited to a maximum of 2 administrators.'], 403);
            }
            if (!Hash::check($request->admin_confirmation, Auth::user()->password)) {
                return response()->json(['success' => false, 'message' => 'Invalid admin confirmation password'], 403);
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'module_access' => [], // Start empty
            'needs_fs_registration' => ($request->role === 'contributor'),
            'needs_tf_registration' => ($request->role === 'contributor'),
        ]);

        return response()->json(['success' => true, 'message' => 'User created successfully', 'user' => $user]);
    }

    public function getUser($id)
    {
        $isAdmin = Auth::user()->role === 'admin';
        if (!$isAdmin && (int) $id !== (int) Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::with(['school', 'incidentSchool'])->findOrFail($id);
        $user->school_safety_id = $this->resolveComprehensiveSchoolIdFromFireSafetyId($user->school_id);

        return response()->json($user);
    }

    public function updateUser(Request $request, $id)
    {
        $isAdmin = Auth::user()->role === 'admin';
        if (!$isAdmin && (int) $id !== (int) Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
        ];

        if ($isAdmin) {
            $rules['role'] = 'required|string|in:admin,contributor,viewer';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if ($isAdmin) {
            $user->role = $request->role;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json(['success' => true, 'message' => 'User updated successfully']);
    }

    public function toggleUserStatus($id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($id == Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Cannot deactivate yourself'], 400);
        }

        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';
        return response()->json(['success' => true, 'message' => "User account has been $status successfully."]);
    }

    public function assignAccess(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);

        $user->module_access = $request->modules ?? [];

        // Fire Safety school assignment
        if ($request->school_id === 'encode') {
            $user->school_id = null;
            $user->needs_fs_registration = true;
        } elseif (!empty($request->school_id)) {
            $user->school_id = $request->school_id;
            $user->needs_fs_registration = false;
        }

        // Comprehensive School Safety assignment (mapped to existing Fire Safety assignment)
        if (!empty($request->school_safety_id)) {
            $mappedFireSafetySchoolId = $this->resolveFireSafetySchoolIdFromComprehensiveSchoolId((int) $request->school_safety_id);

            if ($mappedFireSafetySchoolId) {
                $user->school_id = $mappedFireSafetySchoolId;
                $user->needs_fs_registration = false;
            }
        }

        // Typhoon school assignment
        if ($request->typhoon_school_id === 'encode') {
            $user->typhoon_school_id = null;
            $user->needs_tf_registration = true;
        } else {
            $user->typhoon_school_id = $request->typhoon_school_id;
            $user->needs_tf_registration = false;
        }

        // Incident Checklist school assignment
        $user->incident_school_id = $request->incident_school_id ?: null;

        $user->save();

        return response()->json(['success' => true, 'message' => 'Permissions updated successfully']);
    }

    public function deleteUser($id)
    {
        return response()->json(['success' => false, 'message' => 'User deletion is disabled. Please deactivate the account instead.'], 400);
    }

    public function registerMySchool(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'contributor') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'school_name' => 'required|string|max:255',
            'school_id_number' => 'required|string|unique:firesafety_school_information,school_id',
            'address' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            DB::transaction(function () use ($request, &$user) {
                // Create the base school record
                $school = FireSafetySchool::create([
                    'school_name' => $request->school_name,
                    'school_id' => $request->school_id_number,
                    'address' => $request->address,
                    'status' => 'unconfigured',
                ]);

                // Link to Fire Safety if needed
                if ($user->needs_fs_registration) {
                    $user->school_id = $school->id;
                    $user->needs_fs_registration = false;
                }

                // Link to Typhoon if needed
                if ($user->needs_tf_registration) {
                    // Create evacuation center record
                    $ec = TypFldEvacuationCenter::create([
                        'school_id' => $school->id,
                        'identification' => $request->school_id_number,
                        'location' => $request->address,
                        'usage_status' => 'cleared',
                    ]);
                    $user->typhoon_school_id = $ec->id;
                    $user->needs_tf_registration = false;
                }

                $user->save();
            });

            return response()->json(['success' => true, 'message' => 'Your school has been registered and assigned!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()], 500);
        }
    }

    public function storeAnnouncement(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'what' => 'required|string|max:255',
            'when' => 'required|date',
            'where' => 'required|string|max:255',
            'why' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            // Check if there's an existing active announcement and deactivate it

            $imagePath = $request->file('image')->store('announcements', 'public');

            $announcement = Announcement::create([
                'what' => $request->what,
                'when' => $request->when,
                'where' => $request->where,
                'why' => $request->why,
                'image_path' => $imagePath,
                'is_active' => true,
            ]);

            return response()->json(['success' => true, 'message' => 'Announcement posted successfully!', 'announcement' => $announcement]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to save announcement: ' . $e->getMessage()], 500);
        }
    }

    public function deleteAnnouncement($id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return response()->json(['success' => true, 'message' => 'Announcement deleted successfully']);
    }
}
