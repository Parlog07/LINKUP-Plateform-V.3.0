<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class FriendRequestSent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $senderName;
    public $recipientId;

    public function __construct($senderName, $recipientId)
    {
        $this->senderName = $senderName;
        $this->recipientId = $recipientId;
    }

    public function broadcastOn()
    {
        // Private channel for the specific recipient
        return new PrivateChannel('user.notifications.' . $this->recipientId);
    }

    // Optionally, add broadcastWith() to control the payload
    public function broadcastWith()
    {
        return ['message' => $this->senderName . ' sent you a friend request!'];
    }
}
