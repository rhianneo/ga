<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param mixed ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Get the authenticated user
        $user = Auth::user();

        // If no user is authenticated, redirect to login
        if (!$user) {
            \Log::info('User is not authenticated. Redirecting to login.');
            return redirect()->route('login');
        }

        // Trim user role and allowed roles to remove any whitespace
        $userRole = trim($user->role);
        $roles = array_map('trim', $roles); // Trim the allowed roles

        // Log current and allowed roles for debugging
        \Log::info('Current User Role: ' . $userRole);
        \Log::info('Allowed Roles: ' . implode(', ', $roles));

        // Log the role comparison
        \Log::info('Comparing Role: ' . $userRole);

        // Check if the user role matches any of the allowed roles
        if (!in_array($userRole, $roles)) {
            \Log::info('Role mismatch. Access denied for role: ' . $userRole);
            abort(403, 'Unauthorized action. You do not have permission to access this resource.');
        }

        // Proceed to the next middleware/request handler if the role matches
        return $next($request);
    }
}
