<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Customer;

class CheckCreditLimit
{
    public function handle($request, Closure $next)
    {
        if (!$request->has('customer_id') || !$request->has('items')) {
            return response()->json(['message' => 'Invalid order payload'], 422);
        }

        $customer = Customer::findOrFail($request->customer_id);
        $orderTotal = collect($request->items)->sum(fn ($i) => $i['total']);

        if ($customer->current_balance + $orderTotal > $customer->credit_limit) {
            return response()->json(['message' => 'Credit limit exceeded'], 422);
        }

        return $next($request);
    }
}
