<?php

namespace App\Notifications;

use App\Models\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClaimStatusNotification extends Notification implements ShouldQueue
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
        $status = $this->claim->status === 'approved' ? 'acceptée' : 'rejetée';
        $message = $this->claim->status === 'approved' 
            ? "Votre réclamation pour l'objet {$this->claim->objet->nom} a été acceptée. Vous pouvez maintenant contacter le propriétaire pour récupérer votre objet."
            : "Votre réclamation pour l'objet {$this->claim->objet->nom} a été rejetée." . 
              ($this->claim->rejection_reason ? " Motif : {$this->claim->rejection_reason}" : "");

        return (new MailMessage)
            ->subject("Réclamation {$status}")
            ->line($message)
            ->action('Voir les détails', route('claims.index', ['tab' => 'sent']))
            ->line("Merci d'utiliser notre application !");
    }

    public function toArray($notifiable)
    {
        $status = $this->claim->status === 'approved' ? 'acceptée' : 'rejetée';
        $message = $this->claim->status === 'approved' 
            ? "Votre réclamation pour l'objet {$this->claim->objet->nom} a été acceptée"
            : "Votre réclamation pour l'objet {$this->claim->objet->nom} a été rejetée" . 
              ($this->claim->rejection_reason ? " - Motif : {$this->claim->rejection_reason}" : "");

        return [
            'claim_id' => $this->claim->id,
            'objet_id' => $this->claim->objet->id,
            'objet_nom' => $this->claim->objet->nom,
            'status' => $this->claim->status,
            'type' => 'claim_status',
            'message' => $message,
            'url' => route('claims.index', ['tab' => 'sent'])
        ];
    }
} 