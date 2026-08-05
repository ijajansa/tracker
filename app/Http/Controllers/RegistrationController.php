<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function create()
    {
        if (User::query()->exists()) {
            return redirect()->route('login');
        }

        return view('auth.register');
    }

    public function store(RegisterRequest $request)
    {
        if (User::query()->exists()) {
            return redirect()->route('login');
        }

        $user = User::create([
            'full_name' => $request->string('full_name')->toString(),
            'mpin' => $request->string('mpin')->toString(),
            'failed_attempts' => 0,
            'locked_until' => null,
            'last_login_at' => now(),
        ]);

        UserSetting::create([
            'user_id' => $user->id,
            'currency' => 'INR',
            'currency_symbol' => '₹',
            'theme' => 'light',
            'auto_lock_minutes' => 5,
        ]);

        Auth::login($user);
        request()->session()->regenerate();
        request()->session()->put('last_activity_at', now()->timestamp);

        return redirect()->route('opening-balance');
    }
}
