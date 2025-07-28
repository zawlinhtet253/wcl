<?php
namespace App\Http\Controllers;

use App\Models\Team;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index()
    {
        if(Auth::user()->level != 3) {
            abort(403, 'Unauthorized action.');
        }
        $teams = Team::all();
        return view('pages.admin.teams', compact('teams'));
    }
}