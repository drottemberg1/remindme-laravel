<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'alice@mail.com'],
            ['name' => 'Alice', 'password' => Hash::make('123456')]
        );

        User::updateOrCreate(
            ['email' => 'bob@mail.com'],
            ['name' => 'Bob', 'password' => Hash::make('123456')]
        );
    }
}
