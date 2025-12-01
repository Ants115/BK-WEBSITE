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
    if (!Schema::hasTable('konsultasi')) {
        Schema::create('konsultasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('jadwal_diminta');
            $table->dateTime('jadwal_disetujui')->nullable();
            $table->text('topik');
            $table->string('status')->default('Menunggu Persetujuan');
            $table->text('pesan_guru')->nullable();
            $table->text('hasil_konseling')->nullable(); 
            $table->timestamps();
        });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konsultasis');
    }
};
