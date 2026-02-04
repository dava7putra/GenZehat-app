<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FitnessController;

// Halaman Utama
Route::get('/', [FitnessController::class, 'index'])->name('home');

// Auth Routes
Route::post('/login', [FitnessController::class, 'login'])->name('login');
Route::post('/register', [FitnessController::class, 'register'])->name('register');
Route::get('/logout', [FitnessController::class, 'logout'])->name('logout');

// AJAX Routes (Hanya bisa diakses jika sudah login)
Route::middleware('auth')->group(function () {
    Route::post('/save-progress', [FitnessController::class, 'saveProgress']);
    Route::post('/archive-week', [FitnessController::class, 'archiveWeek']);
    Route::post('/reset-history', [FitnessController::class, 'resetHistory']);
});