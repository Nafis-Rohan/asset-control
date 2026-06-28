<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'IT Admin',
            'email' => 'admin@assetmanager.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department' => 'IT',
            'email_verified_at' => now(),
        ]);

        $manager = User::create([
            'name' => 'Department Manager',
            'email' => 'manager@assetmanager.test',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'department' => 'IT',
            'email_verified_at' => now(),
        ]);

        $employee = User::create([
            'name' => 'Jane Employee',
            'email' => 'employee@assetmanager.test',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'department' => 'IT',
            'email_verified_at' => now(),
        ]);

        $employee2 = User::create([
            'name' => 'John Employee',
            'email' => 'john@assetmanager.test',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'department' => 'HR',
            'email_verified_at' => now(),
        ]);

        Asset::create([
            'name' => 'MacBook Pro 16',
            'serial_number' => 'MBP-2024-001',
            'category' => 'Laptop',
            'status' => 'assigned',
            'user_id' => $employee->id,
        ]);

        Asset::create([
            'name' => 'Dell UltraSharp 27',
            'serial_number' => 'MON-2024-042',
            'category' => 'Monitor',
            'status' => 'available',
        ]);

        Asset::create([
            'name' => 'Microsoft 365 License',
            'serial_number' => 'LIC-MS365-100',
            'category' => 'License',
            'status' => 'assigned',
            'user_id' => $employee->id,
        ]);

        Asset::create([
            'name' => 'ThinkPad X1 Carbon',
            'serial_number' => 'TPX-2023-088',
            'category' => 'Laptop',
            'status' => 'maintenance',
        ]);

        AssetRequest::create([
            'user_id' => $employee->id,
            'requested_item' => 'External Webcam',
            'reason' => 'Need a webcam for daily video conferences with remote team members.',
            'status' => 'pending',
        ]);

        AssetRequest::create([
            'user_id' => $employee2->id,
            'requested_item' => 'Standing Desk',
            'reason' => 'Ergonomic improvement for long hours at the workstation.',
            'status' => 'pending',
        ]);

        AssetRequest::create([
            'user_id' => $employee->id,
            'requested_item' => 'Wireless Mouse',
            'reason' => 'Current mouse is malfunctioning.',
            'status' => 'approved',
            'manager_id' => $manager->id,
        ]);
    }
}
