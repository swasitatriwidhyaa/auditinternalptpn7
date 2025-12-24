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
        // --- TAMBAHAN: Hapus paksa jika tabel sudah ada (Solusi Error Duplicate) ---
        Schema::dropIfExists('audit_standards'); 
        // --------------------------------------------------------------------------

        Schema::create('audit_standards', function (Blueprint $table) {
            $table->id();
            $table->string('kode'); 
            $table->string('nama'); 
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_standards');
    }
};
