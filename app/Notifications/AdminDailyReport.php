<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class AdminDailyReport extends Notification
{
    public function __construct(public $stats, public $lastObjet) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Rapport quotidien - Objets perdus/trouvés",
            'stats' => $this->stats,
            'last_objet' => [
                'id' => $this->lastObjet->id,
                'nom' => $this->lastObjet->nom,
                'type' => $this->lastObjet->statut
            ],
            'time' => now()->format('H:i')
        ];
    }
}