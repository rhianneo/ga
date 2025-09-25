<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $userRole = trim($user->role);
        $roles = array_map('trim', $roles);

        if (!in_array($userRole, $roles)) {
            abort(403, 'Unauthorized: You do not have permission to access this page.');
        }

        return $next($request);
    }
}
