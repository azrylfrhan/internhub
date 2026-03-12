<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'regex:/^\S+$/'],
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('username', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if user has admin or mentor role
            if (!in_array($user->role, ['admin', 'mentor'])) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'username' => 'Anda tidak memiliki akses ke halaman admin.',
                ]);
            }

            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'username' => 'Username atau password salah.',
        ]);
    }
}
