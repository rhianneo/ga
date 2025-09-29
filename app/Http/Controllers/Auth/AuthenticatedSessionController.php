<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        // Return the login view
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the credentials and authenticate the user
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($request->only('email', 'password'))) {
            // Regenerate session to prevent session fixation attacks
            $request->session()->regenerate();

            // Get the authenticated user
            $user = $request->user();

            // Log the user login activity (useful for debugging or auditing)
            Log::info('User logged in', ['user' => $user]);

            // Redirect user based on their role
            return $this->redirectToRoleBasedRoute($user);
        }

        // If authentication fails, redirect back with an error message
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Redirect the user based on their role after authentication.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToRoleBasedRoute($user): RedirectResponse
    {
        switch ($user->role) {
            case 'GA Staff':
                return redirect()->route('dashboard'); // Redirect GA Staff to the dashboard
            case 'Admin Expatriate':
                return redirect()->route('gantt.index'); // Redirect Admin Expatriate to Gantt chart
            case 'Expatriate':
                return redirect()->route('gantt.index'); // Redirect Expatriate to their Gantt chart
            default:
                Log::warning('Unrecognized role for user: ' . $user->role);
                return redirect()->route('home'); // Redirect to the home page if role is unrecognized
        }
    }

    /**
     * Log the user out and invalidate the session.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Log the user out and invalidate the session
        Auth::guard('web')->logout();

        // Invalidate the session and regenerate the CSRF token for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Optionally, log the user logout activity
        Log::info('User logged out successfully.');

        // Redirect to the login page after logout
        return redirect()->route('login');
    }
}
