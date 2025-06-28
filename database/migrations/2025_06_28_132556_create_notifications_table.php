<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type');            // e.g., order_confirmation, low_stock, system_announcement, credit_warning
            $table->string('title');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            $table->softDeletes();             // optional, allows "deleting" notifications without losing data
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
