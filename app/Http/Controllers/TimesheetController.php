<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Timesheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class TimesheetController extends Controller
{
    // Read all timesheets

public function index()
{
    $user = Auth::user();
    $employee = Employee::where('user_id', $user->id)->first();
    $range = request()->input('date_range');
    
    if (!$employee) {
        return redirect()->route('user.dashboard')->with('error', 'Employee record not found.');
    }

    // Date range ရှိရင် start, end ခွဲမယ်
    if ($range) {
        [$start, $end] = explode(' - ', $range);
        $start = Carbon::parse($start)->startOfDay();
        $end = Carbon::parse($end)->endOfDay();
    }

    // Filter setup
    if ($user->level == 1) {
        $query = Timesheet::with(['client', 'employee.user', 'employee.team'])
            ->where('employee_id', $employee->id);
    } elseif ($user->level == 2) {
        $query = Timesheet::with(['client', 'employee.user', 'employee.team'])
            ->whereHas('employee', function ($q) use ($employee) {
                $q->where('team_id', $employee->team_id);
            });
    } elseif ($user->level == 3) {
        $query = Timesheet::with(['client', 'employee.user', 'employee.team']);
    } else {
        return redirect()->route('user.dashboard')->with('error', 'Invalid user level.');
    }

    // Date range ရှိရင် created_at နဲ့ filter
    if ($range) {
        $query->whereBetween('created_at', [$start, $end]);
    }

    $timesheets = $query->orderBy('from', 'desc')->get();

    return view('pages.user.timesheets', compact('timesheets'));
}

    // Show create form
    public function create()
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        
        if (!$employee) {
            return redirect()->route('user.dashboard')->with('error', 'Employee record not found.');
        }
        $clients = Client::where('team_id', $employee->team_id ?? null)->get();
        return view('pages.user.timesheet.addTimesheet', compact('clients'));
    }

    // Create new timesheet
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'description' => 'required|string|max:1000',
            'from' => 'required|date',
            'to' => 'required|date|after:from',
        ]);

        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        $client = Client::find($request->client_id);
        $existing = Timesheet::where('client_id', $request->client_id)
            ->where('employee_id', $employee->id)
            ->where('from', $request->from)
            ->where('to', $request->to)
            ->first();
            
        if ($existing) {
            return redirect()->route('user.timesheet')->with('error', 'Timesheet already exists for this period');
        }
        
        
        if (!$employee) {
            return redirect()->route('user.dashboard')->with('error', 'Employee record not found.');
        }
        if (!$client) {
            return redirect()->route('user.timesheet')->with('error', 'Client not found.');
        }
        if ($client->team_id !== $employee->team_id) {
            return redirect()->route('user.timesheet')->with('error', 'Unauthorized to add timesheet for this client.');
        }
        $timesheet = new Timesheet();
        $timesheet->employee_id = $employee->id;
        $timesheet->client_id = $request->client_id;
        $timesheet->description = $request->description;
        $timesheet->from = Carbon::parse($request->from);
        $timesheet->to = Carbon::parse($request->to);
        $timesheet->status = 0; // Default status to 0 (not approved)
        $timesheet->save();

        return redirect()->route('user.timesheet')->with('message', 'Timesheet added successfully');
    }

    // Read single timesheet
    public function show($id)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        
        $timesheet = Timesheet::with('client')
            ->where('id', $id)
            ->findOrFail($id);

        return view('pages.user.timesheet.viewTimesheet', compact('timesheet'));
    }

    // Show edit form
    public function edit($id) {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();

        if (!$employee) {
            return redirect()->route('user.timesheet')->with('error', 'Employee record not found.');
        }

        // Base query
        $timesheetQuery = Timesheet::with(['employee.user', 'employee.team']);

        if ($user->level == 1) {
            // Level 1: Can edit own timesheet if status = 0
            $timesheet = $timesheetQuery->where('employee_id', $employee->id)
                                        ->where('status', 0)
                                        ->findOrFail($id);
        } elseif ($user->level == 2) {
            // Level 2: Can edit team members' timesheets if status = 0
            $timesheet = $timesheetQuery->whereHas('employee', function ($q) use ($employee) {
                                            $q->where('team_id', $employee->team_id);
                                        })
                                        ->where('status', 1)
                                        ->findOrFail($id);
        } elseif ($user->level == 3) {
            // Level 3: Can edit any timesheet that have approved.$
            $timesheet = $timesheetQuery->where('status', 1)->findOrFail($id);
        } else {
            return redirect()->route('user.timesheet')->with('error', 'Unauthorized access.');
        }

        $clients = Client::orderBy('name')->get();
        return view('pages.user.timesheet.editTimesheet', compact('timesheet', 'clients'));
    }

    // Update timesheet
    public function update(Request $request, $id) {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'description' => 'required|string|max:1000',
            'from' => 'required|date',
            'to' => 'required|date|after:from',
        ]);

        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();

        if (!$employee) {
            return redirect()->route('user.timesheet')->with('error', 'Employee record not found.');
        }

        // Base query
        $timesheetQuery = Timesheet::with(['employee.user', 'employee.team']);

        if ($user->level == 1) {
            // Level 1: Update own timesheet if status = 0
            $timesheet = $timesheetQuery->where('employee_id', $employee->id)
                                        ->where('status', 0)
                                        ->findOrFail($id);
        } elseif ($user->level == 2) {
            // Level 2: Update team members' timesheets if status = 0
            $timesheet = $timesheetQuery->whereHas('employee', function ($q) use ($employee) {
                                            $q->where('team_id', $employee->team_id);
                                        })
                                        ->where('status', 1)
                                        ->findOrFail($id);
        } elseif ($user->level == 3) {
            // Level 3: Update any timesheet
            $timesheet = $timesheetQuery->where('status', 1)->findOrFail($id);
        } else {
            return redirect()->route('user.timesheet')->with('error', 'Unauthorized access.');
        }

        $timesheet->update([
            'client_id' => $request->client_id,
            'description' => $request->description,
            'from' => $request->from,
            'to' => $request->to,
        ]);

        return redirect()->route('user.timesheet')->with('message', 'Timesheet updated successfully');
    }


    // Delete timesheet
    public function destroy($id)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        $timesheet = Timesheet::where('employee_id', $employee->id)->findOrFail($id);
        if ($timesheet->status == 1 && $user->level == 3) {
            $timesheet->delete();
        } elseif ($timesheet->status == 0 && $user->level == 1) {
            $timesheet->delete();
        } elseif ($timesheet->status == 1 && $user->level == 2 && $timesheet->team_id == $employee->team_id) {
            $timesheet->delete();
        } else {
            return redirect()->route('user.timesheet')->with('error', 'Unauthorized access.');
        }

        return redirect()->route('user.timesheet')->with('error', 'Timesheet deleted successfully');
    }

    public function approve(Timesheet $timesheet, Request $request) {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        
        if(!$employee) {
            return redirect()->route('user.timesheet')->with('message' , 'No employee found');
        }
        //&& $timesheet->employee->team_id !== $employee->team_id 
        if($user->level < 2 ) {
            return redirect()->route('user.timesheet')->with('error' , 'Unauthorized.');
        }
        if ($timesheet->status !== 0) {
        return redirect()->route('user.timesheet')->with('error', 'This timesheet has already been approved.');
        }

        $timesheet->status = true;
        $timesheet->approved_by = $user->id;
        $timesheet->save();

        return redirect()->route('user.timesheet')->with('message', 'Timesheet approved successfully.');
    }
}