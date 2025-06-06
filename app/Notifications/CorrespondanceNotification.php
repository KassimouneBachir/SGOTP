<?php 

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class CorrespondanceNotification extends Notification
{
    public function __construct(public $objet, public $matchType) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->getMessage(),
            'url' => "/objets/{$this->objet->id}",
            'type' => 'correspondance',
            'objet_nom' => $this->objet->nom,
            'match_type' => $this->matchType
            
        ];
    }

    protected function getMessage()
    {
        return match($this->matchType) {
            'nom' => "Un objet trouvé correspond à votre objet perdu : {$this->objet->nom}",
            'lieu' => "Un objet trouvé au même lieu que votre objet perdu : {$this->objet->lieu}",
            default => "Ceci pourrait vous appartenir : {$this->objet->nom}"
        };
    }
}