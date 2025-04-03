<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorrectedWorkTime extends Model
{
    protected $fillable = [
        'work_time_id',
        'start_time',
        'finish_time',
        'notes',
        'permission',
    ];

    public function workTime()
    {
        return $this->belongsTo(WorkTime::class);
    }
}
