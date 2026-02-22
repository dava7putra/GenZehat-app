<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('histories', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->integer('minggu_ke');       // Kolom yang error tadi
    $table->integer('latihan_selesai'); 
    $table->integer('total_latihan');   
    $table->integer('persentase');      
    $table->timestamps();
});
}
};