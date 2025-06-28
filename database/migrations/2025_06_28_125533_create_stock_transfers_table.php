<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('from_warehouse_id')
                  ->constrained('warehouses')
                  ->cascadeOnDelete();
            $table->foreignId('to_warehouse_id')
                  ->constrained('warehouses')
                  ->cascadeOnDelete();

            $table->unsignedInteger('quantity');
            $table->timestamp('transferred_at')->useCurrent();   // when the move happened

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
    }
};
