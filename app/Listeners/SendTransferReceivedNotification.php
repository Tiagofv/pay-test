<?php

namespace App\Listeners;

use App\Events\TransferReceived;
use App\Services\MockNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTransferReceivedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of times the queued listener may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param TransferReceived $event
     * @return void
     */
    public function handle(TransferReceived $event)
    {
        (new MockNotificationService())->sendNotification();
    }
}
