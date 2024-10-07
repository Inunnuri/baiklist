<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Calendar extends Model
{
    use HasFactory;

     //Eager Loading by Default
     protected $with = ['user'];
     
    public function task(){
        return $this->HashMany(Task::class, 'calendar_id');
    }


    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
}
