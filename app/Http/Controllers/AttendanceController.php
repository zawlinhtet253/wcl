<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Timesheet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function attendances()
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        $range = request()->input('date_range');

        // Set today's date in Asia/Yangon timezone
        $today = now()->timezone('Asia/Yangon')->toDateString();

        // Check if user has submitted attendance today
        $todayAttendance = $employee ? Attendance::where('employee_id', $employee->id)
            ->whereDate('created_at', $today)
            ->first() : null;

        // Initialize attendances as empty collection
        $attendances = collect();

        // Parse date range if provided
        $start = null;
        $end = null;
        if ($range) {
            try {
                [$start, $end] = explode(' - ', $range);
                $start = Carbon::parse($start)->startOfDay();
                $end = Carbon::parse($end)->endOfDay();
            } catch (\Exception $e) {
                // Handle invalid date range format
                return view('pages.user.attendances', compact('user', 'employee', 'todayAttendance', 'attendances'));
            }
        }

        // Fetch team/all attendance data only if user has submitted their own attendance
        if ($todayAttendance && ($user->level == 2 || $user->level == 3)) {
            $query = Attendance::query()->with(['employee.user']);

            if ($range && $start && $end) {
                // Use whereBetween for date range filtering
                $query->whereBetween('created_at', [$start, $end]);
            } else {
                // Default to today's date if no range provided
                $query->whereDate('created_at', $today);
            }

            switch ($user->level) {
                case 2:
                    // Team Lead - show team attendance
                    if ($employee && $employee->team_id) {
                        $attendances = $query->whereHas('employee', function ($query) use ($employee) {
                            $query->where('team_id', $employee->team_id);
                        })->get();
                    }
                    break;

                case 3:
                    // Manager - show all attendance
                    $attendances = $query->get();
                    break;
            }
        }

        return view('pages.user.attendances', compact('user', 'employee', 'todayAttendance', 'attendances'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|in:Work From Home,In-Office,Client Site,Leave',
            'employee_id' => 'required|exists:employees,id',
        ]);

        // Check if attendance already exists for today
        $today = now()->timezone('Asia/Yangon')->toDateString();
        $existingAttendance = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('created_at', $today)
            ->first();

        if ($existingAttendance) {
            return redirect()->route('user.attendance')->with('error', 'Attendance already recorded for today.');
        }

        $attendance = new Attendance();
        $attendance->name = $request->name;
        $attendance->employee_id = $request->employee_id;
        $attendance->save();

        return redirect()->route('user.attendance')->with('success', 'Attendance recorded successfully.');
    }

    public function approve(Attendance $attendance)
    {

        $user = Auth::user();

        // တကယ်လို့ attendance က အရင်ကတည်းက approved ဖြစ်နေရင်
        if ($attendance->status !== 0) {
            return redirect()->route('user.attendance')->with('error', 'Attendance already approved.');
        }

        // Level 1 ဆိုရင် ဘာမှ approve လုပ်လို့မရဘူး
        if ($user->level < 2) {
            return redirect()->route('user.attendance')->with('error', 'Unauthorized.');
        }

        // Level 2 ဆိုရင် ကိုယ့်ကိုယ်ကိုယ် သို့မဟုတ် ကိုယ့် team ထဲကသူတွေကိုသာ approve လုပ်လို့ရမယ်
        if ($user->level === 2 && $attendance->employee->team->id !== $user->employee->team->id && $attendance->employee->id !== $user->employee->id) {
            return redirect()->route('user.attendance')->with('error', 'Unauthorized.');
        }

        // Level 3 ဆိုရင် အကုန်လုံး approve လုပ်လို့ရတယ် (ထပ်စစ်ဆေးစရာမလို�)

        // Approve လုပ်တဲ့ logic
        $attendance->status = true;
        $attendance->approved_by = $user->id;
        $attendance->save();

        return redirect()->route('user.attendance')->with('message', 'Attendance approved successfully.');
    }
}