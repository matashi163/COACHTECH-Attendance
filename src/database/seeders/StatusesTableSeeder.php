<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            [
                'status' => '勤務外',
            ],
            [
                'status' => '勤務中',
            ],
            [
                'status' => '休憩中',
            ],
            [
                'status' => '退勤済',
            ],
        ];

        Status::insert($statuses);
    }
}
