<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkTime extends Model
{
    protected $fillable = [
        'user_id',
        'start_time',
        'finish_time',
    ];

    public function breakTimes()
    {
        return $this->hasMany(BreakTime::class);
    }
}
