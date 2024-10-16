<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;
    //Notifiable digunakan untuk agar user dapat menerima notifikasi
    //buat notifikasi barunya di Jobs, Tidak harus menggunakan Jobs untuk membuat notifikasi.
    //Tapi menggunakan Jobs/ queue, aplikasi dapat menangani lebih banyak permintaan tanpa memperlambat waktu respons.

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function profile():HasOne{
        return $this->hasOne(Profile::class, 'user_id');
    }

    public function task(): HasMany{
        return $this->hasMany(Task::class, 'user_id');
    }
}
// done


