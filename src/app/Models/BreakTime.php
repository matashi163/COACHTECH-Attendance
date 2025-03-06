<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BreakTime extends Model
{
    protected $fillable = [
        'work_time_id',
        'start_time',
        'finish_time',
    ];
}
