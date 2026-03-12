<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MagangLoginController extends Controller
{
    /**
     * Show the magang login form.
     */
    public function showLoginForm()
    {
        return view('auth.magang-login');
    }

    /**
     * Handle magang login.
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

            // Check if user has magang role
            if ($user->role !== 'magang') {
                Auth::logout();
                throw ValidationException::withMessages([
                    'username' => 'Anda tidak memiliki akses ke halaman magang.',
                ]);
            }

            return redirect()->intended(route('magang.attendance'));
        }

        throw ValidationException::withMessages([
            'username' => 'Username atau password salah.',
        ]);
    }
}