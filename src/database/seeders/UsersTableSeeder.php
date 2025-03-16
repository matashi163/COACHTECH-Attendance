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
        ];

        User::insert($users);
    }
}
