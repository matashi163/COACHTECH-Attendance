<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class AttendanceListController extends Controller
{
    public function viewAttendanceList($userId = null, Request $request)
    {
        $now = Carbon::now();

        if (Auth::guard('web')->check()) {
            $auth = 'user';
            $user = User::find(auth()->id());
            $title = '勤怠一覧';
            $url = '/attendance/list';
        } elseif (Auth::guard('admin')->check()) {
            $auth = 'admin';
            $user = User::find($userId);
            $title = $user->name . 'さんの勤怠';
            $url = '/admin/attendance/staff/' . $user->id;
        }

        $month = $request->has('month') ? Carbon::parse($request->month)->startOfMonth() : $now->startOfMonth();

        $attendanceDatas = [];
        for ($day = $month->copy()->startOfMonth(); $day <= $month->copy()->endOfMonth(); $day->addDay()) {
            $work = $user->workTimes()->whereDate('start_time', $day->format('Y-m-d'))->first();

            if ($work && $work->finish_time) {
                $breakTotal = 0;
                foreach ($work->breakTimes as $break) {
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
                    'detail_url' => '/attendance/' . $user->id . '?date=' . $day->format('Y-m-d'),
                ];
            } else {
                $attendanceDatas[] = [
                    'date' => $day->locale('ja')->isoFormat('MM/DD(ddd)'),
                ];
            }
        }

        return view('attendance_list', compact('auth', 'title', 'url', 'month', 'attendanceDatas'));
    }
}
