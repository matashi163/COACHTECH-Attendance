<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Status;
use App\Models\User;
use App\Models\WorkTime;
use App\Models\BreakTime;

class AttendanceDetailTest extends TestCase
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

        $workTime = WorkTime::create([
            'user_id' => $user->id,
            'start_time' => $now->copy()->setTime(9, 0, 0),
            'finish_time' => $now->copy()->setTime(18, 0, 0),
        ]);

        BreakTime::create([
            'work_time_id' => $workTime->id,
            'start_time' => $now->copy()->setTime(12, 0, 0),
            'finish_time' => $now->copy()->setTime(13, 0, 0),
        ]);

        $this->actingAs($user);
    }

    public function test_view_name()
    {
        $now = Carbon::now();

        $response = $this->get('/attendance/' . auth()->id() . '?date=' . $now->format('Y-m-d'));

        $response->assertStatus(200);

        $response->assertSee('一郎');
    }

    public function test_view_date()
    {
        $now = Carbon::now();

        $response = $this->get('/attendance/' . auth()->id() . '?date=' . $now->format('Y-m-d'));

        $response->assertStatus(200);

        $response->assertSee($now->format('Y年'));
        $response->assertSee($now->isoformat('M月D日'));
    }

    public function test_view_work_time()
    {
        $now = Carbon::now();

        $response = $this->get('/attendance/' . auth()->id() . '?date=' . $now->format('Y-m-d'));

        $response->assertStatus(200);

        $response->assertSee('09:00');
        $response->assertSee('18:00');
    }

    public function test_view_break_time()
    {
        $now = Carbon::now();

        $response = $this->get('/attendance/' . auth()->id() . '?date=' . $now->format('Y-m-d'));

        $response->assertStatus(200);

        $response->assertSee('12:00');
        $response->assertSee('13:00');
    }
}
