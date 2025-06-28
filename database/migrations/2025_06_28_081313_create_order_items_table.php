<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->cascadeOnDelete();

            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();

            $table->foreignId('warehouse_id')
                  ->constrained('warehouses')
                  ->cascadeOnDelete();

            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('discount',   15, 2)->default(0);
            $table->decimal('tax',        15, 2)->default(0);
            $table->decimal('total',      15, 2)->default(0);

            $table->timestamps();
            $table->softDeletes();

            // one product per order line
            $table->unique(['order_id', 'product_id'], 'order_item_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
