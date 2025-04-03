<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => '一郎',
                'email' => 'ichirou@example.com',
                'password' => Hash::make('password'),
                'status_id' => '1',
            ],
            [
                'name' => '二郎',
                'email' => 'jirou@example.com',
                'password' => Hash::make('password'),
                'status_id' => '1',
            ],
            [
                'name' => '三郎',
                'email' => 'saburou@example.com',
                'password' => Hash::make('password'),
                'status_id' => '1',
            ],
            [
                'name' => '四郎',
                'email' => 'shirou@example.com',
                'password' => Hash::make('password'),
                'status_id' => '1',
            ],
            [
                'name' => '五郎',
                'email' => 'gorou@example.com',
                'password' => Hash::make('password'),
                'status_id' => '1',
            ],
        ];

        User::insert($users);
    }
}
