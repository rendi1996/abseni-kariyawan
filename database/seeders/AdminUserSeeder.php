<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate([
            'email' => 'yrendi466@gmail.com',
        ], [
            'name' => 'Admin',
            'password' => Hash::make('admin317724'),
            'role' => 'admin',
        ]);

        User::updateOrCreate([
            'email' => 'user@example.com',
        ], [
            'name' => 'User',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}