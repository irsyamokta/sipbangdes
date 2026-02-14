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

        /* Create Permissions */
        $permissions = [

            /* Dashboard Module */
            'dashboard.view',

            /* Project Module */
            'project.view',
            'project.create',
            'project.edit',
            'project.delete',

            /* Take Off Sheet Module */
            'tos.view',
            'tos.create',
            'tos.edit',
            'tos.delete',

            /* RAB Module */
            'rab.view',
            'rab.create',
            'rab.edit',
            'rab.delete',
            'rab.send',
            'rab.forward',
            'rab.download',
            'rab.approve',
            'rab.review',

            /* Master Data Module */
            'material.view',
            'material.create',
            'material.edit',
            'material.delete',

            'tool.view',
            'tool.create',
            'tool.edit',
            'tool.delete',

            'wage.view',
            'wage.create',
            'wage.edit',
            'wage.delete',

            'unit.view',
            'unit.create',
            'unit.edit',
            'unit.delete',

            'workercategory.view',
            'workercategory.create',
            'workercategory.edit',
            'workercategory.delete',

            'ahsp.view',
            'ahsp.create',
            'ahsp.edit',
            'ahsp.delete',

            /* Progress Module */
            'progress.view',
            'progress.create',
            'progress.edit',
            'progress.delete',

            /* Users Module */
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        /* Create Roles */
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $planner = Role::firstOrCreate(['name' => 'planner']);
        $reviewer = Role::firstOrCreate(['name' => 'reviewer']);
        $approver = Role::firstOrCreate(['name' => 'approver']);

        /* Admin Permissions */
        $admin->givePermissionTo([
            'dashboard.view',

            'project.view',

            'tos.view',
            'tos.create',
            'tos.edit',
            'tos.delete',

            'rab.view',

            'material.view',
            'material.create',
            'material.edit',
            'material.delete',

            'tool.view',
            'tool.create',
            'tool.edit',
            'tool.delete',

            'wage.view',
            'wage.create',
            'wage.edit',
            'wage.delete',

            'unit.view',
            'unit.create',
            'unit.edit',
            'unit.delete',

            'workercategory.view',
            'workercategory.create',
            'workercategory.edit',
            'workercategory.delete',

            'ahsp.view',
            'ahsp.create',
            'ahsp.edit',
            'ahsp.delete',

            'progress.view',

            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
        ]);

        /* Planner Permissions */
        $planner->givePermissionTo([
            'dashboard.view',

            'project.view',
            'project.create',
            'project.edit',
            'project.delete',

            'tos.view',
            'tos.create',
            'tos.edit',
            'tos.delete',

            'rab.view',
            'rab.create',
            'rab.edit',
            'rab.send',
            'rab.download',

            'ahsp.view',
            'ahsp.create',
            'ahsp.edit',
            'ahsp.delete',

            'workercategory.view',
            'workercategory.create',
            'workercategory.edit',
            'workercategory.delete',

            'progress.view',
        ]);

        /* Reviewer Permissions */
        $reviewer->givePermissionTo([
            'dashboard.view',

            'project.view',

            'tos.view',

            'rab.view',
            'rab.forward',
            'rab.review',

            'ahsp.view',

            'workercategory.view',

            'progress.view',
            'progress.create',
            'progress.edit',
            'progress.delete',
        ]);

        /* Approver Permissions */
        $approver->givePermissionTo([
            'dashboard.view',

            'project.view',

            'tos.view',

            'rab.view',
            'rab.approve',
            'rab.review',
            'ahsp.view',

            'workercategory.view',

            'progress.view',
        ]);
    }
}
