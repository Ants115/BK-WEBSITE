<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Pelanggaran extends Model
{
    use HasFactory;

    protected $table = 'pelanggaran'; // Opsional, tapi praktik yang baik

    protected $fillable = [
        'nama_pelanggaran',
        'poin',
    ];
}