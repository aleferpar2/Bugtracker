<?php

namespace App\Listeners;

use App\Events\PostAssigned;
use App\Notifications\PostAssignedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandlePostAssignment implements ShouldQueue
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
    public function handle(PostAssigned $event): void
    {
        $post = $event->post;
        $users = collect([$post->user, $event->oldAssignee, $event->newAssignee])->filter();

        foreach ($users as $user) {
            if ($user && $user->id !== auth()->id()) {
                $message = $this->getMessage($event, $user);
                if ($message) {
                    $user->notify(new PostAssignedNotification(
                        $post,
                        $event->oldAssignee,
                        $event->newAssignee,
                        $message
                    ));
                }
            }
        }
    }

    /**
     * Get the appropriate message based on the user and assignment change.
     */
    private function getMessage(PostAssigned $event, $user): ?string
    {
        $post = $event->post;

        if ($user->id === $event->newAssignee?->id) {
            return "Has sido asignado al post '{$post->title}'";
        }

        if ($user->id === $event->oldAssignee?->id) {
            return "Has sido desasignado del post '{$post->title}'";
        }

        if ($user->id === $post->user_id) {
            if ($event->newAssignee) {
                return "El post '{$post->title}' ha sido asignado a {$event->newAssignee->name}";
            } else {
                return "Se ha removido la asignación del post '{$post->title}'";
            }
        }

        return null;
    }
} 