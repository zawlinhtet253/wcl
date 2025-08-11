<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index() {
        if(Auth::user()->level != 3 ) {
            abort(403 , 'Unauthorized action.');
        } 
        $users = User::with('employee')->get();
        return view('pages.admin.user.users', compact('users'));
    }
    public function detail() {
        $user = Auth::user();
        return view('pages.user.detail', compact('user'));
    }
    public function create() {
        $user = Auth::user();      
        if($user->level != 3) { 
            return redirect()->route('user.dashboard')->with('error', 'Unauthorized.');
        }
        $teams = Team::all();
        return view('pages.admin.user.user_create', compact('teams'));
    }

    public function store(Request $request) {
        $user = Auth::user();
        if ($user->level != 3) {
            return redirect()->route('user.dashboard')->with('error', 'Unauthorized.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'employee_code' => 'required|string|max:255|unique:employees,employee_code',
            'team_id' => 'required|exists:teams,id',
            'position' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password); // Fixed typo
            $user->save();

            $employee = new Employee;
            $employee->employee_code = $request->employee_code;
            $employee->user_id = $user->id;
            $employee->team_id = $request->team_id;
            $employee->position = $request->position;
            $employee->save();

            DB::commit();
            return redirect()->route('admin.users')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create user: ' . $e->getMessage())->withInput();
        }
    }
    public function edit()
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        if($user->level != 3) { 
            $teams = collect();
            return view('pages.user.edit_user', compact('user', 'employee', 'teams'));
        }
        $teams = Team::all();
        return view('pages.user.edit_user', compact('user', 'employee' , 'teams'));
    }

    public function update(Request $request) {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();

        // Validate the request
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'nrc' => ['sometimes', 'required', 'string', 'max:50'],
            'address' => ['sometimes', 'nullable', 'string', 'max:500'],
        ]);

        $user = User::find($user->id);
        $user->name = $validated['name'] ?? $user->name;
        $employee->address = $validated['address'] ?? $employee->address;
        $employee->nrc = $validated['nrc'] ?? $employee->nrc;
        $employee->save();
        $user->save();

        return redirect()->route('user.detail')
            ->with('status', 'Profile updated successfully');
    }

}
