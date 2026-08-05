<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUnlockedForLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::query()->first();

        if ($user && $user->locked_until && $user->locked_until->isFuture()) {
            return back()->withErrors([
                'mpin' => 'Account locked until '.$user->locked_until->format('h:i A'),
            ]);
        }

        return $next($request);
    }
}
