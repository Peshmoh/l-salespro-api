<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 👉  Register all individual seeders here
        $this->call([
            UserSeeder::class,
            // ProductSeeder::class,
            // CustomerSeeder::class,
            // WarehouseSeeder::class,
        ]);
    }
}
