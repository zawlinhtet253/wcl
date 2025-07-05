<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class PageController extends Controller
{
    public function index() {
        return view('pages.login');
    }
    public function dashboard() {
        return view('pages.user.dashboard');
    }
    public function detail($id) {
        $user = User::findOrFail($id);
        return view('pages.user.detail', compact('user'));
    }
}
