<?php

use App\Models\User;
use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('user.notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});





Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {

    $conversation = Conversation::find($conversationId);

    if (! $conversation) {
        return false;
    }

    return $user->id === $conversation->user_one
        || $user->id === $conversation->user_two;
});

Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {

    logger('Channel hit with:', ['value' => $conversationId]);

    return true;
});

