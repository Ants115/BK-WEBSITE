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
    Schema::create('catatan_pelanggaran', function (Blueprint $table) {
        $table->id();
        
        // Relasi ke tabel users (karena siswa ada di tabel users)
        $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
        
        // Relasi ke tabel pelanggarans (Pastikan nama tabelmu 'pelanggarans' atau 'pelanggaran')
        // Berdasarkan log error kamu, validasi sukses ke tabel 'pelanggarans', jadi pakai 's'
        $table->foreignId('pelanggaran_id')->constrained('pelanggarans')->onDelete('cascade');
        
        $table->date('tanggal');
        $table->text('keterangan')->nullable();
        $table->integer('poin_saat_itu')->default(0); // Menyimpan snapshot poin
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_pelanggaran');
    }
};
