<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'sku'               => 'SF-MAX-20W50',
                'name'              => 'SuperFuel Max 20W-50',
                'category'          => 'Engine Oils',
                'subcategory'       => 'Mineral Oils',
                'description'       => 'High-performance mineral oil for heavy-duty engines',
                'price'             => 4500.00,
                'tax_rate'          => 16.0,
                'unit'              => 'Liter',
                'packaging'         => '5L Container',
                'min_order_quantity'=> 1,
                'reorder_level'     => 30,
            ],
            [
                'sku'               => 'ED-SYN-5W30',
                'name'              => 'EcoDrive Synthetic 5W-30',
                'category'          => 'Engine Oils',
                'subcategory'       => 'Synthetic Oils',
                'description'       => 'Fully synthetic oil for modern passenger vehicles',
                'price'             => 7200.00,
                'tax_rate'          => 16.0,
                'unit'              => 'Liter',
                'packaging'         => '4L Container',
                'min_order_quantity'=> 1,
                'reorder_level'     => 40,
            ],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(['sku' => $p['sku']], $p);
        }
    }
}
