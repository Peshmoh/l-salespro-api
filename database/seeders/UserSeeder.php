<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'username' => 'LEYS-1001',
                'email' => 'david.kariuki@leysco.co.ke',
                'password' => Hash::make('SecurePass123!'),
                'first_name' => 'David',
                'last_name' => 'Kariuki',
                'role' => 'Sales Manager',
                'permissions' => json_encode([
                    'view_all_sales',
                    'create_sales',
                    'approve_sales',
                    'manage_inventory',
                ]),
                'status' => 'active',
            ],
            [
                'username' => 'LEYS-1002',
                'email' => 'jane.njoki@leysco.co.ke',
                'password' => Hash::make('SecurePass456!'),
                'first_name' => 'Jane',
                'last_name' => 'Njoki',
                'role' => 'Sales Representative',
                'permissions' => json_encode([
                    'view_own_sales',
                    'create_sales',
                    'view_inventory',
                ]),
                'status' => 'active',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
