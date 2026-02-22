<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\History;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat User Bob
        $user = User::create([
            'username' => 'Bob',
            'password' => bcrypt('password123'),
            'level' => 'Member GenZehat',
        ]);

        // Mengisi Riwayat (History) untuk Bob
        $user->histories()->createMany([
            [
                'minggu_ke' => 1,
                'latihan_selesai' => 7,
                'total_latihan' => 7,
                'persentase' => 100,
            ],
            [
                'minggu_ke' => 2,
                'latihan_selesai' => 4,
                'total_latihan' => 7,
                'persentase' => 57,
            ],
        ]);
    }
}