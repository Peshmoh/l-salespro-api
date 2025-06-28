<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Helpers\LeyscoHelpers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderService
{
    /**
     * Create a new order with its items, tax, discount, and reserved stock.
     *
     * @param  array  $data  Validated input from StoreOrderRequest
     * @return Order
     */
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $helper = app(LeyscoHelpers::class);

            // Step 1: Create the base order
            $order = Order::create([
                'order_number'   => $helper->generateOrderNumber(),
                'customer_id'    => $data['customer_id'],
                'status'         => 'pending',
                'discount'       => $data['discount'] ?? 0,
                'subtotal'       => 0,
                'tax'            => 0,
                'total_amount'   => 0,
                'reserved_until' => Carbon::now()->addMinutes(30), // optional
            ]);

            $subtotal = 0;
            $taxTotal = 0;

            // Step 2: Create order items and calculate totals
            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = $item['quantity'];
                $unitPrice = $product->price;
                $lineSubtotal = $unitPrice * $quantity;
                $lineTax = $helper->calculateTax($lineSubtotal, $product->tax_rate);
                $lineTotal = $lineSubtotal + $lineTax;

                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $product->id,
                    'warehouse_id' => $item['warehouse_id'],
                    'quantity'     => $quantity,
                    'unit_price'   => $unitPrice,
                    'discount'     => 0, // Extendable in future
                    'tax'          => $lineTax,
                    'total'        => $lineTotal,
                ]);

                $subtotal += $lineSubtotal;
                $taxTotal += $lineTax;

                // Optional: Reserve stock here
            }

            // Step 3: Update order with final totals
            $order->update([
                'subtotal'     => $subtotal,
                'tax'          => $taxTotal,
                'total_amount' => $subtotal + $taxTotal - $order->discount,
                'status'       => 'confirmed',
            ]);

            // Step 4: Return full order with relationships
            return $order->fresh(['items.product', 'customer']);
        });
    }
}
