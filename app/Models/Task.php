<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','title', 'description','due_date','reminder_at', 'frequency_id', 'category_id', 'status_id', 'calendar_id'];

    protected $dates = ['due_date'];


    //Eager Loading by Default
    //Penggunaan $with di model membuat kode lebih bersih dan lebih efisien, karena kamu tidak perlu menambahkan with() secara eksplisit di setiap query. Pastikan relasi yang ada di dalam $with memang sering digunakan agar kamu mendapatkan manfaat maksimal dari eager loading.
    protected $with = ['user', 'category', 'status', 'calendar', 'frequency'];

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
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

    
    
}


