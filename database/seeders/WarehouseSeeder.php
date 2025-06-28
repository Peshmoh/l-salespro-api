<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;
use App\Models\Product;      // 👈 add
use App\Models\Inventory;    // 👈 add

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = [
            [
                'code'  => 'NCW',
                'name'  => 'Nairobi Central Warehouse',
                'type'  => 'Main',
                'address' => 'Enterprise Road, Industrial Area, Nairobi',
                'manager_email' => 'warehouse.nairobi@leysco.co.ke',
                'phone'  => '+254-20-5551234',
                'capacity' => 50_000,
                'latitude' => -1.308971,
                'longitude'=> 36.851523,
            ],
            [
                'code'  => 'MRW',
                'name'  => 'Mombasa Regional Warehouse',
                'type'  => 'Regional',
                'address' => 'Port Reitz Road, Changamwe, Mombasa',
                'manager_email' => 'warehouse.mombasa@leysco.co.ke',
                'phone'  => '+254-41-2224567',
                'capacity' => 30_000,
                'latitude' => -4.034396,
                'longitude'=> 39.647446,
            ],
        ];

        foreach ($warehouses as $w) {
            $wh = Warehouse::updateOrCreate(['code' => $w['code']], $w);

            // 🔹 give every product an opening balance of 1 000 units
            foreach (Product::all() as $product) {
                Inventory::updateOrCreate(
                    ['warehouse_id' => $wh->id, 'product_id' => $product->id],
                    ['quantity'     => 1_000]
                );
            }
        }
    }
}
