<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Pastikan model User di-import

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request)
    {
        // 1. Validasi input dari aplikasi mobile
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Coba otentikasi pengguna
        if (Auth::attempt($credentials)) {
            // 3. Jika berhasil, ambil data user
            $user = Auth::user();
            
            // 4. Buat token baru untuk user ini
            $token = $user->createToken('auth_token')->plainTextToken;

            // 5. Kirim respon sukses ke aplikasi mobile berisi token dan data user
            return response()->json([
                'message'       => 'Login berhasil',
                'access_token'  => $token,
                'token_type'    => 'Bearer',
                'user'          => $user
            ], 200);
        }

        // 6. Jika email/password salah, kirim respon error
        return response()->json(['message' => 'Email atau password salah'], 401);
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        // Menghapus token yang sedang digunakan untuk request ini
        $request->user()->currentAccessToken()->delete();
        
        // Kirim respon sukses
        return response()->json(['message' => 'Logout berhasil'], 200);
    }
}