<?php

namespace App\Http\Controllers\auth;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Attempt login
        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            // Regenerate session to prevent session fixation
            $request->session()->regenerate();
            $user = Auth::user();
            $today = now()->toDateString(); // လက်ရှိ ရက်စွဲ (ဥပမာ "2025-07-07")
            $todayAttendance = Attendance::where('employee_id', $user->id)
                ->whereDate('created_at', $today)
                ->first(); // ဒီနေ့အတွက် ပထမဆုံး record ကို ရယူမယ်

            // Check if todayAttendance exists
            if ($todayAttendance) {
                return redirect()->intended(route('user.dashboard'));
            } else {
                return redirect()->intended(route('user.attendance'));
            }
        }

        return back()->withErrors(['email' => 'Invalid email or password.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('user.attendance');
    }
}
