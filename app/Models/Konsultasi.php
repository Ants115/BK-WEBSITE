<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'konsultasi';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'siswa_id',
        'guru_id',
        'jadwal_diminta',
        'jadwal_disetujui',
        'topik',
        'status',
        'pesan_guru',
        'hasil_konseling',
    ];

    // Casting tanggal ke instance Carbon
    protected $casts = [
        'jadwal_diminta'   => 'datetime',
        'jadwal_disetujui' => 'datetime',
    ];

    // Konstanta status untuk konsistensi
    public const STATUS_MENUNGGU           = 'Menunggu Persetujuan';
    public const STATUS_DISETUJUI          = 'Disetujui';
    public const STATUS_DITOLAK            = 'Ditolak';
    public const STATUS_DIJADWALKAN_ULANG  = 'Dijadwalkan Ulang';
    public const STATUS_SELESAI            = 'Selesai';

    /**
     * Relasi ke model User (sebagai Siswa)
     */
    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    /**
     * Relasi ke model User (sebagai Guru)
     */
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    /**
     * Jadwal aktif yang dipakai untuk tampilan:
     * - Jika ada jadwal_disetujui → pakai itu
     * - Kalau tidak → pakai jadwal_diminta
     */
    public function getJadwalAktifAttribute()
    {
        return $this->jadwal_disetujui ?: $this->jadwal_diminta;
    }
}
