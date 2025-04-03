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

    public function correctedWorkTimes()
    {
        return $this->hasMany(CorrectedWorkTime::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
