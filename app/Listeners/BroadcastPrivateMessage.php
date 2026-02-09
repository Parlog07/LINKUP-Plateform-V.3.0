<?php

namespace App\Listeners;

use App\Events\PrivateMessageSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BroadcastPrivateMessage
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PrivateMessageSent $event): void
    {
        //
    }
}
