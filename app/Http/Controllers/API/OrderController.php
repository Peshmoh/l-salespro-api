<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use App\Models\Order;
use App\Models\Product;
use App\Helpers\LeyscoHelpers;

class OrderController extends Controller
{
    /* ===============================================================
     | GET /api/v1/orders
     |===============================================================*/
    public function index()
    {
        $orders = Order::with('customer')
                       ->latest()
                       ->paginate(10);

        return response()->json([
            'success' => true,
            'data'    => $orders,
        ]);
    }

    /* ===============================================================
     | GET /api/v1/orders/{id}
     |===============================================================*/
    public function show(int $id)
    {
        $order = Order::with(['items.product', 'customer'])
                      ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $order,
        ]);
    }

    /* ===============================================================
     | POST /api/v1/orders
     |===============================================================*/
    public function store(StoreOrderRequest $request, OrderService $service)
    {
        $order = $service->createOrder($request->validated());

        return response()->json([
            'success' => true,
            'data'    => $order,
        ], 201);
    }

    /* ===============================================================
     | PUT /api/v1/orders/{id}/status
     |===============================================================*/
    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Confirmed,Processing,Shipped,Delivered,Cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->input('status')]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated',
            'data'    => $order->only(['id', 'order_number', 'status']),
        ]);
    }

    /* ===============================================================
     | GET /api/v1/orders/{id}/invoice
     |===============================================================*/
    public function invoice(int $id)
    {
        $order = Order::with(['items.product', 'customer'])
                      ->findOrFail($id);

        // For now just return the same payload; later you can format / PDF
        return response()->json([
            'success' => true,
            'data'    => $order,
        ]);
    }

    /* ===============================================================
     | POST /api/v1/orders/calculate-total
     | Preview totals without persisting an order
     |===============================================================*/
    public function calculateTotal(Request $request)
    {
        $request->validate([
            'discount'              => 'nullable|numeric|min:0',
            'items'                 => 'required|array|min:1',
            'items.*.product_id'    => 'required|exists:products,id',
            'items.*.quantity'      => 'required|integer|min:1',
        ]);

        $subtotal = 0;
        $tax      = 0;
        $helper   = app(LeyscoHelpers::class);

        foreach ($request->input('items') as $item) {
            $product  = Product::findOrFail($item['product_id']);
            $lineSub  = $product->price * $item['quantity'];
            $lineTax  = $helper->calculateTax($lineSub, $product->tax_rate);

            $subtotal += $lineSub;
            $tax      += $lineTax;
        }

        $discount = $request->input('discount', 0);
        $total    = $subtotal + $tax - $discount;

        return response()->json([
            'success' => true,
            'data'    => compact('subtotal', 'tax', 'discount', 'total'),
        ]);
    }
}
