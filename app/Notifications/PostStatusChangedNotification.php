<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Post $post,
        public string $oldStatus,
        public string $newStatus
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Cambio de estado en tu post')
            ->line("El estado del post '{$this->post->title}' ha cambiado.")
            ->line("Estado anterior: {$this->oldStatus}")
            ->line("Nuevo estado: {$this->newStatus}")
            ->action('Ver post', route('posts.show', $this->post))
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
            'post_id' => $this->post->id,
            'post_title' => $this->post->title,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'message' => "El estado del post '{$this->post->title}' ha cambiado de {$this->oldStatus} a {$this->newStatus}",
        ];
    }
}
