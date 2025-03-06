<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkTime;
use App\Models\BreakTime;
use Illuminate\Support\Carbon;

class RecordController extends Controller
{
    public function viewRecord()
    {
        $user = User::find(auth()->id());
        $today = Carbon::today()->startOfDay();
        $latestWorkTime = $user->workTimes()->orderBy('created_at', 'desc')->first();
        $latestBreakTime = $latestWorkTime ? $latestWorkTime->breakTimes()->orderBy('created_at', 'desc')->first() : null;

        $working = $latestWorkTime && $latestWorkTime->finish_time === null;
        $breaking = $latestBreakTime && $latestBreakTime->finish_time === null;
        $worked = $user->workTimes()->whereDate('finish_time', $today)->exists();

        if ($worked) {
            $user->update(['status_id' => '4']);
        } elseif ($breaking) {
            $user->update(['status_id' => '3']);
        } elseif ($working) {
            $user->update(['status_id' => '2']);
        } else {
            $user->update(['status_id' => '1']);
        }

        $nowTime = now();

        return view('record', compact('user', 'nowTime'));
    }

    public function workStart()
    {
        $user = User::find(auth()->id());

        WorkTime::create([
            'user_id' => $user->id,
            'start_time' => Carbon::now(),
        ]);

        return redirect('/attendance');
    }

    public function workFinish()
    {
        $user = User::find(auth()->id());
        $today = Carbon::today()->startOfDay();
        $latestWorkTime = $user->workTimes()->orderBy('created_at', 'desc')->first();
        $workTime = Carbon::parse($latestWorkTime->start_time);

        while ($workTime < $today) {
            $latestWorkTime->update([
                'finish_time' => $workTime->endOfDay(),
            ]);

            $workTime = $workTime->addDay();

            $latestWorkTime = WorkTime::create([
                'user_id' => $user->id,
                'start_time' => $workTime->startOfDay(),
            ]);
        }

        $latestWorkTime->update([
            'finish_time' => Carbon::now(),
        ]);

        return redirect('/attendance');
    }

    public function breakStart()
    {
        $user = User::find(auth()->id());
        $latestWorkTime = $user->workTimes()->orderBy('created_at', 'desc')->first();

        BreakTime::create([
            'work_time_id' => $latestWorkTime->id,
            'start_time' => Carbon::now(),
        ]);

        return redirect('/attendance');
    }

    public function breakFinish()
    {
        $user = User::find(auth()->id());
        $today = Carbon::today()->startOfDay();
        $latestWorkTime = $user->workTimes()->orderBy('created_at', 'desc')->first();
        $latestBreakTime = $latestWorkTime->breakTimes()->orderBy('created_at', 'desc')->first();
        $breakTime = Carbon::parse($latestBreakTime->start_time);

        while ($breakTime < $today) {
            $latestBreakTime->update([
                'finish_time' => $breakTime->endOfDay(),
            ]);

            $breakTime = $breakTime->addDay();

            $latestBreakTime = BreakTime::create([
                'work_time_id' => $latestWorkTime->id,
                'start_time' => $breakTime->startOfDay(),
            ]);
        }

        $latestBreakTime->update([
            'finish_time' => Carbon::now(),
        ]);

        return redirect('/attendance');
    }
}
