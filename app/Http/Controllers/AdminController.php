<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Models\Employee;
use App\Models\Timesheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    //
    public function show($id) {
        $user = User::find($id);
        $employee = Employee::where('user_id', $user->id)->first();
        return view('pages.admin.user.user_detail', compact('user', 'employee'));
    }
    public function edit($id)
    {
        $user = User::with('employee')->findOrFail($id);
        $authUser = Auth::user();

        // Access control
        if ($authUser->level < 2) {
            return redirect()->route('user.detail')->with('error', 'Unauthorized access.');
        }

        if ($authUser->level == 2) {
            // Check if the target user is in the same team
            if (!$authUser->employee || !$user->employee || $authUser->employee->team_id !== $user->employee->team_id) {
                return redirect()->route('admin.users')->with('error', 'You can only edit users in your team.');
            }
        }

        $teams = Team::all(); // Fetch all teams for the dropdown
        return view('pages.admin.user.user_edit', compact('user', 'teams'));
    }
    public function update(Request $request, $id)
    {
        $user = User::with('employee')->findOrFail($id);
        $authUser = Auth::user();

        // Access control
        if ($authUser->level < 2) {
            return redirect()->route('user.detail')->with('error', 'Unauthorized access.');
        }

        if ($authUser->level == 2) {
            // Check if the target user is in the same team
            if (!$authUser->employee || !$user->employee || $authUser->employee->team_id !== $user->employee->team_id) {
                return redirect()->route('admin.users')->with('error', 'You can only edit users in your team.');
            }
        }

        // Validation rules
        $rules = [
            'nrc' => 'nullable|string|max:255|unique:employees,nrc,' . ($user->employee->id ?? null),
            'address' => 'nullable|string|max:500',
        ];

        // Add rules for level 2 and 3 users
        if ($authUser->level >= 2) {
            $rules = array_merge($rules, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'employee_code' => 'required|string|max:255|unique:employees,employee_code,' . ($user->employee->id ?? null),
                'position' => 'nullable|string|max:255',
                'team_id' => 'nullable|exists:teams,id',
            ]);
        }

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update user details
        if ($authUser->level >= 2) {
            $user->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
            ]);
        }

        // Update or create employee details
        $employeeData = [
            'nrc' => $request->input('nrc'),
            'address' => $request->input('address'),
        ];

        if ($authUser->level >= 2) {
            $employeeData = array_merge($employeeData, [
                'employee_code' => $request->input('employee_code'),
                'position' => $request->input('position'),
                'team_id' => $request->input('team_id'),
            ]);
        }

        if ($user->employee) {
            $user->employee->update($employeeData);
        } else {
            $employeeData['user_id'] = $user->id;
            Employee::create($employeeData);
        }

        return redirect()->route('admin.users')->with('status', 'Profile updated successfully!');
    }
    public function delete($id)
{
    try {
        $authUser = Auth::user();
        $userToDelete = User::findOrFail($id);

        // Level 1 users cannot delete
        if ($authUser->level == 1) {
            return redirect()->back()->with('error', 'You do not have permission to delete users.');
        }

        // Level 2 users can only delete users in the same team
        if ($authUser->level == 2) {
            $authEmployee = Employee::where('user_id', $authUser->id)->first();
            $targetEmployee = Employee::where('user_id', $userToDelete->id)->first();

            // Check if both users have employee records and are in the same team
            if (!$authEmployee || !$targetEmployee || $authEmployee->team_id !== $targetEmployee->team_id) {
                return redirect()->back()->with('error', 'You can only delete users in your own team.');
            }
        }

        // Level 3 users can delete any user (no additional checks needed)

        // Delete the user (employee record will be deleted automatically due to cascade)
        $userToDelete->delete();

        return redirect()->route('admin.users.index')->with('status', 'User deleted successfully.');
    } catch (\Exception $e) {
        Log::error('Error deleting user: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to delete user. Please try again.');
    }
}
}
