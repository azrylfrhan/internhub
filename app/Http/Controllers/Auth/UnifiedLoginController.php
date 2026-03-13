<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UnifiedLoginController extends Controller
{
    /**
     * Show the unified login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login and redirect by role.
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

            if (in_array($user->role, ['admin', 'mentor'])) {
                return redirect()->intended(route('dashboard'));
            }

            if (in_array($user->role, ['magang', 'alumni'])) {
                return redirect()->intended(route('magang.attendance'));
            }

            // Unknown role — deny access
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'username' => 'Akun Anda tidak memiliki akses ke sistem ini.',
            ]);
        }

        throw ValidationException::withMessages([
            'username' => 'Username atau password salah.',
        ]);
    }
}
