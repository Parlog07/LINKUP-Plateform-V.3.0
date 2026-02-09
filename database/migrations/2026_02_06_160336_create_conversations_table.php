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
Schema::create('conversations', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_one')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->foreignId('user_two')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->timestamps();

    // Prevent duplicate conversations
    $table->unique(['user_one', 'user_two']);
});

Schema::create('messages', function (Blueprint $table) {
    $table->id();

    $table->foreignId('conversation_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('user_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->text('body');

    $table->timestamps();
});


Schema::create('message_reads', function (Blueprint $table) {
    $table->id();

    $table->foreignId('message_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('user_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->timestamp('read_at')->nullable();

    $table->unique(['message_id', 'user_id']);
});





    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
