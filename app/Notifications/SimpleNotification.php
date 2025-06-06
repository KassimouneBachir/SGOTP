<?php 



namespace App\Notifications;

use Illuminate\Notifications\Notification;

class SimpleNotification extends Notification
{
    public function __construct(
        public string $message, 
        public string $url = '#'
    ) {}

    public function via($notifiable)
    {
        return ['database']; // Stockage en base seulement
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'url' => $this->url,
            'time' => now()->format('d/m H:i'),
        ];
    }
}