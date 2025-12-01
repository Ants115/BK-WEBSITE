<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BiodataSiswa extends Model
{
    use HasFactory;

    /**
     * Memberitahu Laravel nama tabel yang benar untuk model ini.
     */
    protected $table = 'biodata_siswa';

    /**
     * The attributes that are mass assignable.
     * Diperbaiki: Melengkapi semua kolom yang bisa diisi.
     */
    protected $fillable = [
        'user_id',
        'nis',
        'nama_lengkap',
        'kelas_id',
        'alamat',
        'no_telepon',
    ];

    /**
     * Mendefinisikan relasi ke model Kelas.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
    
    /**
     * Mendefinisikan relasi ke model User.
     */
    
public function user(): BelongsTo
{
    return $this->belongsTo(User::class, 'user_id');
}

}