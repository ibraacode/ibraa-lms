<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->text('answer_text')->nullable(); // Jawaban teks (bisa diganti file nanti)
            $table->integer('score')->nullable(); // Nilai dari guru
            $table->text('feedback')->nullable(); // Catatan dari guru
            $table->dateTime('submitted_at')->nullable();
            $table->timestamps();

            // Satu siswa hanya punya 1 data pengumpulan per tugas
            $table->unique(['assignment_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};