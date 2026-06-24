<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed default admin account
        $adminEmail = env('ADMIN_EMAIL', 'admin@stem.local');
        $adminPassword = env('ADMIN_PASSWORD', 'Admin@1234');

        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'role' => 'admin',
                'password' => Hash::make($adminPassword),
            ]
        );
    }
}
