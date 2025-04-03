<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorrectedBreakTime extends Model
{
    protected $fillable = [
        'corrected_work_time_id',
        'start_time',
        'finish_time',
    ];
}
