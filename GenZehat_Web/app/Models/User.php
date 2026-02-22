<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Sesuaikan $fillable dengan kolom asli di tabel SQL.
     * Kita hapus progress_data dan history_data karena sekarang pakai tabel terpisah.
     */
    protected $fillable = [
    'username',
    'password',
    'level', // Tambahkan ini
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
    // Baris progress_data dan history_data SUDAH DIHAPUS
    ];
    /**
     * RELASI: Ini adalah bagian terpenting!
     * Menghubungkan User ke tabel Histories.
     * Satu User bisa memiliki banyak data History.
     */
    public function histories()
    {
        return $this->hasMany(History::class);
    }

    public function dailyProgress()
    {
    return $this->hasMany(DailyProgress::class);
    }
}