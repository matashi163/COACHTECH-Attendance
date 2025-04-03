<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\User;
use App\Models\WorkTime;

class WorkTimesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $workTimes = [];
        foreach (User::all() as $user) {
            for ($day = $now->copy()->subMonth()->startOfMonth(); $day <= $now->copy()->subDay()->startOfDay(); $day->addDay()) {
                $workTimes[] = [
                    'user_id' => $user->id,
                    'start_time' => $day->copy()->setTime(9, 0, 0),
                    'finish_time' => $day->copy()->setTime(18, 0, 0),
                ];
            }
        }

        WorkTime::insert($workTimes);
    }
}
