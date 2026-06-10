<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'first_name' => 'Super',
            'last_name'  => 'Admin',
            'email'      => 'admin@tshsems.local',
            'login_id'   => 'ADMIN001',
            'password'   => Hash::make('password123'),
            'role'       => 'super_admin',
            'is_active'  => true,
        ]);

        // Create Academic Admin
        User::create([
            'first_name' => 'Academic',
            'last_name'  => 'Admin',
            'email'      => 'academic@tshsems.local',
            'login_id'   => 'ACAD001',
            'password'   => Hash::make('password123'),
            'role'       => 'academic_admin',
            'is_active'  => true,
        ]);

        // Create Registrar Admin
        User::create([
            'first_name' => 'Registrar',
            'last_name'  => 'Admin',
            'email'      => 'registrar@tshsems.local',
            'login_id'   => 'REG001',
            'password'   => Hash::make('password123'),
            'role'       => 'registrar_admin',
            'is_active'  => true,
        ]);

        // Create Technical Admin
        User::create([
            'first_name' => 'Technical',
            'last_name'  => 'Admin',
            'email'      => 'technical@tshsems.local',
            'login_id'   => 'TECH001',
            'password'   => Hash::make('password123'),
            'role'       => 'technical_admin',
            'is_active'  => true,
        ]);
    }
}
