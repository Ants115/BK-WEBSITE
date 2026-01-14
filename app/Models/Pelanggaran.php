<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Pelanggaran extends Model
{
    use HasFactory;

    // Dengan menghapus baris "protected $table", Laravel akan secara otomatis
    // mencari nama tabel plural, yaitu "pelanggarans", yang cocok dengan migrasi Anda.
    protected $fillable = [
        'nama_pelanggaran',
        'poin',
        'kategori',
    ];

    /**
     * Mendefinisikan relasi ke model PelanggaranSiswa.
     * Sebuah jenis pelanggaran bisa dimiliki oleh banyak catatan pelanggaran siswa.
     */
    public function pelanggaranSiswa(): HasMany
    {
        return $this->hasMany(PelanggaranSiswa::class);
    }
}