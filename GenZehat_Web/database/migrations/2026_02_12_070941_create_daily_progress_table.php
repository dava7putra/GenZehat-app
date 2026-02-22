<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::create('daily_progress', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('day_name'); // 'Senin', 'Selasa', dst.
        $table->boolean('is_completed')->default(false);
        $table->string('status')->default('unchecked');
        $table->timestamps();
    });
}
};
