<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "X IPA 1"
            $table->string('grade_level', 5); // Contoh: "X", "XI", "XII"
            $table->string('major_code', 20)->nullable();
            $table->string('academic_year', 10); // Contoh: "2026/2027"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};