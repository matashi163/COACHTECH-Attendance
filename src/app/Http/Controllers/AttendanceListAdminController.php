<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class AttendanceListAdminController extends Controller
{
    public function viewAttendanceListAdmin(Request $request)
    {
        Auth::guard('web')->logout();

        $users = User::all();
        $now = Carbon::now();

        $date = $request->has('date') ? Carbon::parse($request->date)->startOfDay() : $now->startOfDay();

        $attendanceDatas = [];
        foreach ($users as $user) {
            $work = $user->workTimes()->whereDate('start_time', $date)->first();

            if ($work) {
                $breakTotal = 0;
                foreach ($work->breakTimes as $break) {
                    if ($break->start_time && $break->finish_time) {
                        $breakTotal += Carbon::parse($break->start_time)->diffInSeconds(Carbon::parse($break->finish_time));
                    }
                }

                $workTotal = Carbon::parse($work->start_time)->diffInSeconds(Carbon::parse($work->finish_time)) - $breakTotal;

                $attendanceDatas[] = [
                    'name' => $user->name,
                    'work_start' => Carbon::parse($work->start_time)->format('H:i'),
                    'work_finish' => Carbon::parse($work->finish_time)->format('H:i'),
                    'break_time' => sprintf('%d:%02d', floor($breakTotal / 3600), floor(($breakTotal % 3600) / 60)),
                    'work_time' => sprintf('%d:%02d', floor($workTotal / 3600), floor(($workTotal % 3600) / 60)),
                    'detail_url' => '/attendance/' . $user->id . '?date=' . $date->format('Y-m-d'),
                ];
            } else {
                $attendanceDatas[] = [
                    'name' => $user->name,
                ];
            }
        }

        return view('attendance_list_admin', compact('date', 'attendanceDatas'));
    }
}
