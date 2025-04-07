<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminLoginTest extends TestCase
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

        Admin::insert([
            'id' => '1',
            'name' => '管理者',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
    }

    public function test_login_email_validation()
    {
        $response = $this->postJson('/login', [
            'email' => '',
            'password' => 'password',
            'role' => 'admin',
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
            'email' => 'admin@example.com',
            'password' => '',
            'role' => 'admin',
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
            'email' => 'user@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['email']);

        $response->assertJson([
            'errors' => [
                'email' => ['管理者としてのログイン情報が登録されていません'],
            ],
        ]);
    }

    public function test_login_success()
    {
        $response = $this->postJson('/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $response->assertStatus(302);
        $this->assertAuthenticated();
    }
}
