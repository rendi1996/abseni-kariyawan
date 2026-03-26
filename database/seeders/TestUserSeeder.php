<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $testUsers = [
            [
                'name' => 'Rendi Yuniawan',
                'email' => 'rendi@bkk.test',
                'password' => 'test1234',
                'role' => 'user',
            ],
            [
                'name' => 'Ayik Saputra',
                'email' => 'ayik@bkk.test',
                'password' => 'ayik1234',
                'role' => 'user',
            ],
            [
                'name' => 'Dwi Novia',
                'email' => 'dwi@bkk.test',
                'password' => 'dwi1234',
                'role' => 'user',
            ],
            [
                'name' => 'Testing User',
                'email' => 'test.user@bkk.test',
                'password' => 'test1234',
                'role' => 'user',
            ],
        ];

        foreach ($testUsers as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'role' => $userData['role'],
                ]
            );
        }

        echo "\n✓ Test user accounts created successfully!\n";
    }
}
