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

class PostAssigned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post;
    public $oldAssignee;
    public $newAssignee;

    /**
     * Create a new event instance.
     */
    public function __construct(Post $post, $oldAssignee, $newAssignee)
    {
        $this->post = $post;
        $this->oldAssignee = $oldAssignee;
        $this->newAssignee = $newAssignee;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('post.' . $this->post->id),
            new PrivateChannel('user.' . $this->post->user_id),
        ];

        if ($this->oldAssignee) {
            $channels[] = new PrivateChannel('user.' . $this->oldAssignee->id);
        }

        if ($this->newAssignee) {
            $channels[] = new PrivateChannel('user.' . $this->newAssignee->id);
        }

        return $channels;
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
                'user' => $this->post->user->name,
                'old_assignee' => $this->oldAssignee ? $this->oldAssignee->name : null,
                'new_assignee' => $this->newAssignee ? $this->newAssignee->name : null,
            ],
            'message' => $this->getMessage()
        ];
    }

    /**
     * Get the appropriate message based on the assignment change.
     */
    private function getMessage(): string
    {
        if (!$this->oldAssignee && $this->newAssignee) {
            return "El post ha sido asignado a {$this->newAssignee->name}";
        }

        if ($this->oldAssignee && !$this->newAssignee) {
            return "Se ha removido la asignación del post de {$this->oldAssignee->name}";
        }

        return "El post ha sido reasignado de {$this->oldAssignee->name} a {$this->newAssignee->name}";
    }
} 