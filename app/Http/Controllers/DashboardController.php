<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FireSafetySchool;
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
        return view('dashboard', compact('schools'));
    }

    public function getUsers()
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $users = User::with('school')->get()->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'school' => $user->school,
                'status' => 'active', 
                'last_login_at' => null 
            ];
        });

        return response()->json($users);
    }

    public function storeUser(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
            'school_id' => 'nullable|exists:firesafety_school_information,id',
            'modules' => 'required|array',
            'admin_confirmation' => 'required_if:role,admin'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        // Check admin confirmation if creating an admin
        if ($request->role === 'admin') {
            if (!Hash::check($request->admin_confirmation, auth()->user()->password)) {
                return response()->json(['success' => false, 'message' => 'Invalid admin confirmation password'], 403);
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'school_id' => $request->school_id,
            'module_access' => $request->modules,
        ]);

        return response()->json(['success' => true, 'message' => 'User created successfully', 'user' => $user]);
    }

    public function getUser($id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::with('school')->findOrFail($id);
        return response()->json($user);
    }

    public function deleteUser($id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($id == auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete yourself'], 400);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }
}
