<?php

namespace App\Http\Middleware;

use App\Models\UserSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AutoLockSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $settings = UserSetting::query()->firstOrCreate(
            ['user_id' => $user->id],
            ['currency' => 'INR', 'currency_symbol' => '₹', 'theme' => 'light', 'auto_lock_minutes' => 5]
        );

        $lastActivity = (int) $request->session()->get('last_activity_at', now()->timestamp);
        $inactiveFor = now()->timestamp - $lastActivity;
        $lockSeconds = max(1, $settings->auto_lock_minutes) * 60;

        if ($inactiveFor > $lockSeconds) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('status', 'Session locked after inactivity. Please sign in again.');
        }

        $request->session()->put('last_activity_at', now()->timestamp);

        return $next($request);
    }
}
