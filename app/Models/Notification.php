<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Notification extends Model
{
    use Notifiable;

    public function notifiable()
    {
        return $this->morphTo();
    }
}
