<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letter_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama template (e.g. "Surat Keterangan Aktif")
            $table->string('code')->unique(); // Kode singkat untuk internal
            $table->text('body'); // Isi surat, boleh pakai variabel {nama_siswa}, {kelas}, {nis}
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letter_templates');
    }
};