<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::firstOrCreate(
            ['email' => 'admin@taxicrm.com'],
            [
                'name' => 'System Admin',
                'login_id' => 'ADMIN01',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'active',
                'phone' => '9876543210',
            ]
        );

        // Head / Team Lead
        User::firstOrCreate(
            ['email' => 'tl@taxicrm.com'],
            [
                'name' => 'Vikram TeamLead',
                'login_id' => 'TL001',
                'password' => Hash::make('tl123'),
                'role' => 'head',
                'status' => 'active',
                'phone' => '9876543212',
            ]
        );

        // Accountant
        User::firstOrCreate(
            ['email' => 'accounts@taxicrm.com'],
            [
                'name' => 'Accounts Manager',
                'login_id' => 'ACCT01',
                'password' => Hash::make('accounts123'),
                'role' => 'accountant',
                'status' => 'active',
                'phone' => '9876543211',
            ]
        );
    }
}
