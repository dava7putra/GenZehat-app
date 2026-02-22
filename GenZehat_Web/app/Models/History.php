<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     * (Opsional jika nama tabel sudah 'histories')
     */
    protected $table = 'histories';

    /**
     * Kolom-kolom yang boleh diisi (Mass Assignment).
     * Sesuai dengan kolom yang kita pisah-pisah tadi.
     */
    protected $fillable = [
        'user_id',
        'minggu_ke',
        'latihan_selesai',
        'total_latihan',
        'persentase',
        'tanggal_latihan',
    ];

    protected $casts = [
        'minggu_ke' => 'integer',
        'latihan_selesai' => 'integer',
        'total_latihan' => 'integer',
        'persentase' => 'integer',
        'tanggal_latihan' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRingkasanLatihanAttribute()
    {
        return "{$this->latihan_selesai} Selesai dari {$this->total_latihan} Latihan";
    }
}