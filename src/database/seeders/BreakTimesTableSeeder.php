<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\WorkTime;
use App\Models\BreakTime;

class BreakTimesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $breakTimes = [];
        foreach (WorkTime::all() as $workTime) {
            $breakTimes[] = [
                'work_time_id' => $workTime->id,
                'start_time' => Carbon::parse($workTime->start_time)->setTime(12, 0, 0),
                'finish_time' => Carbon::parse($workTime->start_time)->setTime(13, 0, 0),
            ];
        }

        BreakTime::insert($breakTimes);
    }
}
