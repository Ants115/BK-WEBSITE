<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Konsultasi extends Model
{
    use HasFactory;

    protected $table = 'konsultasi';

    // Konstanta status
    public const STATUS_MENUNGGU          = 'Menunggu Persetujuan';
    public const STATUS_DISETUJUI         = 'Disetujui';
    public const STATUS_DITOLAK           = 'Ditolak';
    public const STATUS_DIJADWALKAN_ULANG = 'Dijadwalkan Ulang';
    public const STATUS_SELESAI           = 'Selesai';

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

    protected $casts = [
        'jadwal_diminta'    => 'datetime',
        'jadwal_disetujui'  => 'datetime',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    // Jadwal aktif (yang dipakai di tabel admin)
    public function getJadwalAktifAttribute(): ?Carbon
    {
        if ($this->jadwal_disetujui) {
            return $this->jadwal_disetujui instanceof Carbon
                ? $this->jadwal_disetujui
                : Carbon::parse($this->jadwal_disetujui);
        }

        if ($this->jadwal_diminta) {
            return $this->jadwal_diminta instanceof Carbon
                ? $this->jadwal_diminta
                : Carbon::parse($this->jadwal_diminta);
        }

        return null;
    }
}
