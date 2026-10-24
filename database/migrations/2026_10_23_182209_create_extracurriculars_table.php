<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('extracurriculars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('advisor_id')->nullable()->constrained('users')->nullOnDelete(); // Pembina (Guru)
            $table->string('schedule_day', 20)->nullable(); // Contoh: Senin
            $table->time('schedule_time')->nullable();
            $table->boolean('is_open')->default(true); // Pendaftaran dibuka?
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extracurriculars');
    }
};