<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('extracurricular_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracurricular_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->integer('score')->nullable(); // Nilai ekskul (0-100)
            $table->text('notes')->nullable(); // Catatan pembina
            $table->timestamps();

            // Satu siswa hanya bisa mendaftar 1x di ekskul yang sama
            $table->unique(['extracurricular_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extracurricular_members');
    }
};