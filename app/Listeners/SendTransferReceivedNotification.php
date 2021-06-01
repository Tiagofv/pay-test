<?php

namespace App\Listeners;

use App\Events\TransferReceived;
use App\Services\MockNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendTransferReceivedNotification implements ShouldQueue
{

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
        // sending the notification
        Log::debug('Trying to send your email...');
        (new MockNotificationService())->sendNotification();
    }
}
