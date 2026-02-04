<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // 1. API LOGIN
    public function login(Request $request)
    {
        // Validasi input dari HP
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Cek username & password
        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json(['message' => 'Login Gagal! Cek username/password'], 401);
        }

        // Jika benar, ambil data user
        $user = User::where('username', $request->username)->firstOrFail();

        // BUAT TOKEN (Ini kunci rahasia untuk HP)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login Berhasil',
            'access_token' => $token, // Token ini nanti disimpan di HP
            'user' => $user
        ]);
    }

    // 2. API LOGOUT
    public function logout(Request $request)
    {
        // Hapus token yang sedang dipakai (agar tidak bisa dipakai lagi)
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout Berhasil']);
    }
}