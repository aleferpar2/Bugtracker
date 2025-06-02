<?php

namespace App\Events;

use App\Models\Post;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new event instance.
     */
    public function __construct(Post $post, string $oldStatus, string $newStatus)
    {
        $this->post = $post;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('post.' . $this->post->id),
            new PrivateChannel('user.' . $this->post->user_id),
            new PrivateChannel('user.' . $this->post->assigned_to),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'post' => [
                'id' => $this->post->id,
                'title' => $this->post->title,
                'status' => $this->post->status,
                'old_status' => $this->oldStatus,
                'new_status' => $this->newStatus,
                'user' => $this->post->user->name,
                'assigned_to' => $this->post->assignedTo ? $this->post->assignedTo->name : null,
            ],
            'message' => "El estado del post ha cambiado de {$this->oldStatus} a {$this->newStatus}"
        ];
    }
} 