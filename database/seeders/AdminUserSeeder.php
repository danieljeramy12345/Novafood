<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@novafood.com');
        $adminPassword = env('ADMIN_PASSWORD', 'Admin12345!');
        $adminName = env('ADMIN_NAME', 'NovaFood Administrator');

        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => $adminName,
                'password' => Hash::make($adminPassword),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Also create a demo customer for easy testing
        $customerEmail = env('DEMO_CUSTOMER_EMAIL', 'customer@novafood.com');
        $customerPassword = env('DEMO_CUSTOMER_PASSWORD', 'Customer123!');
        User::updateOrCreate(
            ['email' => $customerEmail],
            [
                'name' => 'Juan Dela Cruz',
                'password' => Hash::make($customerPassword),
                'phone' => '+63 917 123 4567',
                'role' => 'customer',
                'is_active' => true,
                'address' => '123 Bonifacio High Street, BGC, Taguig City, Metro Manila',
                'email_verified_at' => now(),
            ]
        );
    }
}
