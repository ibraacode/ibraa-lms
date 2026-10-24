<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // SPP, Uang Gedung, Seragam, dll
            $table->text('description')->nullable();
            $table->integer('default_amount')->default(0); // nominal default
            $table->boolean('is_mandatory')->default(true); // wajib atau opsional
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_types');
    }
};