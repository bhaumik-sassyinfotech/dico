<?php

namespace App\Events;

use App\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PostDelete implements ShouldBroadcast {

    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $user;
    public $sender_id;
    public $post_id;

    public function __construct($user = null,$sender_detail) {
       // dd($sender_detail);
        $this->user = $user;
        $this->sender_id = $sender_detail->user_id;
        $this->post_id = $sender_detail->post_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() {
        return new Channel('activity.'.$this->sender_id);
    }

    public function broadcastAs() {
        return 'message.postDelete';
    }

    public function broadcastWith() {
        
        $msg = $this->user->name . ' post has been delete successfully';
        $notification = new Notification;
        $notification->user_id = $this->user->id;
        $notification->notification_description = $msg;
        $notification->is_read = 0;
        $notification->send_to = $this->sender_id;
        $notification->redirect_url = route('post.index');
        $notification->save();
        return [
            'msg' => $msg,
            'username' => $this->user->name,
            'time' => 'now',
            'url' =>  $notification->redirect_url
        ];
    }
}
