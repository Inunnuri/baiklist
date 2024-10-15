<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    // use HasFactory; tidak perlu karena tidak pakai data dummy maupun seeder

    protected $fillable = ['user_id','title', 'description','due_date','reminder_at', 'frequency_id', 'category_id', 'status_id', 'calendar_id'];

    protected $dates = ['due_date'];//Properti $dates digunakan untuk mendefinisikan kolom yang harus diperlakukan sebagai objek Carbon secara otomatis, sehingga tidak perlu menggunakan Carbon:, contoh $oldDueDate = $task->due_date;
    //contoh kalau manual: $oldDueDate = Carbon::parse($task->due_date);
    //tapi jika data tetap string, pakai saja manual gpp


    //Eager Loading by Default
    //Penggunaan $with di model membuat kode lebih bersih dan lebih efisien, karena kamu tidak perlu menambahkan with() secara eksplisit di setiap query. Pastikan relasi yang ada di dalam $with memang sering digunakan agar kamu mendapatkan manfaat maksimal dari eager loading.
    protected $with = ['user', 'category', 'status', 'calendar', 'frequency'];

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
        //ditabel tasks harus punya user_id
    }
    
    public function category():BelongsTo{
        return $this->belongsTo(Category::class);
    }
    public function status():BelongsTo{
        return $this->belongsTo(Status::class);
    }
    public function calendar():BelongsTo{
        return $this->belongsTo(Calendar::class);
    }
    public function frequency():BelongsTo{
        return $this->belongsTo(Frequency::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
        //morphMany(): Method ini mendefinisikan relasi one-to-many polymorphic. Artinya, satu Task dapat memiliki banyak notifikasi yang terkait dengannya.
        //pastikan di model Notification pakai morphTo()
        //Parameter 'notifiable' adalah nama yang digunakan untuk merujuk ke relasi ini. Ini berarti bahwa ketika Laravel melakukan query untuk mengambil notifikasi, ia akan mencari kolom notifiable_type dan notifiable_id dalam tabel notifications untuk menentukan model mana (misalnya, Task, User, atau model lain) yang terkait dengan notifikasi tersebut.
    }

    //done
}


