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
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            // ... foreign keys tetap sama ...
            $table->foreignId('standard_id')->constrained('audit_standards')->onDelete('cascade');
            $table->foreignId('auditor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('auditee_id')->constrained('users')->onDelete('cascade');
            
            $table->date('tanggal_audit'); // Tanggal mulai
            $table->date('deadline');      // <--- INI KOLOM BARU (Batas Waktu)
            
            // Lokasi KITA HAPUS
            // $table->string('lokasi'); 
            
            $table->enum('status', ['requested', 'planned', 'ongoing', 'finished', 'rejected'])->default('planned');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};
