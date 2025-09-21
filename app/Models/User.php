<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Notifications\Notifiable;
    use Illuminate\Database\Eloquent\Relations\HasOne;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\HasOneThrough;

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

        public function biodataSiswa(): HasOne
    {
        return $this->hasOne(BiodataSiswa::class, 'user_id');
    }

        // Perbaikan: Menambahkan tipe return 'HasMany'
        public function pelanggaranSiswa(): HasMany
        {
            return $this->hasMany(PelanggaranSiswa::class, 'siswa_user_id');
        }

       
        
    }