<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Customer;
use App\Models\Product;
use App\Helpers\LeyscoHelpers;

class CheckCreditLimit
{
    public function handle($request, Closure $next)
    {
        // Basic structure validation
        if (!$request->filled('customer_id') || !$request->filled('items')) {
            return response()->json(['message' => 'Invalid order payload'], 422);
        }

        $customer = Customer::findOrFail($request->customer_id);
        $helper   = app(LeyscoHelpers::class);

        /* ----------------------------------------------------------
         | Calculate the order value on‑the‑fly
         |---------------------------------------------------------*/
        $orderTotal = collect($request->items)->sum(function ($row) use ($helper) {
            if (!isset($row['product_id'], $row['quantity'])) {
                return 0;                       // skip malformed rows
            }

            $product = Product::find($row['product_id']);
            if (!$product) return 0;            // skip missing products

            $lineSub = $product->price * $row['quantity'];
            $lineTax = $helper->calculateTax($lineSub, $product->tax_rate);

            return $lineSub + $lineTax;         // line total (no discount yet)
        });

        /* ----------------------------------------------------------
         | Credit‑limit check
         |---------------------------------------------------------*/
        $wouldBeBalance = $customer->current_balance + $orderTotal;

        if ($wouldBeBalance > $customer->credit_limit) {
            return response()->json([
                'message' => 'Credit limit exceeded',
                'balance_after' => $wouldBeBalance,
                'credit_limit'  => $customer->credit_limit,
            ], 422);
        }

        return $next($request);
    }
}
