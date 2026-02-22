<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MobileController extends Controller
{
    // Fungsi untuk Android melihat History berdasarkan Username
    public function getUserHistory(Request $request) {
        // Cek apakah username dikirim?
        if (!$request->username) {
            return response()->json(['status' => 'error', 'message' => 'Username wajib diisi!'], 400);
        }

        // Cari User di Database
        $user = User::where('username', $request->username)->first();

        // Jika user tidak ditemukan
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User tidak ditemukan'], 404);
        }

        // Jika ditemukan, kirim datanya
        return response()->json([
            'status' => 'success',
            'username' => $user->username,
            'level' => 'Member GenZehat', // Bisa diganti logic level
            // Kita ambil history_data (JSON) yang sudah di-cast jadi Array
            'history' => $user->history_data ?? [], 
            'bmi_info' => [
                'berat' => $user->weight ?? 0,
                'tinggi' => $user->height ?? 0
            ]
        ], 200);
    }
}
