<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return view('auth.login'); // Return the login view
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Authenticate the user based on the provided credentials
        $request->authenticate();

        // Regenerate session to prevent session fixation attacks
        $request->session()->regenerate();

        $user = $request->user();

        // Log user data after authentication
        \Log::info("User logged in", ['user' => $user]);

        // Redirect based on the user's role
        switch ($user->role) {
            case 'GA Staff':
                return redirect()->route('dashboard');  // Redirect GA Staff to dashboard
            case 'Admin Expatriate':
                return redirect()->route('gantt.index');  // Redirect Admin Expatriate to Gantt chart
            case 'Expatriate':
                return redirect()->route('gantt.index');  // Redirect Expatriate to their own Gantt chart
            default:
                // Log a warning if the user's role is unrecognized
                \Log::warning("Unrecognized role for user: " . $user->role);
                return redirect()->route('home');  // Redirect to 'home' if the role is unrecognized
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
        // Log out the user and invalidate the session
        Auth::guard('web')->logout();

        // Invalidate the session and regenerate the CSRF token for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Optionally, log the user out and notify
        \Log::info("User logged out successfully.");

        // Redirect to the login page after logout
        return redirect()->route('login');
    }
}
