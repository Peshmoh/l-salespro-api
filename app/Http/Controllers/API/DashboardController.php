<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;

class DashboardController extends Controller
{
    /** ----------------------------------------------------------------
     * GET /api/dashboard/summary
     * ----------------------------------------------------------------
     *  - total_sales
     *  - total_orders
     *  - average_order_value
     *  - inventory_turnover_rate
     * ---------------------------------------------------------------- */
    public function summary()
    {
        return Cache::remember('dashboard:summary', 60, function () {
            $totalSales   = Order::sum('total_amount');
            $totalOrders  = Order::count();
            $avgOrder     = $totalOrders ? $totalSales / $totalOrders : 0;
            $turnoverRate = $this->inventoryTurnoverRate();

            return response()->json([
                'success' => true,
                'data'    => [
                    'total_sales'          => round($totalSales, 2),
                    'total_orders'         => $totalOrders,
                    'average_order_value'  => round($avgOrder, 2),
                    'inventory_turnover_rate' => round($turnoverRate, 2),
                ],
            ]);
        });
    }

    /** ----------------------------------------------------------------
     * GET /api/dashboard/sales-performance?range=week
     *  range = today|week|month|quarter|year
     * ---------------------------------------------------------------- */
    public function salesPerformance(Request $request)
    {
        $range = $request->query('range', 'month');     // default = month
        $start = $this->startDateForRange($range);

        $sales = Order::where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total_amount) as sales')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        return response()->json(['success' => true, 'data' => $sales]);
    }

    /** ----------------------------------------------------------------
     * GET /api/dashboard/inventory-status
     *  - returns product counts per category
     * ---------------------------------------------------------------- */
    public function inventoryStatus()
    {
        return Cache::remember('dashboard:inventory_status', 60, function () {
            $byCategory = Product::select('category', DB::raw('COUNT(*) as total'))
                ->groupBy('category')
                ->orderByDesc('total')
                ->get();

            return response()->json(['success' => true, 'data' => $byCategory]);
        });
    }

    /** ----------------------------------------------------------------
     * GET /api/dashboard/top-products
     *  - top 5 products by quantity sold
     * ---------------------------------------------------------------- */
    public function topProducts()
    {
        return Cache::remember('dashboard:top_products', 60, function () {
            $top = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
                ->groupBy('product_id')
                ->with('product:id,name,sku')
                ->orderByDesc('total_sold')
                ->limit(5)
                ->get()
                ->map(fn ($row) => [
                    'product_id' => $row->product_id,
                    'sku'        => $row->product->sku,
                    'name'       => $row->product->name,
                    'total_sold' => (int) $row->total_sold,
                ]);

            return response()->json(['success' => true, 'data' => $top]);
        });
    }

    /* -----------------------------------------------------------------
     | Private helpers
     * -----------------------------------------------------------------*/
    private function startDateForRange(string $range): Carbon
    {
        return match ($range) {
            'today'   => Carbon::today(),
            'week'    => Carbon::now()->startOfWeek(),
            'month'   => Carbon::now()->startOfMonth(),
            'quarter' => Carbon::now()->firstOfQuarter(),
            'year'    => Carbon::now()->startOfYear(),
            default   => Carbon::now()->startOfMonth(),
        };
    }

    /** Basic (simplified) inventory turnover:
     *   turnover = COGS / average inventory value
     *   Here, COGS = total sales; avg inventory = avg product price.
     *   Replace with real inventory costs if available. */
    private function inventoryTurnoverRate(): float
    {
        $cogs           = Order::sum('total_amount');
        $averageCost    = Product::avg('price');
        return $averageCost > 0 ? $cogs / $averageCost : 0;
    }
}
