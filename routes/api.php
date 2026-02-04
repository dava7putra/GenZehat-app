<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FitnessApiController;

// JALUR UMUM (Bisa diakses siapa saja)
Route::post('/login', [AuthController::class, 'login']);

// JALUR KHUSUS (Harus punya Token / Login dulu)
Route::middleware('auth:sanctum')->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Fitur Fitness
    Route::get('/get-data', [FitnessApiController::class, 'getData']);
    Route::post('/save-progress', [FitnessApiController::class, 'saveProgress']);
});