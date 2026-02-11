<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Message;

class DeleteExpiredMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'messages:delete-expired';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

public function handle()
{
    Message::whereNotNull('expires_at')
        ->where('expires_at', '<=', now())
        ->delete();
}

}
