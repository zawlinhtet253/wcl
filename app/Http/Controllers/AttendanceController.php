<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function attendance() {
        $authUser = Auth::user();
        $employee = Employee::where('user_id', $authUser->id)->first();
        $today = now()->timezone('Asia/Yangon')->toDateString();
        $todayAttendance = $employee ? Attendance::where('employee_id', $employee->id)
            ->whereDate('created_at', $today)
            ->first() : null;
        return view('pages.user.attendance', compact('employee' , 'todayAttendance'));
    }
    
    public function attendances()
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        $range = request()->input('date_range');
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

        // Fetch attendance data based on user level
        $query = Attendance::query()->with(['employee.user']);

        if ($user->level == 1) {
            // Level 1: Show all their own attendance records
            if ($employee) {
                $query->where('employee_id', $employee->id);
                if ($range && $start && $end) {
                    $query->whereBetween('created_at', [$start, $end]);
                }
                $attendances = $query->orderBy('created_at', 'desc')->get();
            }
        } elseif ($user->level == 2 || $user->level == 3) {
            // Level 2 (Team Lead) or Level 3 (Manager): Show team/all attendance for today or date range
            if ($range && $start && $end) {
                $query->whereBetween('created_at', [$start, $end]);
            } else {
                $query->whereDate('created_at', $today);
            }

            if ($user->level == 2 && $employee && $employee->team_id) {
                // Level 2: Show team attendance
                $query->whereHas('employee', function ($query) use ($employee) {
                    $query->where('team_id', $employee->team_id);
                });
            }
            // Level 3: Show all attendance (no additional filter needed)
            $attendances = $query->orderBy('created_at', 'desc')->get();
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
        if ($request->name == 'Leave') {
            $attendance = new Attendance();
            $attendance->name = $request->name;
            $attendance->employee_id = $request->employee_id;
            $attendance->check_in_time = now()->timezone('Asia/Yangon');
            $attendance->check_in_latitude = $request->check_in_latitude;
            $attendance->check_in_longitude = $request->check_in_longitude;
            $attendance->check_out_time = now()->timezone('Asia/Yangon');
            $attendance->check_out_latitude = $request->check_in_latitude;
            $attendance->check_out_longitude = $request->check_in_longitude;
            $attendance->save();
        } else {
            $attendance = new Attendance();
            $attendance->name = $request->name;
            $attendance->employee_id = $request->employee_id;
            $attendance->check_in_time = now()->timezone('Asia/Yangon');
            $attendance->check_in_latitude = $request->check_in_latitude;
            $attendance->check_in_longitude = $request->check_in_longitude;
            $attendance->save();
        }
        return redirect()->route('user.attendances')->with('success', 'Attendance recorded successfully.');
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

        return redirect()->route('user.attendances')->with('message', 'Attendance approved successfully.');
    }
    public function checkOut(Request $request) {
        $user = Auth::user();
        $employee = Employee::find($user->id);
        $today = now();
        $attendance = Attendance::where('employee_id' , $employee->id)
            ->whereDate('created_at' , $today)->first();
        $attendance = Attendance::findOrFail($attendance->id);
        $attendance->check_out_time = now()->timezone('Asia/Yangon');
        $attendance->check_out_latitude = $request->check_out_latitude;
        $attendance->check_out_longitude = $request->check_out_longitude;
        $attendance->save();

        return redirect()->route('user.attendances')->with('success', 'Check out successfully.');
    }
    public function autoCheckOut() {
        $today = now()->toDateString();
        $attendances = Attendance::whereDate('created_at' , $today)
            ->whereNull('check_in_time')->get();
        dd($attendances);
    }
}