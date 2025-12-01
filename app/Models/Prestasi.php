<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi oleh Admin
    protected $fillable = [
        'siswa_id',
        'nama_prestasi',
        'jenis',
        'tingkat',
        'penyelenggara',
        'tahun',
        'bukti_foto',
        'keterangan',
    ];

    /**
     * Relasi: Sebuah Prestasi dimiliki oleh satu Siswa (User)
     */
    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }
}