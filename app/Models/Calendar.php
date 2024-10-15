<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    use HasFactory;// Digunakan untuk mempermudah pembuatan instance model dalam pengujian atau seed data , factory()

    public function task(){
        return $this->HashMany(Task::class, 'calendar_id');
    }
}
//done
