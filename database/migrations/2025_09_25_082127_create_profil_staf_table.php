<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Diperbaiki: Nama tabel diubah menjadi 'biodata_staf'
        Schema::create('biodata_staf', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('nip')->unique()->nullable();
            $table->string('nama_lengkap'); // <-- Ditambahkan: Kolom ini hilang sebelumnya
            $table->string('jabatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Diperbaiki: Nama tabel yang dihapus juga diubah
        Schema::dropIfExists('biodata_staf');
    }
};
