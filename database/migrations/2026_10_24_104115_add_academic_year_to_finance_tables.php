<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah di tabel invoices
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('academic_year', 10)->nullable()->after('id');
        });

        // Tambah di tabel other_incomes (Pemasukan BOS/Hibah)
        Schema::table('other_incomes', function (Blueprint $table) {
            $table->string('academic_year', 10)->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('academic_year');
        });
        Schema::table('other_incomes', function (Blueprint $table) {
            $table->dropColumn('academic_year');
        });
    }
};