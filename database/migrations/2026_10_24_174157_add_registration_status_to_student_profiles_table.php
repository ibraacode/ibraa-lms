<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            // Status daftar ulang: pending, completed
            $table->enum('registration_status', ['pending', 'completed'])->default('pending')->after('is_verified');
            $table->timestamp('registration_completed_at')->nullable()->after('registration_status');
        });
    }

    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn(['registration_status', 'registration_completed_at']);
        });
    }
};