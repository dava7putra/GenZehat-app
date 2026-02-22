<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Menghapus kolom yang sudah tidak digunakan
        $table->dropColumn(['progress_data', 'history_data']);
        
        // Menambahkan kolom level jika sebelumnya belum ada di tabel users
        if (!Schema::hasColumn('users', 'level')) {
            $table->string('level')->default('Member GenZehat')->after('password');
        }
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->json('progress_data')->nullable();
        $table->json('history_data')->nullable();
        $table->dropColumn('level');
    });
}
};
