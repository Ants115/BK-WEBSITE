<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('pesans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pengirim_id')->constrained('users')->onDelete('cascade'); // Siapa yang ngetik?
        $table->foreignId('penerima_id')->constrained('users')->onDelete('cascade'); // Untuk siapa?
        $table->text('isi'); // Isi chatnya
        $table->boolean('is_read')->default(false); // Sudah dibaca belum?
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesans');
    }
};
