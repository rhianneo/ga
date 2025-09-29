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
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $email = strtolower($request->email);
        $role = $this->assignRoleBasedOnEmail($email);

        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        event(new Registered($user));
        Auth::login($user);

        return $this->redirectBasedOnRole($role);
    }

    protected function assignRoleBasedOnEmail(string $email): string
    {
        if (str_ends_with($email, '@toyoflex.com')) {
            return 'GA Staff';
        }

        $adminEmails = [
            'takafumi.matsunaga@asahi-intecc.com',
            'kazuhiro.tsuchiya@asahi-intecc.com',
        ];

        if (in_array($email, $adminEmails)) {
            return 'Admin Expatriate';
        }

        return 'Expatriate';
    }

    protected function redirectBasedOnRole(string $role): RedirectResponse
    {
        switch ($role) {
            case 'GA Staff':
                return redirect()->route('dashboard');
            case 'Admin Expatriate':
            case 'Expatriate':
                return redirect()->route('gantt.index');
            default:
                return redirect('/');
        }
    }
}
