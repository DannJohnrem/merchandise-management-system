<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'view inventory',
            'create inventory',
            'edit inventory',
            'delete inventory',
            'delete permissions',
            'view permissions',
            'create permissions',
            'edit permissions',
            'view it-leasing',
            'create it-leasing',
            'edit it-leasing',
            'delete it-leasing',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $roles = [
            'Super Admin' => Permission::all(),
            'Admin' => Permission::whereIn('name', [
                'view users',
                'create users',
                'edit users',
                'view inventory',
                'create inventory',
                'edit inventory',
            ])->get(),
            'User' => Permission::whereIn('name', [
                'view inventory',
            ])->get(),
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }

        $user = User::first();
        if ($user) {
            $user->assignRole('Super Admin');
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
