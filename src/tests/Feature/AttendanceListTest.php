<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Status;
use App\Models\User;
use App\Models\WorkTime;

class AttendanceListTest extends TestCase
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

        $user = User::create([
            'id' => '1',
            'name' => '一郎',
            'email' => 'ichirou@example.com',
            'password' => Hash::make('password'),
            'status_id' => '1',
        ]);

        $now = Carbon::now();

        $workTimes = [];
        for ($day = $now->copy()->startOfMonth(); $day <= $now->copy()->startOfDay(); $day->addDay()) {
            $workTimes[] = [
                'user_id' => $user->id,
                'start_time' => $day->copy()->setTime(9, 0, 0),
                'finish_time' => $day->copy()->setTime(18, 0, 0),
            ];
        }

        WorkTime::insert($workTimes);

        $this->actingAs($user);
    }

    public function test_view_attendance()
    {
        $response = $this->get('/attendance/list');

        $response->assertStatus(200);

        $response->assertSee('09:00');
        $response->assertSee('18:00');
    }

    public function test_view_month()
    {
        $response = $this->get('/attendance/list');

        $response->assertStatus(200);

        $response->assertSee(Carbon::now()->format('Y/m'));
    }

    public function test_view_previous_month()
    {
        $previous = Carbon::now()->subMonth();

        $response = $this->get('/attendance/list?month=' . $previous->format('Y-m'));

        $response->assertStatus(200);

        $response->assertSee($previous->format('Y/m'));
    }

    public function test_view_next_month()
    {
        $next = Carbon::now()->addMonth();

        $response = $this->get('/attendance/list?month=' . $next->format('Y-m'));

        $response->assertStatus(200);

        $response->assertSee($next->format('Y/m'));
    }

    public function test_transition_detail()
    {
        $response = $this->get('/attendance/' . auth()->id() . '?date=' . Carbon::now()->format('Y-m-d'));

        $response->assertStatus(200);
    }
}
