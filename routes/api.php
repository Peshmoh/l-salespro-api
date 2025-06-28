<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\OrderController;          // ← NEW import

Route::prefix('v1')->group(function () {

    /* ---------- AUTHENTICATION ---------- */
    Route::prefix('auth')->middleware('throttle:auth')->group(function () {
        Route::post('login',   [AuthController::class, 'login']);
        Route::post('logout',  [AuthController::class, 'logout'])->middleware('auth:sanctum');
        Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');
        Route::get ('user',    [AuthController::class, 'user'])->middleware('auth:sanctum');

        Route::post('password/forgot', [AuthController::class, 'forgot']);
        Route::post('password/reset',  [AuthController::class, 'reset']);
    });

    /* ---------- INVENTORY MANAGEMENT: PRODUCTS ---------- */
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('products',          [ProductController::class, 'index']);
        Route::get('products/{id}',     [ProductController::class, 'show']);

        // create (admin only)
        Route::post('products',         [ProductController::class, 'store'])
              ->middleware('permission:manage_inventory');

        Route::put   ('products/{id}',  [ProductController::class, 'update'])
              ->middleware('permission:manage_inventory');

        Route::delete('products/{id}',  [ProductController::class, 'destroy'])
              ->middleware('permission:manage_inventory');
    });

    /* ---------- DASHBOARD ANALYTICS ---------- */
    Route::middleware('auth:sanctum')->prefix('dashboard')->group(function () {
        Route::get('summary',           [DashboardController::class, 'summary']);
        Route::get('sales-performance', [DashboardController::class, 'salesPerformance']);
        Route::get('inventory-status',  [DashboardController::class, 'inventoryStatus']);
        Route::get('top-products',      [DashboardController::class, 'topProducts']);
    });

    /* ---------- SALES ORDER MANAGEMENT ---------- */
    Route::middleware('auth:sanctum')->prefix('orders')->group(function () {
        Route::get('/',               [OrderController::class, 'index']);
        Route::get('/{id}',           [OrderController::class, 'show']);

        // create with credit‑limit check
        Route::post('/',              [OrderController::class, 'store'])
              ->middleware('credit.check');

        Route::put('/{id}/status',    [OrderController::class, 'updateStatus']);
        Route::get('/{id}/invoice',   [OrderController::class, 'invoice']);
        Route::post('/calculate-total',[OrderController::class, 'calculateTotal']);
    });
});
