<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Status;
use App\Models\User;
use App\Models\Admin;
use App\Models\WorkTime;
use App\Models\BreakTime;

class AdminAttendanceListTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Status::insert([
            [
                'id' => '1',
                'status' => '勤務外',
            ],
            [
                'id' => '2',
                'status' => '勤務中',
            ],
            [
                'id' => '3',
                'status' => '休憩中',
            ],
            [
                'id' => '4',
                'status' => '退勤済',
            ],
        ]);

        User::insert([
            [
                'id' => '1',
                'name' => '一郎',
                'email' => 'ichirou@example.com',
                'password' => Hash::make('password'),
                'status_id' => '1',
            ],
            [
                'id' => '2',
                'name' => '二郎',
                'email' => 'jirou@example.com',
                'password' => Hash::make('password'),
                'status_id' => '1',
            ],
            [
                'id' => '3',
                'name' => '三郎',
                'email' => 'saburou@example.com',
                'password' => Hash::make('password'),
                'status_id' => '1',
            ],
            [
                'id' => '4',
                'name' => '四郎',
                'email' => 'shirou@example.com',
                'password' => Hash::make('password'),
                'status_id' => '1',
            ],
            [
                'id' => '5',
                'name' => '五郎',
                'email' => 'gorou@example.com',
                'password' => Hash::make('password'),
                'status_id' => '1',
            ]
        ]);

        $admin = Admin::create([
            'id' => '1',
            'name' => '管理者',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $now = Carbon::now();

        $workTimes = [];
        foreach (User::all() as $user) {
            $workTimes[] = [
                'user_id' => $user->id,
                'start_time' => $now->copy()->setTime(9, 0, 0),
                'finish_time' => $now->copy()->setTime(18, 0, 0),
            ];
        }

        WorkTime::insert($workTimes);

        $breakTimes = [];
        foreach (WorkTime::all() as $workTime) {
            $breakTimes[] = [
                'work_time_id' => $workTime->id,
                'start_time' => $now->copy()->setTime(12, 0, 0),
                'finish_time' => $now->copy()->setTime(13, 0, 0),
            ];
        }

        BreakTime::insert($breakTimes);

        $this->actingAs($admin, 'admin');
    }

    public function test_view_attendance()
    {
        $response = $this->get('/admin/attendance/list');

        $response->assertStatus(200);

        foreach (User::all() as $user) {
            $response->assertSee($user->name);
            $response->assertSee(Carbon::parse($user->workTimes()->first()->start_time)->format('H:i'));
            $response->assertSee(Carbon::parse($user->workTimes()->first()->finish_time)->format('H:i'));
        }
    }

    public function test_view_date()
    {
        $now = Carbon::now();

        $response = $this->get('/admin/attendance/list');

        $response->assertStatus(200);

        $response->assertSee($now->format('Y/m/d'));
    }

    public function test_view_previous_date()
    {
        $now = Carbon::now();

        $response = $this->get('/admin/attendance/list?date=' . $now->copy()->subDay()->startOfDay());

        $response->assertStatus(200);

        $response->assertSee($now->copy()->subDay()->format('Y/m/d'));
    }

    public function test_view_next_date()
    {
        $now = Carbon::now();

        $response = $this->get('/admin/attendance/list?date=' . $now->copy()->addDay()->startOfDay());

        $response->assertStatus(200);

        $response->assertSee($now->copy()->addDay()->format('Y/m/d'));
    }
}
