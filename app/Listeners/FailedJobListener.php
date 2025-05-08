<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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

        $jobName = $event->job->resolveName();
        $exceptionMessage = $event->exception->getMessage();
        $payload = $event->job->payload();

        // Log the failure
        Log::channel('jobfailures')->error("Job failed: {$jobName}", [
            'exception' => $exceptionMessage,
            'payload' => $payload,
        ]);

        // Send email notification
        Mail::raw("A job has failed: {$jobName}\n\nError: {$exceptionMessage}", function ($message) {
            $message->to('admin@example.com')
                    ->subject('Laravel Job Failed');
        });
    }
}
