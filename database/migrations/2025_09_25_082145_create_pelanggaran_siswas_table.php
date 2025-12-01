<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggaran_siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_user_id')->constrained('users');
            $table->foreignId('pelanggaran_id')->constrained('pelanggarans');
            $table->foreignId('pelapor_user_id')->constrained('users');
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggaran_siswas');
    }
};