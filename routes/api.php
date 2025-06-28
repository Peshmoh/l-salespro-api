<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\CustomerController;  
use App\Http\Controllers\API\WarehouseController;
use App\Http\Controllers\API\NotificationController;


Route::prefix('v1')->group(function () {

    /* ---------- AUTHENTICATION ---------- */
    Route::prefix('auth')->middleware('throttle:auth')->group(function () {
        Route::post('login',    [AuthController::class, 'login']);
        Route::post('logout',   [AuthController::class, 'logout'])->middleware('auth:sanctum');
        Route::post('refresh',  [AuthController::class, 'refresh'])->middleware('auth:sanctum');
        Route::get ('user',     [AuthController::class, 'user'])->middleware('auth:sanctum');

        Route::post('password/forgot', [AuthController::class, 'forgot']);
        Route::post('password/reset',  [AuthController::class, 'reset']);
    });

    /* ---------- INVENTORY: PRODUCTS ---------- */
    Route::middleware('auth:sanctum')->group(function () {
        Route::get   ('products',        [ProductController::class, 'index']);
        Route::get   ('products/{id}',   [ProductController::class, 'show']);
        Route::post  ('products',        [ProductController::class, 'store'])->middleware('permission:manage_inventory');
        Route::put   ('products/{id}',   [ProductController::class, 'update'])->middleware('permission:manage_inventory');
        Route::delete('products/{id}',   [ProductController::class, 'destroy'])->middleware('permission:manage_inventory');
    });

    /* ---------- DASHBOARD ANALYTICS ---------- */
    Route::middleware('auth:sanctum')->prefix('dashboard')->group(function () {
        Route::get('summary',            [DashboardController::class, 'summary']);
        Route::get('sales-performance',  [DashboardController::class, 'salesPerformance']);
        Route::get('inventory-status',   [DashboardController::class, 'inventoryStatus']);
        Route::get('top-products',       [DashboardController::class, 'topProducts']);
    });

    /* ---------- SALES ORDERS ---------- */
    Route::middleware('auth:sanctum')->prefix('orders')->group(function () {
        Route::get   ('/',                [OrderController::class, 'index']);
        Route::get   ('/{id}',            [OrderController::class, 'show']);
        Route::post  ('/',                [OrderController::class, 'store'])->middleware('credit.check');
        Route::put   ('/{id}/status',     [OrderController::class, 'updateStatus']);
        Route::get   ('/{id}/invoice',    [OrderController::class, 'invoice']);
        Route::post  ('/calculate-total', [OrderController::class, 'calculateTotal']);
    });

    /* ---------- CUSTOMERS ---------- */
    Route::middleware('auth:sanctum')->prefix('customers')->group(function () {
        Route::get   ('/',                   [CustomerController::class, 'index']);
        Route::get   ('/{id}',               [CustomerController::class, 'show']);
        Route::post  ('/',                   [CustomerController::class, 'store']);
        Route::put   ('/{id}',               [CustomerController::class, 'update']);
        Route::delete('{id}',                [CustomerController::class, 'destroy']);

        // extra endpoints
        Route::get('{id}/orders',            [CustomerController::class, 'orderHistory']);
        Route::get('{id}/credit-status',     [CustomerController::class, 'creditStatus']);
        Route::get('map-data',               [CustomerController::class, 'mapData']);
    });

    Route::middleware('auth:sanctum')->group(function () {
    Route::get('/warehouses', [WarehouseController::class, 'index']);
    Route::get('/warehouses/{id}/inventory', [WarehouseController::class, 'inventory']);
    Route::get('/stock-transfers', [WarehouseController::class, 'transferHistory']);
    Route::post('/stock-transfers', [WarehouseController::class, 'transferStock']);
});
Route::middleware('auth:sanctum')->prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::put('{id}/read', [NotificationController::class, 'markAsRead']);
    Route::put('read-all', [NotificationController::class, 'markAllAsRead']);
    Route::delete('{id}', [NotificationController::class, 'destroy']);
    Route::get('unread-count', [NotificationController::class, 'unreadCount']);
});
});
