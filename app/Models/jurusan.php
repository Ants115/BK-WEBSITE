<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Jangan lupa import ini

class Jurusan extends Model
{
    use HasFactory;

    protected $table = 'jurusans';

    protected $fillable = [
        'nama_jurusan',
        'singkatan',
        // ... kolom lainnya
    ];

    /**
     * Relasi: Satu Jurusan memiliki BANYAK Kelas.
     * Contoh: Jurusan TKR punya kelas (X TKR 1, XI TKR 2, dst).
     */
    public function kelas(): HasMany
    {
        // Pastikan nama model 'Kelas' sesuai dengan nama class model kamu
        return $this->hasMany(Kelas::class, 'jurusan_id'); 
    }
}