<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DetailController extends Controller
{
    public function viewDetail($userId, Request $request)
    {
        if (Auth::guard('web')->check()) {
            $auth = 'user';
        } elseif (Auth::guard('admin')->check()) {
            $auth = 'admin';
        }

        $user = User::find($userId);
        $workTime = $user->workTimes()->whereDate('start_time', $request->date)->first();
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
            'date' => Carbon::parse($request->date),
            'work_start' => Carbon::parse($workTime->start_time),
            'work_finish' => Carbon::parse($workTime->finish_time),
            'break_times' => $breakData,
        ];

        return view('detail', compact('auth', 'attendanceData'));
    }
}
