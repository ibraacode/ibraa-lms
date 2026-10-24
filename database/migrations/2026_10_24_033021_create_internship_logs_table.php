<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internship_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('industry_placement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete(); // Guru pembimbing
            
            $table->date('date');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            
            // Untuk GPS dan Selfie
            $table->string('check_in_photo')->nullable(); // Path foto
            $table->string('check_in_lat')->nullable();
            $table->string('check_in_long')->nullable();
            $table->string('check_out_photo')->nullable();
            $table->string('check_out_lat')->nullable();
            $table->string('check_out_long')->nullable();
            
            $table->text('activity'); // Kegiatan hari ini
            $table->integer('score')->nullable(); // Nilai dari guru
            $table->text('teacher_notes')->nullable();
            $table->timestamps();

            // Satu siswa hanya punya 1 log per hari
            $table->unique(['student_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_logs');
    }
};