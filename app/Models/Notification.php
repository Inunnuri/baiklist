<?php
//polymorphic relationship yang memungkinkan model untuk memiliki banyak tipe notifikasi yang berbeda.
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Notification extends Model
{
    use Notifiable; //untuk fitur notifikasi

    public function notifiable()
    {
        return $this->morphTo();//Relasi morphTo() memungkinkan sebuah notifikasi dihubungkan dengan berbagai model lain yang berbeda, tanpa perlu membuat relasi satu per satu.
        //pastikan model yang berhubungan misal model task, pakai morphMany()
        //pastikan model user pakai Notifiable, agar user dapat menerima notifikasi dan karena notifiable_type nya model user
    }
    //done
}
