<?php

namespace App\Events;

use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Registration implements ShouldBroadcast {

    use Dispatchable,
        InteractsWithSockets,
        SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $user;

    public function __construct($user = null) {
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() {
        return new Channel('activity');
    }

    public function broadcastAs() {
        return 'message.register';
    }
//Carbon::now()->diffForHumans($this->user->created_at
    public function broadcastWith() {
        return ['msg' => $this->user->name . ' Registration Successfully',
            'username' => $this->user->name,
            'time' => 'now',
            'url' => url('/'),
        ];
    }

}
