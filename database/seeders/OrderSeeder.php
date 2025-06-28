<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $customers = Customer::all();
            $products = Product::all();
            $warehouse = Warehouse::first(); // simple case: all orders from 1 warehouse

            foreach ($customers as $customer) {
                $order = Order::create([
                    'customer_id'   => $customer->id,
                    'order_number'  => 'ORD-' . now()->format('Y-m') . '-' . strtoupper(Str::random(3)),
                    'status'        => 'confirmed',
                    'subtotal'      => 0,
                    'discount'      => 500,
                    'tax'           => 0,
                    'total_amount'  => 0, // will calculate below
                    'created_by'    => 1, // assuming admin user ID 1
                ]);

                $itemsTotal = 0;

                foreach ($products->random(2) as $product) {
                    $quantity = rand(1, 5);
                    $price = $product->price;
                    $discount = 0.1 * $price * $quantity; // 10% line discount
                    $tax = 0.16 * ($price * $quantity - $discount);

                    $itemTotal = ($price * $quantity - $discount) + $tax;
                    $itemsTotal += $itemTotal;

                    OrderItem::create([
                        'order_id'      => $order->id,
                        'product_id'    => $product->id,
                        'warehouse_id'  => $warehouse->id,
                        'quantity'      => $quantity,
                        'unit_price'    => $price,
                        'discount'      => $discount,
                        'tax'           => $tax,
                        'total'         => $itemTotal,
                    ]);
                }

                // Update order totals
                $order->update([
                    'subtotal'      => $itemsTotal,
                    'tax'           => 0.16 * $itemsTotal,
                    'total_amount'  => $itemsTotal + (0.16 * $itemsTotal) - $order->discount,
                ]);
            }
        });
    }
}
