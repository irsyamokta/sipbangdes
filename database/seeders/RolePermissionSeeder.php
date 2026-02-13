<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // Permissions
        Permission::firstOrCreate(['name' => 'manage master data']);
        Permission::firstOrCreate(['name' => 'give approval']);
        Permission::firstOrCreate(['name' => 'give review']);
        Permission::firstOrCreate(['name' => 'send rab']);
        Permission::firstOrCreate(['name' => 'forward rab']);
        Permission::firstOrCreate(['name' => 'manage rab']);
        Permission::firstOrCreate(['name' => 'manage take of sheet']);
        Permission::firstOrCreate(['name' => 'manage worker category']);
        Permission::firstOrCreate(['name' => 'manage ahsp']);
        Permission::firstOrCreate(['name' => 'manage project']);
        Permission::firstOrCreate(['name' => 'manage progress']);
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'download rab']);

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $planner = Role::firstOrCreate(['name' => 'planner']);
        $approver = Role::firstOrCreate(['name' => 'approver']);
        $reviewer = Role::firstOrCreate(['name' => 'reviewer']);

        // Grant permissions
        $admin->givePermissionTo([
            'manage master data',
            'manage rab',
            'manage take of sheet',
            'manage worker category',
            'manage ahsp',
            'manage project',
            'manage users',
        ]);
        $planner->givePermissionTo([
            'manage master data',
            'send rab',
            'manage take of sheet',
            'manage worker category',
            'manage ahsp',
            'manage project',
            'download rab',
        ]);
        $approver->givePermissionTo([
            'give approval',
            'give review',
        ]);
        $reviewer->givePermissionTo([
            'give review',
            'manage progress',
        ]);
    }
}
