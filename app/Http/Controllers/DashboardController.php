<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FireSafetySchool;
use App\Models\TypFldEvacuationCenter;
use App\Models\Announcement;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
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
        $schools = FireSafetySchool::all();
        // If user is contributor, only pass their assigned school
        if (auth()->user()->role === 'contributor' && auth()->user()->school_id) {
            $schools = FireSafetySchool::where('id', auth()->user()->school_id)->get();
        }
        $announcements = Announcement::where('is_active', true)->latest()->get();
        return view('dashboard', compact('schools', 'announcements'));
    }

    public function getUsers(Request $request)
    {
        $isAdmin = auth()->user()->role === 'admin';

        if ($isAdmin) {
            $query = User::with(['school', 'typhoonSchool.school']);

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
            $users = User::with(['school', 'typhoonSchool.school'])->whereKey(auth()->id())->get();
        }

        $schools = FireSafetySchool::all();
        $typhoonSchools = TypFldEvacuationCenter::all();
        $adminCount = User::where('role', 'admin')->count();

        if ($request->expectsJson()) {
            return response()->json($users);
        }

        return view('users.index', compact('users', 'schools', 'typhoonSchools', 'adminCount', 'isAdmin'));
    }

    public function storeUser(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
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
            if (!Hash::check($request->admin_confirmation, auth()->user()->password)) {
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
        $isAdmin = auth()->user()->role === 'admin';
        if (!$isAdmin && (int) $id !== (int) auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::with('school')->findOrFail($id);
        return response()->json($user);
    }

    public function updateUser(Request $request, $id)
    {
        $isAdmin = auth()->user()->role === 'admin';
        if (!$isAdmin && (int) $id !== (int) auth()->id()) {
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
        if (auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($id == auth()->id()) {
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
        if (auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);
        
        $user->module_access = $request->modules ?? [];
        
        // Fire Safety school assignment
        if ($request->school_id === 'encode') {
            $user->school_id = null;
            $user->needs_fs_registration = true;
        } else {
            $user->school_id = $request->school_id;
            $user->needs_fs_registration = false;
        }

        // Typhoon school assignment
        if ($request->typhoon_school_id === 'encode') {
            $user->typhoon_school_id = null;
            $user->needs_tf_registration = true;
        } else {
            $user->typhoon_school_id = $request->typhoon_school_id;
            $user->needs_tf_registration = false;
        }

        $user->save();

        return response()->json(['success' => true, 'message' => 'Permissions updated successfully']);
    }

    public function deleteUser($id)
    {
        return response()->json(['success' => false, 'message' => 'User deletion is disabled. Please deactivate the account instead.'], 400);
    }

    public function registerMySchool(Request $request)
    {
        $user = auth()->user();
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
        if (auth()->user()->role !== 'admin') {
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
        if (auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return response()->json(['success' => true, 'message' => 'Announcement deleted successfully']);
    }
}
