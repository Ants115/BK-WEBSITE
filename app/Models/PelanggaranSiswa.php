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

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = ['siswa_user_id', 'pelanggaran_id', 'tanggal', 'keterangan'];

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
}

