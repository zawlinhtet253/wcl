<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PageController extends Controller
{
    public function index() {
        return view('pages.login');
    }
    public function dashboard() {
        return view('pages.user.dashboard');
    }
    public function detail() {
        $user = Auth::user();
        return view('pages.user.detail', compact('user'));
    }
    
}
