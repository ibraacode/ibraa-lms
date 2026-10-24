<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counseling_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counseling_case_id')->constrained()->cascadeOnDelete();
            $table->foreignId('author_id')->constrained('users'); // Guru BK yang mencatat
            $table->text('content'); // Isi catatan (rahasia)
            $table->date('session_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counseling_notes');
    }
};