<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Fungsi Menampilkan Login
     */
    public function show() {
        return view('auth.login');
    }

    /**
     * Fungsi Validasi Data Login
     */
    public function store(Request $request) {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah',
        ])->onlyInput('email');
    }

    /**
     * Fungsi Logout
     */
    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
