<?php

namespace App\Listeners;

use App\Events\ContactCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendContactEmail;

class SendContactNotification
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
    public function handle(ContactCreated $event): void
    {
        SendContactEmail::dispatch($event->contact); // Send via queue
    }
}
