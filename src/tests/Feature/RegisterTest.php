<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Models\Status;
use App\Models\User;

class RegisterTest extends TestCase
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
    }

    public function test_register_name_validation()
    {
        $response = $this->postJson('/register', [
            'name' => '',
            'email' => 'ichirou@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['name']);

        $response->assertJson([
            'errors' => [
                'name' => ['お名前を入力してください'],
            ],
        ]);
    }

    public function test_register_email_validation()
    {
        $response = $this->postJson('/register', [
            'name' => '一郎',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['email']);

        $response->assertJson([
            'errors' => [
                'email' => ['メールアドレスを入力してください'],
            ],
        ]);
    }

    public function test_register_password_reqiured_validation()
    {
        $response = $this->postJson('/register', [
            'name' => '一郎',
            'email' => 'ichirou@example.com',
            'password' => '',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['password']);

        $response->assertJson([
            'errors' => [
                'password' => ['パスワードを入力してください'],
            ],
        ]);
    }

    public function test_register_password_min_validation()
    {
        $response = $this->postJson('/register', [
            'name' => '一郎',
            'email' => 'ichirou@example.com',
            'password' => 'passwor',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['password']);

        $response->assertJson([
            'errors' => [
                'password' => ['パスワードは8文字以上で入力してください'],
            ],
        ]);
    }

    public function test_register_password_confirmation_validation()
    {
        $response = $this->postJson('/register', [
            'name' => '一郎',
            'email' => 'ichirou@example.com',
            'password' => 'password',
            'password_confirmation' => 'different_password',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['password_confirmation']);

        $response->assertJson([
            'errors' => [
                'password_confirmation' => ['パスワードと一致しません'],
            ],
        ]);
    }

    public function test_register_success()
    {
        $response = $this->postJson('/register', [
            'name' => '一郎',
            'email' => 'ichirou@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => '一郎',
            'email' => 'ichirou@example.com',
        ]);

        $this->assertTrue(Hash::check('password', User::where('email', 'ichirou@example.com')->first()->password));
    }
}
