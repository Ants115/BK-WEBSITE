<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // <-- Pastikan ini ada

class BiodataStaf extends Model // <-- Pastikan ada 'extends Model'
{
    use HasFactory;
    protected $table = 'biodata_staf';
    protected $fillable = ['user_id', 'nip', 'nama_lengkap', 'jabatan'];
}
 
// /**
// * Mendefinisikan relasi ke model User.
// */
// public function user(): BelongsTo
// {
// return $this->belongsTo(User::class);
// }
