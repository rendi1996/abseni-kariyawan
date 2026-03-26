<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employees = [
            [
                'name' => 'Andi Saputra',
                'email' => 'andi.saputra@attendance.test',
                'password' => 'andi12345',
                'employee_code' => 'KRY001',
                'phone' => '081200000001',
                'department' => 'Operasional',
                'position' => 'Staff Gudang',
                'address' => 'Jl. Melati No. 10, Jakarta',
                'hire_date' => '2024-01-15',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@attendance.test',
                'password' => 'budi12345',
                'employee_code' => 'KRY002',
                'phone' => '081200000002',
                'department' => 'Keuangan',
                'position' => 'Admin Finance',
                'address' => 'Jl. Kenanga No. 21, Bandung',
                'hire_date' => '2024-02-01',
            ],
            [
                'name' => 'Citra Lestari',
                'email' => 'citra.lestari@attendance.test',
                'password' => 'citra12345',
                'employee_code' => 'KRY003',
                'phone' => '081200000003',
                'department' => 'HR',
                'position' => 'HR Officer',
                'address' => 'Jl. Dahlia No. 8, Surabaya',
                'hire_date' => '2024-02-20',
            ],
            [
                'name' => 'Dewi Anggraini',
                'email' => 'dewi.anggraini@attendance.test',
                'password' => 'dewi12345',
                'employee_code' => 'KRY004',
                'phone' => '081200000004',
                'department' => 'Marketing',
                'position' => 'Digital Marketing',
                'address' => 'Jl. Anggrek No. 13, Yogyakarta',
                'hire_date' => '2024-03-10',
            ],
            [
                'name' => 'Eko Pratama',
                'email' => 'eko.pratama@attendance.test',
                'password' => 'eko12345',
                'employee_code' => 'KRY005',
                'phone' => '081200000005',
                'department' => 'IT',
                'position' => 'Support Technician',
                'address' => 'Jl. Mawar No. 2, Semarang',
                'hire_date' => '2024-04-05',
            ],
        ];

        foreach ($employees as $employee) {
            $user = User::updateOrCreate([
                'email' => $employee['email'],
            ], [
                'name' => $employee['name'],
                'password' => Hash::make($employee['password']),
                'role' => 'user',
            ]);

            Employee::updateOrCreate([
                'user_id' => $user->id,
            ], [
                'employee_code' => $employee['employee_code'],
                'phone' => $employee['phone'],
                'department' => $employee['department'],
                'position' => $employee['position'],
                'address' => $employee['address'],
                'hire_date' => $employee['hire_date'],
                'is_active' => true,
            ]);
        }
    }
}