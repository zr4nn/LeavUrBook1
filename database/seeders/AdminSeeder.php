<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@leavurbook.com'],
            [
                'name'     => 'Administrator',
                'username' => 'admin',
                'phone'    => null,
                'role'     => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );
    }
}