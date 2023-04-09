<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'view ujian']);
        Permission::create(['name' => 'create ujian']);
        Permission::create(['name' => 'update ujian']);
        Permission::create(['name' => 'delete ujian']);

        // create roles and assign created permissions

        // this can be done as separate statements
        // $role = Role::create(['name' => 'admin']);
        // $role->givePermissionTo('edit articles');

        // or may be done by chaining
        $role = Role::create(['name' => 'admin'])
            ->givePermissionTo(['view ujian', 'create ujian', 'update ujian', 'delete ujian']);

        // $role = Role::create(['name' => 'super-admin']);
        // $role->givePermissionTo(Permission::all());
    }
}
