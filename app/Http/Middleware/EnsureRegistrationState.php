<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRegistrationState
{
    public function handle(Request $request, Closure $next, string $mode): Response
    {
        $userExists = User::query()->exists();

        if ($mode === 'register' && $userExists) {
            return redirect()->route('login');
        }

        if ($mode === 'login' && ! $userExists) {
            return redirect()->route('register');
        }

        return $next($request);
    }
}
