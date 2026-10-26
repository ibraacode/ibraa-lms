<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->decimal('report_score', 5, 2)->nullable()->after('chosen_major'); // Rata-rata rapor
            $table->integer('test_score')->nullable()->after('report_score'); // Nilai tes (jika ada)
            $table->decimal('final_score', 5, 2)->nullable()->after('test_score'); // Nilai akhir untuk seleksi
        });
    }

    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->dropColumn(['report_score', 'test_score', 'final_score']);
        });
    }
};