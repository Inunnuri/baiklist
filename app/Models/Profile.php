<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    // use HasFactory;

    protected $fillable = ['user_id','name','email', 'address','region', 'phone_number', 'profile_photo'];

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
}
// done
