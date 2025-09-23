<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str; // <-- 1. TAMBAHKAN BARIS INI untuk mengimpor helper Str

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    protected $fillable = ['tingkatan_id', 'jurusan_id', 'nama_unik', 'nama_kelas', 'tahun_ajaran'];

    public function tingkatan(): BelongsTo
    {
        return $this->belongsTo(Tingkatan::class);
    }
    
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

    /**
     * Mencari dan mengembalikan data kelas yang paling mungkin
     * menjadi tujuan kenaikan kelas berdasarkan pola nama.
     * Logika ini berlaku untuk SEMUA JURUSAN.
     * Contoh: dari 'X RPL 1' akan mencari 'XI RPL 1'
     *
     * @return Model|null
     */
    public function getSuggestedNextClass()
    {
        $this->loadMissing('tingkatan');
        
        $namaKelasSekarang = $this->nama_kelas;
        $namaTingkatan = $this->tingkatan->nama_tingkatan;
        $namaKelasTujuan = null;

        if ($namaTingkatan === 'X' && str_starts_with($namaKelasSekarang, 'X ')) {
            // 2. PERBAIKI BAGIAN INI: Gunakan Str::replaceFirst
            $namaKelasTujuan = Str::replaceFirst('X ', 'XI ', $namaKelasSekarang);
        }
        elseif ($namaTingkatan === 'XI' && str_starts_with($namaKelasSekarang, 'XI ')) {
            // DAN BAGIAN INI JUGA: Gunakan Str::replaceFirst
            $namaKelasTujuan = Str::replaceFirst('XI ', 'XII ', $namaKelasSekarang);
        }

        if ($namaKelasTujuan) {
            return static::firstWhere('nama_kelas', $namaKelasTujuan);
        }

        return null;
    }
}