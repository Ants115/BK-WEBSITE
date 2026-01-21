<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\BiodataStaf;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke biodata siswa (untuk user role = siswa).
     */
    public function biodataSiswa(): HasOne
    {
        return $this->hasOne(BiodataSiswa::class, 'user_id');
    }

    /**
     * Riwayat pelanggaran siswa.
     */
   public function pelanggaranSiswa(): BelongsToMany
    {
        return $this->belongsToMany(Pelanggaran::class, 'catatan_pelanggaran', 'siswa_id', 'pelanggaran_id')
                    ->withPivot('id', 'tanggal', 'keterangan', 'poin_saat_itu') // Ambil kolom tambahan di tabel tengah
                    ->withTimestamps()
                    ->orderByPivot('tanggal', 'desc');
    }

    /**
     * Prestasi siswa.
     */
    public function prestasi(): HasMany
    {
        return $this->hasMany(Prestasi::class, 'siswa_id');
    }

    /**
     * Kelas-kelas yang diwalikan oleh user ini (role = wali_kelas).
     */
    public function kelasDiwalikan(): HasMany
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id');
    }

    public function biodataStaf()
{
    // relasi 1-1: satu user punya satu biodata staf
    return $this->hasOne(BiodataStaf::class, 'user_id');
}

}
