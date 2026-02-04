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
        return view('fitness');
    }

    // 2. Handle Login
    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->with('error', 'Login gagal, cek username/password');
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
            'progress_data' => [], // Otomatis jadi JSON kosong
            'history_data' => []   // Otomatis jadi JSON kosong
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

    // --- AJAX HANDLERS ---

    public function saveProgress(Request $request) {
        $user = Auth::user();
        // Data dikirim sebagai JSON string dari JS, kita decode dulu atau terima raw
        // Karena di model sudah dicasting 'array', kita bisa simpan langsung
        $progressData = json_decode($request->data, true); 

        $user->progress_data = $progressData;
        $user->save();

        return "Saved";
    }

    public function archiveWeek(Request $request) {
        $user = Auth::user();
        $history = $user->history_data ?? [];

        $weekNum = count($history) + 1;
        $newLog = [
            "title" => "Minggu ke-$weekNum",
            "desc" => "{$request->completed_count} Selesai dari 7 Latihan",
            "count" => (int)$request->completed_count,
            "date" => date("d M Y")
        ];

        $history[] = $newLog;

        $user->history_data = $history;
        $user->progress_data = []; // Reset progress
        $user->save();

        return response()->json($history);
    }

    public function resetHistory() {
        $user = Auth::user();
        $user->history_data = [];
        $user->save();
        return "Cleared";
    }
}
