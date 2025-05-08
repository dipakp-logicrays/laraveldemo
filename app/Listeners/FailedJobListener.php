<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Mail;

class FailedJobListener
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
    public function handle(object $event): void
    {
        $jobName = $event->job->resolveName();
        $exception = $event->exception->getMessage();

        // Send email
        Mail::raw("A job has failed: {$jobName}\n\nError: {$exception}", function ($message) {
            $message->to('admin@example.com')
                    ->subject('Laravel Job Failed');
        });
    }
}
