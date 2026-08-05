<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function create()
    {
        if (! User::query()->exists()) {
            return redirect()->route('register');
        }

        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login', [
            'userLockedUntil' => User::query()->value('locked_until'),
        ]);
    }

    public function store(LoginRequest $request)
    {
        $user = User::query()->first();

        if (! $user) {
            return redirect()->route('register');
        }

        if ($user->locked_until && $user->locked_until->isFuture()) {
            return back()->withErrors([
                'mpin' => 'Account locked until '.$user->locked_until->format('h:i A'),
            ]);
        }

        if (! Hash::check($request->string('mpin')->toString(), $user->mpin)) {
            $user->failed_attempts = ($user->failed_attempts ?? 0) + 1;

            if ($user->failed_attempts >= 5) {
                $user->locked_until = now()->addMinutes(5);
            }

            $user->save();

            return back()->withErrors([
                'mpin' => $user->locked_until
                    ? 'Too many attempts. Try again at '.$user->locked_until->format('h:i A')
                    : 'Invalid MPIN. Please try again.',
            ]);
        }

        $user->forceFill([
            'failed_attempts' => 0,
            'locked_until' => null,
            'last_login_at' => now(),
        ])->save();

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('last_activity_at', now()->timestamp);

        return redirect()->route('dashboard');
    }
}
