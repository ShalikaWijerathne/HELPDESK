<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // One demo account per role — use these to log in and explore the system
        $userRole  = Role::where('name', 'User')->first();
        $staffRole = Role::where('name', 'IT Staff')->first();
        $adminRole = Role::where('name', 'Admin')->first();

        // Demo User account
        User::firstOrCreate(
            ['email' => 'user@helpdesk.test'],
            [
                'name'     => 'Demo User',
                'password' => Hash::make('password'),
                'role_id'  => $userRole?->id,
            ]
        );

        // Demo IT Staff account
        User::firstOrCreate(
            ['email' => 'staff@helpdesk.test'],
            [
                'name'     => 'Demo Staff',
                'password' => Hash::make('password'),
                'role_id'  => $staffRole?->id,
            ]
        );

        // Demo Admin account
        User::firstOrCreate(
            ['email' => 'admin@helpdesk.test'],
            [
                'name'     => 'Demo Admin',
                'password' => Hash::make('password'),
                'role_id'  => $adminRole?->id,
            ]
        );
    }
}
