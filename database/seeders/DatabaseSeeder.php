<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@mq-alamin.com',
            'role' => 'admin',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_verified' => true,
        ]);
        User::create([
            'name' => 'Keuangan',
            'email' => 'keuangan@mq-alamin.com',
            'role' => 'keuangan',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_verified' => true,
        ]);
    }
}
