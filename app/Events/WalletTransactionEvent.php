<?php

namespace App\Events;

use App\Models\User;
use App\Models\Wallet;
use App\Services\WalletService;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WalletTransactionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $wallet;
    public $payload;
    public $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        Wallet $wallet,
        User $user,
        array $payload
    ){
        $this->wallet = $wallet;
        $this->user = $user;
        $this->payload = $payload;
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
