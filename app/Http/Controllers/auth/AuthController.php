<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Log;
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
            $request->session()->regenerate();
            $user = Auth::user();

            Log::info('User ID: ' . $user->id . ', 2FA Secret: ' . ($user->google2fa_secret ?? 'Not set'));

            // Redirect to 2FA verification
            $request->session()->put('2fa_user_id', $user->id);
            Log::info('Redirecting to 2FA verification');
            return redirect()->route('2fa.verify');
        }

        // Invalid login attempt
        Log::warning('Invalid login attempt for email: ' . $validated['email']);
        return back()->withErrors(['email' => 'Invalid email or password.'])->withInput($request->except('password'));
    }

    public function show2faForm(Request $request)
    {
        $user = Auth::user();
        $google2fa = app('pragmarx.google2fa');

        // If user doesn't have a 2FA secret, generate one and show QR code
        if (!$user->google2fa_secret) {
            $secret = $google2fa->generateSecretKey();
            $request->session()->put('2fa_secret', $secret);

            $QR_Image = $google2fa->getQRCodeInline(
                config('app.name'),
                $user->email,
                $secret
            );

            return view('google2fa.verify', [
                'QR_Image' => $QR_Image,
                'secret' => $secret,
                'needs_setup' => true,
            ]);
        }

        // If 2FA secret exists, show verification form only
        return view('google2fa.verify', [
            'needs_setup' => false,
        ]);
    }

    public function verify2fa(Request $request)
    {
        $request->validate(['code' => 'required|numeric']);

        $userId = $request->session()->get('2fa_user_id');
        $user = User::find($userId);

        if (!$user) {
            Log::error('2FA: User not found for ID: ' . $userId);
            Auth::logout();
            $request->session()->invalidate();
            return redirect()->route('login')->withErrors(['error' => 'User session invalid. Please login again.']);
        }

        $google2fa = app('pragmarx.google2fa');

        if (!$user->google2fa_secret) {
            $secret = $request->session()->get('2fa_secret');
            if ($google2fa->verifyKey($secret, $request->code)) {
                $user->update(['google2fa_secret' => $secret]);
                $request->session()->forget('2fa_secret');
                $request->session()->put('2fa_verified', true);
                Log::info('2FA enabled for user ID: ' . $user->id);
            } else {
                Log::warning('Invalid 2FA code during setup for user ID: ' . $userId);
                return back()->withErrors(['code' => 'Invalid 2FA code.']);
            }
        } else {
            if ($google2fa->verifyKey($user->google2fa_secret, $request->code)) {
                $request->session()->forget('2fa_user_id');
                $request->session()->put('2fa_verified', true);
                Log::info('2FA verified for user ID: ' . $user->id);
            } else {
                Log::warning('Invalid 2FA code for user ID: ' . $userId);
                return back()->withErrors(['code' => 'Invalid 2FA code.']);
            }
        }

        $today = now()->toDateString();
        $todayAttendance = Attendance::where('employee_id', $user->id)
            ->whereDate('created_at', $today)
            ->first();

        Log::info('Attendance Check: ' . ($todayAttendance ? 'Found' : 'Not found'));

        if ($todayAttendance) {
            Log::info('Redirecting to dashboard after 2FA');
            return redirect()->intended(route('user.dashboard'));
        } else {
            Log::info('Redirecting to attendance after 2FA');
            return redirect()->intended(route('user.attendance'));
        }
    }

    public function enable2fa(Request $request)
    {
        $user = Auth::user();
        $google2fa = app('pragmarx.google2fa');
        $secret = $google2fa->generateSecretKey();

        $request->session()->put('2fa_secret', $secret);

        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $secret
        );

        return view('google2fa.enable', [
            'QR_Image' => $QR_Image,
            'secret' => $secret,
        ]);
    }

    public function confirm2fa(Request $request)
    {
        $request->validate(['code' => 'required|numeric']);
        $user = Auth::user();
        $user = User::find($user->id);
        $google2fa = app('pragmarx.google2fa');

        $secret = $request->session()->get('2fa_secret');
        if ($google2fa->verifyKey($secret, $request->code)) {
            $user->update(['google2fa_secret' => $secret]);
            $request->session()->forget('2fa_secret');
            $request->session()->put('2fa_verified', true);

            $today = now()->toDateString();
            $todayAttendance = Attendance::where('employee_id', $user->id)
                ->whereDate('created_at', $today)
                ->first();

            Log::info('2FA enabled, Attendance Check: ' . ($todayAttendance ? 'Found' : 'Not found'));

            if ($todayAttendance) {
                Log::info('Redirecting to dashboard after enabling 2FA');
                return redirect()->route('user.dashboard')->with('success', '2FA enabled successfully.');
            } else {
                Log::info('Redirecting to attendance after enabling 2FA');
                return redirect()->route('user.attendance')->with('success', '2FA enabled successfully.');
            }
        }

        Log::warning('Invalid 2FA code for user ID: ' . $user->id);
        return back()->withErrors(['code' => 'Invalid 2FA code.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('index');
    }
}