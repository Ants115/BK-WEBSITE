<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permohonan_konselings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_user_id')->constrained('users');
            $table->string('topik')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permohonan_konselings');
    }
};