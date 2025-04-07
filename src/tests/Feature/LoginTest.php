<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Models\Status;
use App\Models\User;

class LoginTest extends TestCase
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

        User::create([
            'id' => '1',
            'name' => '一郎',
            'email' => 'ichirou@example.com',
            'password' => Hash::make('password'),
            'status_id' => '1',
        ]);
    }

    public function test_login_email_validation()
    {
        $response = $this->postJson('/login', [
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['email']);

        $response->assertJson([
            'errors' => [
                'email' => ['メールアドレスを入力してください'],
            ],
        ]);
    }

    public function test_login_password_validation()
    {
        $response = $this->postJson('/login', [
            'email' => 'ichirou@example.com',
            'password' => '',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['password']);

        $response->assertJson([
            'errors' => [
                'password' => ['パスワードを入力してください'],
            ],
        ]);
    }

    public function test_login_different_validation()
    {
        $response = $this->postJson('/login', [
            'email' => 'jirou@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['email']);

        $response->assertJson([
            'errors' => [
                'email' => ['ログイン情報が登録されていません'],
            ],
        ]);
    }

    public function test_login_success()
    {
        $response = $this->postJson('/login', [
            'email' => 'ichirou@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $this->assertAuthenticated();
    }
}
