<?php

namespace App\Http\Controllers\auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
{
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:8',
    ]);

    // Attempt login
    if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
        // Regenerate session to prevent session fixation
        $request->session()->regenerate();

        return redirect()->intended(route('user.dashboard'));
    }

    return back()->withErrors(['email' => 'Invalid email or password.']);
}
}
