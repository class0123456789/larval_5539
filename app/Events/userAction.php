<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class userAction
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $uid,$adminName,$model,$aid,$type,$content;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $model, int $aid, int $type, string $content)
    {
        $this->uid = auth('admin')->user()->id;
        $this->adminName = auth('admin')->user()->name;
        $this->model = $model;
        $this->aid = $aid;
        $this->type = $type;
        $this->content = $content;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
       // return new PrivateChannel('channel-name');
        return [];
    }
}
