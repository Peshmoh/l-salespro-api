<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\Inventory;
use App\Models\StockTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WarehouseController extends Controller
{
    public function index() {
        return response()->json(['success' => true, 'data' => Warehouse::paginate(10)]);
    }

    public function inventory($id) {
        $inventory = Inventory::with('product')
                        ->where('warehouse_id', $id)
                        ->paginate(20);

        return response()->json(['success' => true, 'data' => $inventory]);
    }

    public function transferHistory() {
        $transfers = StockTransfer::with(['fromWarehouse', 'toWarehouse', 'product'])
                        ->latest()->paginate(20);

        return response()->json(['success' => true, 'data' => $transfers]);
    }

    public function transferStock(Request $request) {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'from_warehouse_id' => 'required|exists:warehouses,id|different:to_warehouse_id',
            'to_warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $from = Inventory::where('product_id', $validated['product_id'])
                ->where('warehouse_id', $validated['from_warehouse_id'])
                ->lockForUpdate()->first();

            if (!$from || $from->quantity < $validated['quantity']) {
                throw ValidationException::withMessages(['quantity' => 'Insufficient stock.']);
            }

            $from->decrement('quantity', $validated['quantity']);

            $to = Inventory::firstOrCreate([
                'product_id' => $validated['product_id'],
                'warehouse_id' => $validated['to_warehouse_id'],
            ]);
            $to->increment('quantity', $validated['quantity']);

            $transfer = StockTransfer::create($validated);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Transfer successful',
                'data' => $transfer->load(['product', 'fromWarehouse', 'toWarehouse']),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
