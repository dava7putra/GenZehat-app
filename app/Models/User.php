<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // <-- Penting untuk API nanti

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Daftar kolom yang BOLEH diisi secara massal (User::create)
     */
    protected $fillable = [
        'username',      // <--- Pastikan ini ada!
        'password',      // <--- Pastikan ini ada!
        'progress_data', // <--- Agar data JSON bisa disimpan
        'history_data',  // <--- Agar data JSON bisa disimpan
    ];

    /**
     * Kolom yang harus disembunyikan saat data dikirim (misal lewat API)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Mengubah format data otomatis (Casting)
     * Ini PENTING agar 'progress_data' otomatis jadi Array saat diambil,
     * dan jadi JSON saat disimpan ke database.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'progress_data' => 'array', // <--- Wajib ada
        'history_data' => 'array',  // <--- Wajib ada
    ];
}
