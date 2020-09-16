<?php

namespace App\Events\Post;

use App\Models\Report;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserReport
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $report;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * Get Reporter
     *
     * @return \App\Models\User
     */
    public function reporter()
    {
        return $this->report->user;
    }

    /**
     * Get post reported
     *
     * @return \App\Models\Post
     */
    public function post()
    {
        return $this->report->post;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [];
    }
}
