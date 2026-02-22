<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyProgress extends Model
{
    use HasFactory;

    // Bagian INI yang hilang di tempat Anda
    // Tanpa ini, updateOrCreate tidak akan bisa menyimpan data 'status'
    protected $fillable = [
        'user_id', 
        'day_name', 
        'status', 
        'is_completed',
        'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
