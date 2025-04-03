<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\CorrectedWorkTime;

class CorrectionListController extends Controller
{
    public function viewCorrectionList(Request $request)
    {
        if ($request->page == 'approving') {
            $page = false;
        } elseif ($request->page == 'approved') {
            $page = true;
        } else {
            $page = false;
        }

        if (Auth::guard('web')->check()) {
            $auth = 'user';

            $user = User::find(auth()->id());
            $corrects = CorrectedWorkTime::whereIn('work_time_id', $user->workTimes()->pluck('id')->toArray())->where('permission', $page)->get();

            $correctDatas = [];
            if ($corrects) {
                foreach ($corrects as $correct) {
                    $correctDatas[] = [
                        'status' => $page,
                        'name' => $user->name,
                        'date' => Carbon::parse($correct->start_time)->format('Y/m/d'),
                        'reason' => $correct->notes,
                        'correct_date' => Carbon::parse($correct->created_at)->format('Y/m/d'),
                        'detail_url' => '/attendance/' . $user->id . '?date=' . Carbon::parse($correct->start_time)->format('Y-m-d'),
                    ];
                }
            }
        } elseif (Auth::guard('admin')->check()) {
            $auth = 'admin';

            $corrects = CorrectedWorkTime::where('permission', $page)->get();

            $correctDatas = [];
            if ($corrects) {
                foreach ($corrects as $correct) {
                    $correctDatas[] = [
                        'status' => $page,
                        'name' => $correct->workTime->user->name,
                        'date' => Carbon::parse($correct->start_time)->format('Y/m/d'),
                        'reason' => $correct->notes,
                        'correct_date' => Carbon::parse($correct->created_at)->format('Y/m/d'),
                        'detail_url' => '/attendance/' . $correct->workTime->user->id . '?date=' . Carbon::parse($correct->start_time)->format('Y-m-d'),
                    ];
                }
            }
        }

        return view('correction_list', compact('page', 'auth', 'correctDatas'));
    }
}
