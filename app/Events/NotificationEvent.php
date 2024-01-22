<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $path;
    public $user_id;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct($path, $user_id, $message)
    {
        $this->path = $path;
        $this->user_id = $user_id;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->user_id);
    }

    public function broadcastAs()
    {
        return 'notification-event';
    }
}
