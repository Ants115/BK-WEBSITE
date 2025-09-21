<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}