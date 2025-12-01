<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_peringatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_user_id')->constrained('users');
            $table->string('jenis');
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->foreignId('dibuat_oleh_user_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_peringatans');
    }
};