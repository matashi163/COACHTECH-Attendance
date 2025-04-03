<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\BreakTime;
use App\Models\CorrectedWorkTime;
use App\Models\CorrectedBreakTime;
use App\Http\Requests\CorrectRequest;

class DetailController extends Controller
{
    public function viewDetail($userId, Request $request)
    {
        if (Auth::guard('web')->check()) {
            $auth = 'user';
        } elseif (Auth::guard('admin')->check()) {
            $auth = 'admin';
        }

        $url = '/attendance/' . $userId . '?date=' . $request->date;

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
            'notes' => $workTime->correctedWorkTimes()->where('permission', false)->first()->notes ?? '',
        ];

        if ($workTime->correctedWorkTimes()->where('permission', false)->first()) {
            $approving = true;
        } else {
            $approving = false;
        }

        return view('detail', compact('auth', 'url', 'attendanceData', 'approving'));
    }

    public function correct(CorrectRequest $request, $userId)
    {
        $user = User::find($userId);
        $date = Carbon::parse($request->date);
        $workTime = $user->workTimes()->whereDate('start_time', $date)->first();

        if (Auth::guard('web')->check()) {
            $startTime = Carbon::parse($request->work_start);
            $finishTime = Carbon::parse($request->work_finish);

            $correctedWorkTime = CorrectedWorkTime::create([
                'work_time_id' => $workTime->id,
                'start_time' => $date->copy()->setTime($startTime->hour, $startTime->minute, 0),
                'finish_time' => $date->copy()->setTime($finishTime->hour, $finishTime->minute, 0),
                'notes' => $request->notes,
                'permission' => false,
            ]);

            foreach (array_keys($request->break_start) as $arrayKey) {
                if ($request->break_start[$arrayKey] && $request->break_finish[$arrayKey]) {
                    $breakStart = Carbon::parse($request->break_start[$arrayKey]);
                    $breakFinish = Carbon::parse($request->break_finish[$arrayKey]);

                    CorrectedBreakTime::create([
                        'corrected_work_time_id' => $correctedWorkTime->id,
                        'start_time' => $date->copy()->setTime($breakStart->hour, $breakStart->minute, 0),
                        'finish_time' => $date->copy()->setTime($breakFinish->hour, $breakFinish->minute, 0),
                    ]);
                }
            }
        } elseif (Auth::guard('admin')->check()) {
            if ($workTime->correctedWorkTimes()->where('permission', false)->first()) {
                $correctedWorkTime = $workTime->correctedWorkTimes()->where('permission', false)->first();

                $correctedWorkTime->update([
                    'permission' => true,
                ]);
            } else {
                $startTime = Carbon::parse($request->work_start);
                $finishTime = Carbon::parse($request->work_finish);

                $correctedWorkTime = CorrectedWorkTime::create([
                    'work_time_id' => $workTime->id,
                    'start_time' => $date->copy()->setTime($startTime->hour, $startTime->minute, 0),
                    'finish_time' => $date->copy()->setTime($finishTime->hour, $finishTime->minute, 0),
                    'notes' => $request->notes,
                    'permission' => true,
                ]);

                foreach (array_keys($request->break_start) as $arrayKey) {
                    if ($request->break_start[$arrayKey] && $request->break_finish[$arrayKey]) {
                        $breakStart = Carbon::parse($request->break_start[$arrayKey]);
                        $breakFinish = Carbon::parse($request->break_finish[$arrayKey]);

                        CorrectedBreakTime::create([
                            'corrected_work_time_id' => $correctedWorkTime->id,
                            'start_time' => $date->copy()->setTime($breakStart->hour, $breakStart->minute, 0),
                            'finish_time' => $date->copy()->setTime($breakFinish->hour, $breakFinish->minute, 0),
                        ]);
                    }
                }
            }
            $startTime = Carbon::parse($request->work_start);
            $finishTime = Carbon::parse($request->work_finish);

            $workTime->update([
                'start_time' => $date->copy()->setTime($startTime->hour, $startTime->minute, 0),
                'finish_time' => $date->copy()->setTime($finishTime->hour, $finishTime->minute, 0),
            ]);

            BreakTime::where('work_time_id', $workTime->id)->delete();

            foreach (array_keys($request->break_start) as $arrayKey) {
                if ($request->break_start[$arrayKey] && $request->break_finish[$arrayKey]) {
                    $breakStart = Carbon::parse($request->break_start[$arrayKey]);
                    $breakFinish = Carbon::parse($request->break_finish[$arrayKey]);

                    BreakTime::create([
                        'work_time_id' => $workTime->id,
                        'start_time' => $date->copy()->setTime($breakStart->hour, $breakStart->minute, 0),
                        'finish_time' => $date->copy()->setTime($breakFinish->hour, $breakFinish->minute, 0),
                    ]);
                }
            }
        }

        return back();
    }
}
