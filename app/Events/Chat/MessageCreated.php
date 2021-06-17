<?php

namespace App\Events\Chat;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;

    public Conversation $conversation;

    public array $sender;

    public array $content;

    public array $topic;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;

        $this->sender = $message->sender->only(['name', 'phone', 'id', 'created_at']);

        $this->content = $message->only([
            'id',
            'content',
            'extra',
            'sender_type',
            'sender_id',
            'topic_id',
            'topic_type',
            'created_at'
        ]);

        $this->topic = $message->topic->toArray();

        $this->conversation = Conversation::findByMessage($message)->load(['sender', 'topic']);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'message:created';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [
            new PrivateChannel("customer." . $this->message->topic->id),
            new PrivateChannel('chat')
        ];
    }
}
