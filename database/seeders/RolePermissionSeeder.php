<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'manage roles']);
        Permission::create(['name'=> 'manage users']);
        Permission::create(['name'=> 'manage entities']);
        Permission::create(['name'=> 'view adminpage']);
        Permission::create(['name'=> 'view tutorpage']);
        Permission::create(['name'=> 'view studentpage']);
        Permission::create(['name'=> 'view course']);
        Permission::create(['name'=> 'create course']);
        Permission::create(['name'=> 'edit course']);
        Permission::create(['name'=> 'delete course']);
        Permission::create(['name'=> 'view schedule']);
        Permission::create(['name'=> 'create schedule']);
        Permission::create(['name'=> 'edit schedule']);
        Permission::create(['name'=> 'delete schedule']);
        Permission::create(['name'=> 'view material']);
        Permission::create(['name'=> 'upload material']);
        Permission::create(['name'=> 'edit material']);
        Permission::create(['name'=> 'delete material']);
        Permission::create(['name'=> 'create assignment']);
        Permission::create(['name'=> 'submit assignments']);
        Permission::create(['name'=> 'grade assignments']);
        Permission::create(['name'=> 'action logs']);

        $superadmin = Role::create(['name' => 'Super Admin']);
        $admin = Role::create(['name' => 'Admin']);
        $tutor = Role::create(['name' => 'Tutor']);
        $student = Role::create(['name' => 'Student']);

        $superadmin->givePermissionTo(Permission::all());
        $admin->givePermissionTo(Permission::all());
        $tutor->givePermissionTo(['view tutorpage', 'view course', 'view schedule', 'view material', 'upload material', 'edit material', 'delete material', 'create assignment', 'grade assignments', 'action logs']);
        $student->givePermissionTo(['view studentpage', 'view course', 'view material', 'view schedule', 'submit assignments']);
    }
}
