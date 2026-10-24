<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('character_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->string('academic_year', 10); // Contoh: "2026/2027"
            $table->string('semester', 10); // "Ganjil" atau "Genap"
            $table->enum('spiritual_grade', ['A', 'B', 'C', 'D'])->nullable();
            $table->text('spiritual_notes')->nullable();
            $table->enum('social_grade', ['A', 'B', 'C', 'D'])->nullable();
            $table->text('social_notes')->nullable();
            $table->foreignId('graded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            // Satu siswa hanya punya 1 nilai sikap per semester per tahun ajaran
            $table->unique(['student_id', 'academic_year', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('character_grades');
    }
};