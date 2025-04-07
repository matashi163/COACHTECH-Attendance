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
use App\Models\CorrectedWorkTime;

class AttendanceCorrectTest extends TestCase
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

    public function test_work_time_validation()
    {
        $now = Carbon::now();

        $response = $this->postJson('/attendance/' . auth()->id() . '?date=' . $now->format('Y-m-d'), [
            'work_start' => '09:00',
            'work_finish' => '08:00',
            'break_start' => ['12:00'],
            'break_finish' => ['13:00'],
            'notes' => 'test',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['work_finish']);

        $response->assertJson([
            'errors' => [
                'work_finish' => ['出勤時間もしくは退勤時間が不適切な値です'],
            ],
        ]);
    }

    public function test_break_start_validation()
    {
        $now = Carbon::now();

        $response = $this->postJson('/attendance/' . auth()->id() . '?date=' . $now->format('Y-m-d'), [
            'work_start' => '09:00',
            'work_finish' => '18:00',
            'break_start' => ['19:00'],
            'break_finish' => ['13:00'],
            'notes' => 'test',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['break_start.0']);

        $response->assertJson([
            'errors' => [
                'break_start.0' => ['出勤時間もしくは退勤時間が不適切な値です'],
            ],
        ]);
    }

    public function test_break_finish_validation()
    {
        $now = Carbon::now();

        $response = $this->postJson('/attendance/' . auth()->id() . '?date=' . $now->format('Y-m-d'), [
            'work_start' => '09:00',
            'work_finish' => '18:00',
            'break_start' => ['12:00'],
            'break_finish' => ['19:00'],
            'notes' => 'test',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['break_finish.0']);

        $response->assertJson([
            'errors' => [
                'break_finish.0' => ['出勤時間もしくは退勤時間が不適切な値です'],
            ],
        ]);
    }

    public function test_notes_validation()
    {
        $now = Carbon::now();

        $response = $this->postJson('/attendance/' . auth()->id() . '?date=' . $now->format('Y-m-d'), [
            'work_start' => '09:00',
            'work_finish' => '18:00',
            'break_start' => ['12:00'],
            'break_finish' => ['13:00'],
            'notes' => '',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['notes']);

        $response->assertJson([
            'errors' => [
                'notes' => ['備考を記入してください'],
            ],
        ]);
    }

    public function test_success()
    {
        $now = Carbon::now();

        $response = $this->postJson('/attendance/' . auth()->id() . '?date=' . $now->format('Y-m-d'), [
            'work_start' => '09:00',
            'work_finish' => '18:00',
            'break_start' => ['12:00'],
            'break_finish' => ['13:00'],
            'notes' => 'test',
        ]);

        $response->assertStatus(302);

        $response = $this->get('/stamp_correction_request/list?page=approving');

        $response->assertSee('test');

        CorrectedWorkTime::first()->update([
            'permission' => true,
        ]);

        $response = $this->get('/stamp_correction_request/list?page=approved');

        $response->assertSee('test');
    }

    public function test_transition_detail()
    {
        $now = Carbon::now();

        $response = $this->get('/attendance/' . auth()->id() . '?date=' . $now->format('Y-m-d'));

        $response->assertStatus(200);
    }
}
