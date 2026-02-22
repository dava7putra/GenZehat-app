<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FitnessController extends Controller
{
    // 1. Menampilkan Halaman Utama
    public function index() {
    $user = auth()->user(); 
    if (!$user) return view('fitness'); 

    $histories = $user->histories()->orderBy('id', 'desc')->get();

    // [PERBAIKAN 1] Gunakan whereIn untuk filter STRICT hanya 'completed' atau 'missed'
    // Ini akan membuang status 'unchecked' yang menyebabkan tombol jadi hitam
    $dailyProgress = $user->dailyProgress()
        ->whereIn('status', ['completed', 'missed']) 
        ->pluck('status', 'day_name')        
        ->toArray();

    return view('fitness', compact('histories', 'dailyProgress'));
}

    // 2. Handle Login
    // Ganti fungsi login di FitnessController.php menjadi seperti ini:
    public function login(Request $request) {
    $credentials = $request->validate([
        'username' => 'required',
        'password' => 'required'
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // CEK: Jika permintaan datang dari API/Android
        if ($request->expectsJson() || $request->is('api/*')) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'token' => $token,
                'message' => 'Login Berhasil'
            ]);
        }

        // Jika dari Browser (Web) biasa
        $request->session()->regenerate();
        return redirect()->intended('/');
    }

    // Jika gagal
    if ($request->expectsJson() || $request->is('api/*')) {
        return response()->json(['status' => 'error', 'message' => 'Gagal'], 401);
    }
    return back()->with('error', 'Login gagal!');
}

    // 3. Handle Register
    public function register(Request $request) {
        $request->validate([
            'reg_username' => 'required|unique:users,username',
            'reg_password' => 'required'
        ]);

        User::create([
            'username' => $request->reg_username,
            'password' => Hash::make($request->reg_password),
        ]);
    
        return back()->with('success', 'Registrasi berhasil, silakan login!');
    }

    // 4. Handle Logout
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // --- AJAX HANDLERS (Database) ---

    // Simpan Ceklis Harian (Satu per satu)
    public function saveProgress(Request $request) {
    $user = auth()->user();
    $status = $request->status;

    // [PERBAIKAN 2] Hapus DULU semua data hari itu (mencegah duplikat row)
    $user->dailyProgress()->where('day_name', $request->key)->delete();

    // Jika statusnya bukan 'unchecked', baru kita simpan data baru
    if ($status !== 'unchecked') {
        $user->dailyProgress()->create([
            'day_name' => $request->key,
            'status' => $status,
            'is_completed' => ($status === 'completed')
        ]);
    }

    return response()->json(['status' => 'saved', 'saved_status' => $status]);
}

    // Simpan Mingguan & Reset (Tombol Save & Exit)
    public function archiveWeek(Request $request) {
        $user = auth()->user();

        // 1. Simpan ke History
        $user->histories()->create([
            'minggu_ke' => $user->histories()->count() + 1,
            'latihan_selesai' => (int)$request->completed_count,
            'total_latihan' => 7,
            'persentase' => round(((int)$request->completed_count / 7) * 100),
        ]);
        
        // 2. HAPUS data harian agar kembali bersih (Reset)
        $user->dailyProgress()->delete(); 

        // 3. Kirim respon sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Minggu berhasil diarsipkan dan direset',
            'history' => $user->histories
        ]);
    }

    // Fitur Cek History User Lain
    public function checkHistory(Request $request) {
        $username = $request->query('username');
        $user = User::with('histories')->where('username', $username)->first();

        if ($user) {
            return response()->json([
                'status' => 'success',
                'username' => $user->username,
                'level' => $user->level ?? 'Member GenZehat',
                // PERBAIKAN DI SINI: Menambahkan "{" setelah function ($h)
                'history' => $user->histories->map(function ($h) {
                    return [
                        'week' => "Minggu ke-" . $h->minggu_ke,
                        'detail' => $h->latihan_selesai . " Selesai dari " . $h->total_latihan . " Latihan",
                        'progress' => $h->persentase,
                        'date' => $h->created_at->format('d M Y')
                    ];
                })
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'User tidak ditemukan'], 404);
    }

    // Fitur Reset Semua History (Hapus Permanen)
    public function resetHistory() {
        $user = auth()->user();
        $user->histories()->delete();
        return response()->json(['status' => 'success']);
    }
    public function getHistory(Request $request) {
        $user = Auth::user();

        // Cek apakah user valid
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Ambil data history milik user tersebut
        // Kita urutkan dari yang terbaru (desc)
        $history = $user->histories()->orderBy('id', 'desc')->get();

        // PENTING:
        // Kembalikan langsung array-nya tanpa bungkusan "status" atau "data"
        // Agar Android langsung membacanya sebagai List [...]
        return response()->json($history);
    }
}
