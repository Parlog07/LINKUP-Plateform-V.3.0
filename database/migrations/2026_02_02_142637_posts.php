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
        Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->text('content')->nullable();
        $table->string('media_path')->nullable();
        $table->string('media_type')->nullable();
        $table->enum('status', ['approved', 'pending', 'blocked'])->default('approved');
        $table->text('moderation_reason')->nullable();
        $table->timestamps();
    });
    
    Schema::create('posts_actions', function (Blueprint $table) {
        $table->id();
        
        // Foreign Keys
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('post_id')->constrained()->cascadeOnDelete();
        
        // The Action: 'like' or 'comment'
        $table->string('action_type'); 
        
        // The Comment Body (Null if it's just a like)
        $table->text('content')->nullable();
        
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
        Schema::dropIfExists('posts_actions');
    }
};
