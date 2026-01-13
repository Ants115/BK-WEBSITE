<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
       'nama_kelas',
        'nama_unik',     // Tambahkan ini
        'tahun_ajaran',  // Tambahkan ini (opsional jika mau dipakai)
        'tingkatan_id',
        'jurusan_id',
        'wali_kelas_id',
    ];

    /**
     * Relasi ke Tingkatan (X, XI, XII).
     */
    public function tingkatan(): BelongsTo
    {
        return $this->belongsTo(Tingkatan::class);
    }

    /**
     * Relasi ke Jurusan (RPL, TKJ, dsb).
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

    /**
     * Relasi ke wali kelas (User guru).
     * Asumsi: kolom kelas.wali_kelas_id -> users.id
     */
    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    /**
     * Relasi ke biodata siswa di kelas ini.
     */
    public function biodataSiswa(): HasMany
    {
        return $this->hasMany(BiodataSiswa::class, 'kelas_id');
    }

    /**
     * Alias untuk kompatibilitas kode lama.
     */
    public function biodataSiswas(): HasMany
    {
        return $this->hasMany(BiodataSiswa::class, 'kelas_id');
        // atau: return $this->biodataSiswa();
    }

    /**
     * Mengusulkan kelas tujuan untuk kenaikan kelas.
     */
    public function getSuggestedNextClass(): ?self
    {
        if (!$this->tingkatan_id) {
            return null;
        }

        $query = self::where('tingkatan_id', $this->tingkatan_id + 1);

        if (!is_null($this->jurusan_id ?? null)) {
            $query->where('jurusan_id', $this->jurusan_id);
        }

        return $query->orderBy('nama_kelas')->first();
    }
}
