<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;

class DetailController extends Controller
{
    public function viewDetail($date)
    {
        $user = User::find(auth()->id());
        $workTime = $user->workTimes()->whereDate('start_time', $date)->first();
        $breakTimes = $workTime->breakTimes;

        $breakData = [];
        foreach ($breakTimes as $breakTime) {
            $breakData[] = [
                'break_start' => Carbon::parse($breakTime->start_time),
                'break_finish' => Carbon::parse($breakTime->finish_time),
            ];
        }

        $attendanceData = [
            'name' => $user->name,
            'date' => Carbon::parse($date),
            'work_start' => Carbon::parse($workTime->start_time),
            'work_finish' => Carbon::parse($workTime->finish_time),
            'break_times' => $breakData,
        ];

        return view('detail', compact('attendanceData'));
    }
}
