<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\PrivateMessageSent;
use App\Listeners\BroadcastPrivateMessage;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PrivateMessageSent::class => [
            BroadcastPrivateMessage::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}