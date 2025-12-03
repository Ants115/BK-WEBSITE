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
        Schema::create('prestasi', function (Blueprint $table) {
            $table->id();

            // Relasi ke users (siswa)
            $table->foreignId('siswa_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Data prestasi
            $table->string('nama_prestasi');
            $table->string('jenis')->nullable();         // akademik / non-akademik / lainnya
            $table->string('tingkat')->nullable();       // Sekolah / Kab / Prov / Nasional / dll
            $table->string('pencapaian')->nullable();    // Juara 1, Harapan, Peserta, dll
            $table->string('penyelenggara')->nullable();
            $table->date('tanggal')->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi');
    }
};
