<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            // Data tambahan
            $table->string('religion', 20)->nullable();
            $table->string('child_order', 10)->nullable();
            $table->integer('siblings_count')->nullable();
            $table->string('living_with', 30)->nullable();
            
            // Data Ortu
            $table->string('father_occupation')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('father_education')->nullable();
            $table->string('mother_education')->nullable();
            $table->text('parent_address')->nullable();
            
            // Dokumen (path file)
            $table->string('document_kk')->nullable();
            $table->string('document_akta')->nullable();
            $table->string('document_ijazah')->nullable();
            $table->string('document_photo')->nullable();
            
            // Status Verifikasi Dokumen
            $table->boolean('is_documents_verified')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            
            // Biaya Pendaftaran
            $table->boolean('is_fee_paid')->default(false);
            $table->string('payment_proof')->nullable(); // Bukti transfer
        });
    }

    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->dropColumn([
                'religion', 'child_order', 'siblings_count', 'living_with',
                'father_occupation', 'mother_occupation', 'father_education', 'mother_education', 'parent_address',
                'document_kk', 'document_akta', 'document_ijazah', 'document_photo',
                'is_documents_verified', 'verified_by', 'verified_at',
                'is_fee_paid', 'payment_proof',
            ]);
        });
    }
};