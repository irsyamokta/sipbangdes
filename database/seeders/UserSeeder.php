<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Administrator',
                'password' => Hash::make('Password123!'),
                'email_verified_at' => now(),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $admin->assignRole('admin');

        $planner = User::updateOrCreate(
            ['email' => 'planner@example.com'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Planner',
                'password' => Hash::make('Password123!'),
                'email_verified_at' => now(),
                'role' => 'planner',
                'is_active' => true,
            ]
        );

        $planner->assignRole('planner');

        $reviewer = User::updateOrCreate(
            ['email' => 'reviewer@example.com'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Reviewer',
                'password' => Hash::make('Password123!'),
                'email_verified_at' => now(),
                'role' => 'reviewer',
                'is_active' => true,
            ]
        );

        $reviewer->assignRole('reviewer');

        $approver = User::updateOrCreate(
            ['email' => 'approver@example.com'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Approver',
                'password' => Hash::make('Password123!'),
                'email_verified_at' => now(),
                'role' => 'approver',
                'is_active' => true,
            ]
        );

        $approver->assignRole('approver');
    }
}
