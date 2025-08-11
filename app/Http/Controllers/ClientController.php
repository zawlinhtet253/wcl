<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Level 1 ဆိုရင် client စာရင်းကို ကြည့်ခွင့်မရှိဘူး
        if ($user->level < 2) {
            return redirect()->route('user.timesheet')->with('error', 'Unauthorized.');
        }

        // Client စာရင်းကို ယူဖို့ query
        $query = Client::with('team');

        // Level 2 ဆိုရင် ကိုယ့် team ထဲက client တွေကိုသာ ယူမယ်
        if ($user->level === 2) {
            $query->where('team_id', $user->employee->team_id);
        }

        // Level 3 ဆိုရင် အကုန်လုံး client တွေကို ယူမယ် (ထပ်စစ်ဆေးစရာမလို�)
        $clients = $query->get();

        // Client စာရင်းကို view ထဲသို့ ပို့ပေးမယ်

        return view('pages.admin.client.clients', compact('clients'));
    }
    public function create() {
        $user = Auth::user();
        $teams = Team::all();
        $employeeTeamId = $user->employee->team_id ?? null;
        $employeeTeamName = $user->employee->team->name ?? null;
        // Level 1 ဆိုရင် client ဖန်တီးခွင့်မရှိဘူး
        if ($user->level < 2) {
            return redirect()->route('user.timesheet')->with('error', 'Unauthorized.');
        }
        // Level 2 ဆိုရင် ကိုယ့် team ထဲက client တွေကိုသာ ဖန်တီးခွင့်ရှိတယ်
        if ($user->level === 2) {
            return view('pages.admin.client.create' , compact('teams' , 'employeeTeamId' , 'employeeTeamName'));
        }
        // Level 3 ဆိုရင် အကုန်လုံး client တွေကို ဖန်တီးခွင့်ရှိတယ်
        return view('pages.admin.client.create' , compact('teams' , 'employeeTeamId'));
    
    } 
    public function store(Request $request) {
        $user = Auth::user();
        // Level 1 ဆိုရင် client ဖန်တီးခွင့်မရှိဘူး
        if ($user->level < 2) {
            return redirect()->route('user.timesheet')->with('error', 'Unauthorized.');
        }

        // Level 2 ဆိုရင် ကိုယ့် team ထဲက client တွေကိုသာ ဖန်တီးခွင့်ရှိတယ်
        if ($user->level === 2 && $request->team_id != $user->employee->team_id) {
            return redirect()->route('admin.clients')->with('error', 'Unauthorized.');
        }

        // Client ဖန်တီး request ကို validate လုပ်မယ်
        $request->validate([
            'name' => 'required|string|max:255',
            'industry_type' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:clients,code',
            'email' => 'required|email|max:255|unique:clients,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'team_id' => 'required|exists:teams,id',
        ]);

        // Client ဖန်တီးမယ်
        $client = Client::create([
            'name' => $request->name,
            'industry_type' => $request->industry_type,
            'code' => $request->code,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'team_id' => $request->team_id,
        ]);

        // Client ဖန်တီးပြီးနောက် redirect ပြန်မယ်
        return redirect()->route('admin.clients')->with('message', 'Client created successfully.');
    }
}
