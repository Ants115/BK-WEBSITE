<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    //

    protected $fillable = ['pengirim_id', 'penerima_id', 'isi', 'is_read'];

public function pengirim()
{
    return $this->belongsTo(User::class, 'pengirim_id');
}
}
