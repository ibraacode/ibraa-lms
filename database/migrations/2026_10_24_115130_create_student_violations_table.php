<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('violation_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reported_by')->constrained('users'); // Guru yang mencatat
            $table->date('date');
            $table->text('notes')->nullable();
            $table->string('academic_year', 10); // WAJIB: Terikat tahun ajaran
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_violations');
    }
};