<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'username'    => 'LEYS-1001',
                'email'       => 'david.kariuki@leysco.co.ke',
                'password'    => Hash::make('SecurePass123!'),
                'first_name'  => 'David',
                'last_name'   => 'Kariuki',
                'role'        => 'Sales Manager', // Just a label — not used for access control
                'status'      => 'active',
            ],
            [
                'username'    => 'LEYS-1002',
                'email'       => 'jane.njoki@leysco.co.ke',
                'password'    => Hash::make('SecurePass456!'),
                'first_name'  => 'Jane',
                'last_name'   => 'Njoki',
                'role'        => 'Sales Representative',
                'status'      => 'active',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']], // prevent duplicates
                $user
            );
        }
    }
}
