<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_attendance_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique(); // Kode acak untuk di-QR
            $table->enum('type', ['in', 'out']); // Untuk masuk atau pulang
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_attendance_tokens');
    }
};