<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;

Route::prefix('v1')->group(function () {

    /* ---------- AUTHENTICATION ---------- */
    Route::prefix('auth')->middleware('throttle:auth')->group(function () {
        Route::post('login',   [AuthController::class, 'login']);
        Route::post('logout',  [AuthController::class, 'logout'])->middleware('auth:sanctum');
        Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');
        Route::get('user',     [AuthController::class, 'user'])->middleware('auth:sanctum');

        Route::post('password/forgot', [AuthController::class, 'forgot']);
        Route::post('password/reset',  [AuthController::class, 'reset']);
    });

    /* ---------- INVENTORY MANAGEMENT: PRODUCTS ---------- */
    Route::middleware('auth:sanctum')->group(function () {
        Route::get   ('products',        [ProductController::class,'index']);
        Route::get   ('products/{id}',   [ProductController::class,'show']);

   // api.php  (inside the existing Route::prefix('v1')->group(...))

Route::middleware(['auth:sanctum', 'permission:manage_inventory'])->group(function () {
    Route::post('products', [ProductController::class, 'store']);
});


        Route::put   ('products/{id}',   [ProductController::class,'update'])
             ->middleware('can:manage_inventory');

        Route::delete('products/{id}',   [ProductController::class,'destroy'])
             ->middleware('can:manage_inventory');
    });
});
