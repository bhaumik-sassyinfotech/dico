<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PostUpdate implements ShouldBroadcast {

    use Dispatchable,
        InteractsWithSockets,
        SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $user;
    public $sender_id;

    public function __construct($user = null,$sender_id) {
        $this->user = $user;
        $this->sender_id = $sender_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() {
        return new Channel('activity.'.$this->sender_id);
    }

    
     
    /*Broadcast::channel('App.User.*', function ($user, $user_id) {
        return (int)$user->id === (int)$user_id;
    });*/
    
    
    public function broadcastAs() {
        return 'message.postUpdate';
    }

    public function broadcastWith() {
        return [
            'msg' => $this->user->name . ' post updated successfully',
            'username' => $this->user->name,
            'time' => 'now',
            'url' => url('/'),
        ];
    }
}
