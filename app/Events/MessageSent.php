<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message; // Import correct du modèle Message


    // app/Events/MessageSent.php
class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $conversationId;

    // Correction du type hint ici
    public function __construct(Message $message, $conversationId)
    {
        $this->message = $message;
        $this->conversationId = $conversationId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('conversation.'.$this->conversationId);
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }

    public function broadcastWhen()
    {
        return $this->message->conversation->participants()
            ->where('user_id', $this->message->user_id)
            ->exists();
    }

    public function broadcastWith()
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'body' => $this->message->body,
                'user_id' => $this->message->user_id,
                'user' => [
                    'id' => $this->message->user->id,
                    'name' => $this->message->user->name,
                ],
                'created_at' => $this->message->created_at->toDateTimeString(),
                'type' => $this->message->type,
                'attachment_path' => $this->message->attachment_path,
            ]
        ];
    }
}

