<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            // Status kelulusan
            $table->enum('status', ['aktif', 'lulus', 'mutasi', 'dropout'])->default('aktif')->after('classroom_id');
            $table->string('graduation_year', 10)->nullable()->after('status'); // Contoh: 2026
        });
    }

    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn(['status', 'graduation_year']);
        });
    }
};
