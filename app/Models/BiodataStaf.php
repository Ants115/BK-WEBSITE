<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- Pastikan ini ada

class BiodataStaf extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model.
     */
    protected $table = 'biodata_staf';

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = ['user_id', 'nip', 'nama_lengkap', 'jabatan'];
    
    /**
     * Mendefinisikan relasi ke model User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}