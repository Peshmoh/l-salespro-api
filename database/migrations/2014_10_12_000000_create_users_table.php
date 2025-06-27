<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Basic user info
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');

            // Optional user profile fields
            $table->string('first_name');
            $table->string('last_name');

            // Optional: Simple label for role display (not used for permission checks)
            $table->string('role')->nullable();

            // User status
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->index();

            // Laravel Auth fields
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();

            // Timestamps and soft deletes
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
