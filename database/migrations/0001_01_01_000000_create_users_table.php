<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('image')->nullable();
            $table->string('bio')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('user')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('friend_requests', function (Blueprint $table) {
    // 1. Standard auto-incrementing ID (1, 2, 3...)
    $table->id(); 
    
    // 2. Both foreign keys should match the User ID type (BigInt)
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('request_sender_id')->constrained('users')->cascadeOnDelete();
    
    $table->string('status')->default('pending'); // Set a default!
    $table->timestamps(); // Adds created_at / updated_at automatically
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('friend_request');
    }
};
