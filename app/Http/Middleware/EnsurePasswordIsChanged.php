<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordIsChanged
{
    protected array $except = [
        'profile.edit',
        'profile.update',
        'password.update',
        'password.confirm',
        'verification.*',
        'logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->must_change_password && ! $request->routeIs(...$this->except)) {
            return redirect()->route('profile.edit')
                ->with('status', 'must-change-password');
        }

        return $next($request);
    }
}
