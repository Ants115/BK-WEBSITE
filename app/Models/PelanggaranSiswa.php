<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PelanggaranSiswa extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'pelanggaran_siswa';
    protected $fillable = ['siswa_user_id', 'pelanggaran_id', 'pelapor_user_id', 'tanggal', 'keterangan'];

    /**
     * Mendapatkan data pelanggaran (aturan) yang terkait.
     */
    public function pelanggaran(): BelongsTo
    {
        return $this->belongsTo(Pelanggaran::class);
    }

    /**
     * Mendapatkan data siswa (user) yang melakukan pelanggaran.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_user_id');
    }

    /**
     * Mendapatkan data user (guru/admin) yang melaporkan pelanggaran.
     */
    public function pelapor(): BelongsTo // <-- TAMBAHKAN RELASI INI
    {
        return $this->belongsTo(User::class, 'pelapor_user_id');
    }
}