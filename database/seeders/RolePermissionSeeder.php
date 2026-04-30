<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [

            /* General */
            'dashboard.view',

            /* Users */
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.search',

            /* Project */
            'project.view',
            'project.create',
            'project.edit',
            'project.delete',
            'project.search',
            'project.filter',

            /* Progress */
            'progress.view',
            'progress.create',
            'progress.edit',
            'progress.delete',

            /* Master Data */
            'unit.view','unit.create','unit.edit','unit.delete','unit.search',
            'material.view','material.create','material.edit','material.delete','material.search',
            'wage.view','wage.create','wage.edit','wage.delete','wage.search',
            'tool.view','tool.create','tool.edit','tool.delete','tool.search',

            /* AHSP */
            'ahsp.view',
            'ahsp.create',
            'ahsp.edit',
            'ahsp.delete',
            'ahsp.search',

            /* Category */
            'category.view',
            'category.create',
            'category.edit',
            'category.delete',
            'category.search',

            /* TOS */
            'tos.view',
            'tos.create',
            'tos.edit',
            'tos.delete',
            'tos.search',
            'tos.filter',

            /* RAB */
            'rab.view',
            'rab.create',
            'rab.edit',
            'rab.delete',
            'rab.operational.create',
            'rab.operational.edit',
            'rab.operational.delete',
            'rab.generate.ai',
            'rab.download',

            /* RAB Flow */
            'rab.send',
            'rab.review',
            'rab.forward',
            'rab.approve',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        /* Roles */
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $planner = Role::firstOrCreate(['name' => 'planner']);
        $reviewer = Role::firstOrCreate(['name' => 'reviewer']);
        $approver = Role::firstOrCreate(['name' => 'approver']);

        /* Admin */
        $admin->syncPermissions(Permission::all());

        /* Planner (Kaur Perencanaan) */
        $planner->syncPermissions([
            'dashboard.view',

            'project.view','project.create','project.edit','project.delete','project.search','project.filter',

            'tos.view','tos.create','tos.edit','tos.delete','tos.search','tos.filter',

            'rab.view','rab.create','rab.edit','rab.generate.ai','rab.download',
            'rab.operational.create','rab.operational.edit','rab.operational.delete',
            'rab.send',

            'ahsp.view','ahsp.create','ahsp.edit','ahsp.delete','ahsp.search',
            'category.view','category.create','category.edit','category.delete','category.search',

            'progress.view',
        ]);

        /* Reviewer (Sekretaris Desa) */
        $reviewer->syncPermissions([
            'dashboard.view',

            'project.view', 'project.search', 'project.filter',
            'tos.view', 'tos.search', 'tos.filter',
            'rab.view',

            'rab.review',
            'rab.forward',

            'ahsp.view', 'ahsp.search',
            'category.view', 'category.search',

            'progress.view',
        ]);

        /* Approver (Kepala Desa) */
        $approver->syncPermissions([
            'dashboard.view',

            'project.view', 'project.search', 'project.filter',
            'tos.view', 'tos.search', 'tos.filter',
            'rab.view',

            'rab.review',
            'rab.approve',

            'ahsp.view', 'ahsp.search',
            'category.view', 'category.search',

            'progress.view',
        ]);
    }
}