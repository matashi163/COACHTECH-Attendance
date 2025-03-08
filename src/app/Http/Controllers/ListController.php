<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\WorkTime;
use App\Models\BreakTime;

class ListController extends Controller
{
    public function viewList(Request $request)
    {
        $user = User::find(auth()->id());
        $now = Carbon::now();

        $month = $request->has('month') ? Carbon::parse($request->month)->startOfMonth() : $now->startOfMonth();

        $previousMonth = $month->copy()->subMonth();
        $nextMonth = $month->copy()->addMonth();

        $attendanceDatas = [];
        for ($day = $month->copy()->startOfMonth(); $day <= $month->copy()->endOfMonth(); $day->addDay()) {
            $work = $user->worktimes()->whereDate('start_time', $day->format('Y-m-d'))->first();

            if ($work && $work->finish_time) {
                $breakTotal = 0;
                foreach ($work->breaktimes as $break) {
                    if ($break->start_time && $break->finish_time) {
                        $breakTotal += Carbon::parse($break->start_time)->diffInSeconds(Carbon::parse($break->finish_time));
                    }
                }

                $workTotal = Carbon::parse($work->start_time)->diffInSeconds(Carbon::parse($work->finish_time)) - $breakTotal;

                $attendanceDatas[] = [
                    'date' => $day->locale('ja')->isoFormat('MM/DD(ddd)'),
                    'work_start' => Carbon::parse($work->start_time)->format('H:i'),
                    'work_finish' => Carbon::parse($work->finish_time)->format('H:i'),
                    'break_time' => sprintf('%d:%02d', floor($breakTotal / 3600), floor(($breakTotal % 3600) / 60)),
                    'work_time' => sprintf('%d:%02d', floor($workTotal / 3600), floor(($workTotal % 3600) / 60)),
                    'detail_url' => $day->format('Y-m-d'),
                ];
            } else {
                $attendanceDatas[] = [
                    'date' => $day->locale('ja')->isoFormat('MM/DD(ddd)'),
                ];
            }
        }

        return view('list', compact('month', 'attendanceDatas'));
    }
}
