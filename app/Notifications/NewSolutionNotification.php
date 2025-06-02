<?php

namespace App\Notifications;

use App\Models\Solution;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSolutionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Solution $solution
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nueva solución propuesta para tu post')
            ->line('Se ha propuesto una nueva solución para tu post: ' . $this->solution->post->title)
            ->line('Solución: ' . $this->solution->description)
            ->action('Ver solución', route('posts.show', $this->solution->post))
            ->line('Gracias por usar nuestra aplicación!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'solution_id' => $this->solution->id,
            'post_id' => $this->solution->post_id,
            'post_title' => $this->solution->post->title,
            'user_id' => $this->solution->user_id,
            'user_name' => $this->solution->user->name,
            'message' => 'Nueva solución propuesta para tu post: ' . $this->solution->post->title,
        ];
    }
} 