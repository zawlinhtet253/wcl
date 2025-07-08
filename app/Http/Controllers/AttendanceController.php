<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function attendance() {
        $user = Auth::user();
        $today = now()->toDateString(); // လက်ရှိ ရက်စွဲ (ဥပမာ "2025-07-07")
        $todayAttendance = Attendance::where('employee_id', $user->id)
            ->whereDate('created_at', $today)
            ->first(); // ဒီနေ့အတွက် ပထမဆုံး record ကို ရယူမယ်

        return view('pages.user.attendance', compact('user', 'todayAttendance', 'today'));
    }
    public function store(Request $request) {
        $attendance = new Attendance();
        $attendance->name = $request->name;
        $attendance->employee_id = $request->employee_id;
        $attendance->save();

        return redirect()->route('user.attendance')->with('success', 'Attendance recorded successfully.');
    }
}