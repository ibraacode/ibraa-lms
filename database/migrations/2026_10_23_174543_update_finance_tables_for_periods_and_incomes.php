<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah periode di jenis tagihan
        Schema::table('fee_types', function (Blueprint $table) {
            // 'one_time', 'weekly', 'monthly', 'yearly'
            $table->string('period')->default('one_time')->after('default_amount'); 
        });

        // Tabel untuk pemasukan non-siswa (BOS, Hibah, dll)
        Schema::create('other_incomes', function (Blueprint $table) {
            $table->id();
            $table->string('source'); // BOS, Hibah, Sponsor
            $table->string('description')->nullable();
            $table->integer('amount');
            $table->date('received_at');
            $table->string('reference')->nullable();
            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('fee_types', function (Blueprint $table) {
            $table->dropColumn('period');
        });
        Schema::dropIfExists('other_incomes');
    }
};