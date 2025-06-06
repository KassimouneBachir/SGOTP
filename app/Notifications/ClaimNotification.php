<?php

namespace App\Notifications;

use App\Models\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClaimNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $claim;

    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('claims.index', ['tab' => 'received', 'highlight' => $this->claim->id]);

        return (new MailMessage)
            ->subject('Nouvelle réclamation pour votre objet')
            ->line('Une nouvelle réclamation a été soumise pour votre objet : ' . $this->claim->objet->nom)
            ->line('Description de la réclamation : ' . $this->claim->description)
            ->action('Voir la réclamation', $url)
            ->line('Merci d\'utiliser notre application !');
    }

    public function toArray($notifiable)
    {
        return [
            'claim_id' => $this->claim->id,
            'objet_id' => $this->claim->objet->id,
            'objet_nom' => $this->claim->objet->nom,
            'description' => $this->claim->description,
            'user_id' => $this->claim->user_id,
            'user_name' => $this->claim->user->name,
            'type' => 'claim',
            'message' => 'Nouvelle réclamation pour votre objet ' . $this->claim->objet->nom,
            'url' => route('claims.index', ['tab' => 'received', 'highlight' => $this->claim->id])
        ];
    }
} 