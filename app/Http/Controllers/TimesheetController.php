<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Timesheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TimesheetController extends Controller
{

    public function index() {
        $user = Auth::user();
        $timesheets = Timesheet::with('client', 'user')
            ->where('user_id', $user->id)
            ->orderBy('from', 'desc') // Order by most recent first
            ->get();

        $clients = Client::orderBy('name')->get();
        return view('pages.user.timesheets', compact('timesheets', 'clients'));
    }
    public function add()
    {
        
        $clients = Client::all();
        return view('pages.user.addTimesheet', compact('clients'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $timesheet = new Timesheet();
        $timesheet->client_id = $request->client_id;
        $timesheet->user_id = $user->id;
        $timesheet->description = $request->description;
        $timesheet->from = $request->from ;
        $timesheet->to = $request->to;
        $timesheet->save();
        return redirect()->route('user.timesheet')->with('message', 'Timesheet added successfully');
        
    }
}