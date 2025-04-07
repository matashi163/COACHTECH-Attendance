<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Status;
use App\Models\User;

class ViewTimeTest extends TestCase
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

    public function test_view_time()
    {
        $response = $this->get('/attendance');

        $response->assertStatus(200);

        $now = Carbon::now();

        $response->assertSee($now->isoFormat('YYYY年MM月DD日(ddd)'));
        $response->assertSee($now->format('H:i'));
    }
}
