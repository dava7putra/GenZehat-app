<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FitnessController; // Pastikan ini benar
// use App\Http\Controllers\MobileController; // Tambahkan ini jika file-nya ada

// JALUR UMUM
// Gunakan FitnessController karena fungsi login ada di sana
Route::post('/login', [FitnessController::class, 'login']);

// Jika fungsi checkHistory ada di FitnessController (sesuai file yang Anda upload)
Route::middleware('auth:sanctum')->group(function () {
    // Route untuk Android mengambil history
    Route::get('/history', [FitnessController::class, 'getHistory']);

    Route::post('/logout', [FitnessController::class, 'logout']);
});