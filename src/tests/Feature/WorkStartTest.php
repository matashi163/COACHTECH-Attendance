<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Status;
use App\Models\User;

class WorkStartTest extends TestCase
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

        $this->actingAs($user);
    }

    public function test_button()
    {
        $response = $this->get('/attendance');

        $response->assertStatus(200);

        $response->assertSee('出勤');

        $response = $this->get('/attendance/work/start');
        $response = $this->get('/attendance');

        $response->assertStatus(200);

        $response->assertSee('勤務中');
    }

    public function test_only_once()
    {
        $response = $this->get('/attendance/work/start');
        $response = $this->get('/attendance/work/finish');
        $response = $this->get('/attendance');

        $response->assertStatus(200);

        $response->assertDontSee('出勤');
    }

    public function test_record()
    {
        $response = $this->get('/attendance/work/start');

        $this->assertDatabaseHas('work_times', [
            'start_time' => Carbon::now(),
        ]);
    }
}
