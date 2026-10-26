<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ================= BIODATA SISWA =================
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->string('nik', 20)->nullable(); // Nomor Induk Kependudukan
            $table->string('kk_number', 20)->nullable(); // Nomor Kartu Keluarga
            $table->string('religion', 20)->nullable()->after('gender');
            $table->string('child_order', 10)->nullable()->after('religion'); // Anak ke-
            $table->integer('siblings_count')->nullable()->after('child_order'); // Jumlah saudara
            $table->string('living_with', 30)->nullable()->after('siblings_count'); // Ortu/Wali/Panti
            $table->text('special_needs')->nullable()->after('living_with'); // Disabilitas/Kebutuhan Khusus
            
            // Data Ortu
            $table->string('father_name')->nullable();
            $table->string('father_status', 30)->nullable(); // Hidup/Meninggal
            $table->string('father_job')->nullable();
            $table->string('father_education', 30)->nullable();
            $table->string('father_phone', 20)->nullable();
            
            $table->string('mother_name')->nullable();
            $table->string('mother_status', 30)->nullable();
            $table->string('mother_job')->nullable();
            $table->string('mother_education', 30)->nullable();
            $table->string('mother_phone', 20)->nullable();
            
            // Data Wali
            $table->string('guardian_name')->nullable();
            $table->string('guardian_relation', 30)->nullable();
            $table->string('guardian_job')->nullable();
            $table->string('guardian_phone', 20)->nullable();
            $table->text('guardian_address')->nullable();

            // Finalisasi
            $table->boolean('is_verified')->default(false); // Status finalisasi data
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
        });

        // ================= BIODATA PEGAWAI / GURU =================
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->string('nik', 20)->nullable();
            $table->string('religion', 20)->nullable();
            $table->string('marital_status', 20)->nullable(); // Belum Menikah, Menikah, dll
            $table->string('last_education', 30)->nullable(); // S1, S2, SMA
            $table->string('university')->nullable();
            $table->string('major')->nullable();
            $table->year('graduation_year')->nullable();
            $table->string('npwp', 30)->nullable();
            
            // Finalisasi
            $table->boolean('is_verified')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'nik', 'kk_number', 'religion', 'child_order', 'siblings_count', 
                'living_with', 'special_needs', 'father_name', 'father_status', 
                'father_job', 'father_education', 'father_phone', 'mother_name', 
                'mother_status', 'mother_job', 'mother_education', 'mother_phone', 
                'guardian_name', 'guardian_relation', 'guardian_job', 'guardian_phone', 
                'guardian_address', 'is_verified', 'verified_by', 'verified_at'
            ]);
        });

        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'nik', 'religion', 'marital_status', 'last_education', 'university',
                'major', 'graduation_year', 'npwp', 'employment_status', 'joined_at',
                'is_verified', 'verified_by', 'verified_at'
            ]);
        });
    }
};