<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure the Super Admin role exists
        $superAdminRole = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        // 👤 Create your Super Admin account
        $superAdmin = User::firstOrCreate(
            ['email' => 'jonrhem10@gmail.com'],
            [
                'name' => 'Johnrem De Guzman',
                'password' => Hash::make('johnrem10'),
            ]
        );

        // Assign the Super Admin role
        $superAdmin->assignRole($superAdminRole);

        // 🧑‍💻 Generate 20 random users
        User::factory()->count(20)->create();
    }
}
