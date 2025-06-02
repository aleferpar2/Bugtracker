<?php

namespace App\Listeners;

use App\Events\PostStatusChanged;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandlePostStatusChange implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PostStatusChanged $event): void
    {
        $post = $event->post;
        $users = collect([$post->user, $post->assignedTo])->filter();

        foreach ($users as $user) {
            if ($user->id === auth()->id()) {
                continue; // No notificar al usuario que realizó el cambio
            }

            Notification::create([
                'type' => 'post_status_changed',
                'notifiable_type' => 'App\Models\Post',
                'notifiable_id' => $post->id,
                'user_id' => $user->id,
                'message' => "El estado del post '{$post->title}' ha cambiado de {$event->oldStatus} a {$event->newStatus}",
                'read' => false
            ]);
        }
    }
} 