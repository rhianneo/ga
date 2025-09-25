<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the registration form data
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Normalize the email to lowercase
        $email = strtolower($request->email);

        // Assign role based on email domain or specific user emails
        $role = $this->assignRoleBasedOnEmail($email);

        // Create the user record
        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        // Fire the Registered event
        event(new Registered($user));

        // Log the user in
        Auth::login($user);

        // Redirect based on user role
        return $this->redirectBasedOnRole($role);
    }

    /**
     * Assign role based on the user's email domain or a predefined list of emails.
     *
     * @param string $email
     * @return string
     */
    protected function assignRoleBasedOnEmail(string $email): string
    {
        // Check for Toyoflex email domain
        if (str_ends_with($email, '@toyoflex.com')) {
            return 'GA Staff'; // GA Staff role for Toyoflex email domain
        }

        // Check for specific admin expatriate emails
        $adminEmails = [
            'takafumi.matsunaga@asahi-intecc.com',
            'kazuhiro.tsuchiya@asahi-intecc.com',
            'jane.doe@asahi-intecc.com',
        ];

        if (in_array($email, $adminEmails)) {
            return 'Admin Expatriate'; // Specific emails for Admin Expatriate role
        }

        // Default role for all other users
        return 'Expatriate';
    }

    /**
     * Redirect the user based on their role after registration.
     *
     * @param string $role
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectBasedOnRole(string $role): RedirectResponse
    {
        // Handle redirection based on the user's role
        switch ($role) {
            case 'GA Staff':
                return redirect()->route('dashboard'); // Redirect to the dashboard for GA Staff
            case 'Admin Expatriate':
            case 'Expatriate':
                return redirect()->route('gantt'); // Redirect to the Gantt chart page for Admin Expatriates and Expats
            default:
                return redirect('/'); // Default redirection for other roles
        }
    }
}
