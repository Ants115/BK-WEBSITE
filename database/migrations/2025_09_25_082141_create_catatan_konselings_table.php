<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catatan_konselings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->unique()->constrained('jadwal_konselings');
            $table->text('isi_catatan');
            $table->text('tindak_lanjut')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catatan_konselings');
    }
};