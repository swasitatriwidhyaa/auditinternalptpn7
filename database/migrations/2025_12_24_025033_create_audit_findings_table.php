<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('audit_findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained('audits')->onDelete('cascade');
            $table->text('klausul'); // Misal: Klausul 5.1 Kepemimpinan
            $table->text('uraian_temuan'); // Apa yang salah?
            $table->enum('kategori', ['major', 'minor', 'observasi']);
            // Respon Auditee
            $table->text('akar_masalah')->nullable();
            $table->text('tindakan_koreksi')->nullable();
            $table->string('bukti_perbaikan')->nullable(); // Path file upload
            $table->enum('status_temuan', ['open', 'responded', 'closed'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_findings');
    }
};
