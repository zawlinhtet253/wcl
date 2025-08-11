<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TwoFactorAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !$request->session()->has('2fa_verified') && Auth::user()->google2fa_secret) {
            Log::info('2FA middleware redirecting to 2fa.verify for user ID: ' . Auth::id());
            return redirect()->route('2fa.verify');
        }
        return $next($request);
    }
}