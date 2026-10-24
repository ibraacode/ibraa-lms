<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nis', 20)->nullable()->unique()->after('email');
            $table->string('nip', 30)->nullable()->unique()->after('nis');
            $table->string('phone', 20)->nullable()->after('nip');
            $table->string('photo_path')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('photo_path');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('must_change_password', 1)->default('0')->after('last_login_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nis', 'nip', 'phone', 'photo_path',
                'is_active', 'last_login_at', 'must_change_password',
            ]);
        });
    }
};