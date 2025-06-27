<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Permission
        $manageInventory = Permission::firstOrCreate(['name' => 'manage_inventory']);

        // 2. Role with permission
        $manager = Role::firstOrCreate(['name' => 'Sales Manager']);
        $manager->syncPermissions([$manageInventory]);

        // 3. Assign role to seeded manager user
        $user = User::where('email', 'david.kariuki@leysco.co.ke')->first();
        if ($user && !$user->hasRole('Sales Manager')) {
            $user->assignRole('Sales Manager');
        }
    }
}
