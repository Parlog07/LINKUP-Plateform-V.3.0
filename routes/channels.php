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

    if ((int) $user->id !== (int) $conversation->user_one && (int) $user->id !== (int) $conversation->user_two) {
        return false;
    }

    return [
        'id' => $user->id,
        'name' => trim(($user->first_name ?? '').' '.($user->last_name ?? '')) ?: ($user->name ?? 'User'),
    ];
});

