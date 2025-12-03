<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    use HasFactory;

    protected $table = 'prestasi';

    protected $fillable = [
        'siswa_id',
        'judul',
        'tingkat',
        'kategori',
        'penyelenggara',
        'tanggal',
        'bukti_foto',
        'keterangan',
    ];

    // Cast kolom tanggal jadi object tanggal (Carbon)
    protected $casts = [
        'tanggal'    => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Pilihan tingkat & kategori (bisa dipakai ulang di controller / view)
    public const TINGKAT_OPTIONS = [
        'Sekolah',
        'Kabupaten/Kota',
        'Provinsi',
        'Nasional',
        'Internasional',
    ];

    public const KATEGORI_OPTIONS = [
        'Akademik',
        'Non-akademik',
    ];

    public function siswa()
    {
        // Karena namespace sama (App\Models), tidak perlu use User lagi
        return $this->belongsTo(User::class, 'siswa_id');
    }
}
