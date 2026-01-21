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
        // PENGAMAN: Cek dulu, jika tabel SUDAH ADA, jangan buat lagi (skip)
        if (!Schema::hasTable('prestasi')) {
            
            Schema::create('prestasi', function (Blueprint $table) {
                $table->id();

                // Relasi ke users (siswa)
                $table->foreignId('siswa_id')
                    ->constrained('users')
                    ->onDelete('cascade');

                // Data prestasi (sesuaikan dengan kolom yang kamu butuhkan)
                $table->string('nama_prestasi'); // atau 'judul'
                $table->string('jenis')->nullable();
                $table->string('tingkat')->nullable();
                $table->string('pencapaian')->nullable();
                $table->string('penyelenggara')->nullable();
                $table->date('tanggal')->nullable();
                $table->text('keterangan')->nullable();

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi');
    }
};