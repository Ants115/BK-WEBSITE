<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_konselings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_id')->unique()->constrained('permohonan_konselings');
            $table->foreignId('guru_bk_user_id')->constrained('users');
            $table->date('tanggal_konseling');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('lokasi')->nullable();
            $table->string('status')->default('terjadwal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_konselings');
    }
};