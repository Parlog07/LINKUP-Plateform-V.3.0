<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('user.notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});



Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    // Check if user is part of this conversation
    $conversation = \App\Models\Conversation::find($conversationId);
    
    return $conversation && (
        $conversation->user_one === $user->id || 
        $conversation->user_two === $user->id
    );
});
