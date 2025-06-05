<?php

namespace App\Listeners;

use App\Events\PostStatusChanged;
use App\Notifications\PostStatusChangedNotification;
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
            if ($user && $user->id !== auth()->id()) {
                $user->notify(new PostStatusChangedNotification(
                    $post,
                    $event->oldStatus,
                    $event->newStatus
                ));
            }
        }
    }
} 