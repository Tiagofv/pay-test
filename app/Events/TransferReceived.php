<?php

namespace App\Events;

use App\Models\Transfer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransferReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Transfer
     */
    private $transfer;

    /**
     * Create a new event instance.
     *
     * @param Transfer $transfer
     */
    public function __construct(Transfer $transfer)
    {

        $this->transfer = $transfer;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
