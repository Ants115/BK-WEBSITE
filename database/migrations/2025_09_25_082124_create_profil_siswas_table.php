<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Diperbaiki: Nama tabel diubah menjadi 'biodata_siswa'
        Schema::create('biodata_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('nis')->unique();
            $table->string('nama_lengkap'); // <-- Ditambahkan: Kolom ini hilang sebelumnya
            $table->foreignId('kelas_id')->nullable()->constrained('kelas');
            $table->text('alamat')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('status')->default('Aktif'); 
            $table->string('tahun_lulus')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Diperbaiki: Nama tabel yang dihapus juga diubah
        Schema::dropIfExists('biodata_siswa');
    }
};
