<?php

namespace App\Http\Controllers;

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
        return view('pages.admin.clients', compact('clients'));
    }
}
