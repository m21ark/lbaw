<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $type;
    public $sender;
    public $obj;
    public $photo;

    private $channel;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($channel, $type, $sender, $obj)
    {
        $this->channel = $channel;
        $this->type = $type;
        $this->sender = $sender;
        $this->obj  = $obj;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
        return ['App.User.' . $this->channel];
    }

    public function broadcastAs()
    {
        return 'my-event';
    }
}
