<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@ibralms.local'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('ChangeMe123!'),
                'is_active' => true,
            ]
        );

        $admin->assignRole('super-admin');
    }
}