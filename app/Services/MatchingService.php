<?php 

namespace App\Services;

use App\Models\Objet;
use App\Models\User;
use App\Notifications\CorrespondanceNotification;
use App\Notifications\AdminDailyReport;
use Carbon\Carbon;
use Illuminate\Support\Str;

class MatchingService
{
    public function checkMatches(Objet $newObjet)
    {
        if ($newObjet->statut === 'perdu') {
            $this->checkForLostItem($newObjet);
        } else {
            $this->checkForFoundItem($newObjet);
        }

        $this->notifyAdmin($newObjet);
    }

    protected function checkForLostItem(Objet $lostItem)
    {
        $foundItems = Objet::trouves()
            ->where(function($query) use ($lostItem) {
                $query->where(function($q) use ($lostItem) {
                    // Correspondance par nom (recherche floue)
                    $q->where('nom', 'like', '%' . $lostItem->nom . '%')
                      ->orWhere(function($q) use ($lostItem) {
                          $words = explode(' ', $lostItem->nom);
                          foreach ($words as $word) {
                              if (strlen($word) > 3) {
                                  $q->orWhere('nom', 'like', '%' . $word . '%');
                              }
                          }
                      });
                })
                ->orWhere(function($q) use ($lostItem) {
                    // Correspondance par lieu
                    $q->where('lieu', 'like', '%' . $lostItem->lieu . '%');
                })
                ->orWhere(function($q) use ($lostItem) {
                    // Correspondance par date (à +/- 2 jours)
                    $date = Carbon::parse($lostItem->date_perte);
                    $q->whereBetween('date_perte', [
                        $date->copy()->subDays(2),
                        $date->copy()->addDays(2)
                    ]);
                });
            })
            ->get();

        foreach ($foundItems as $foundItem) {
            $score = $this->calculateMatchScore($lostItem, $foundItem);
            if ($score >= 0.5) { // Seuil de correspondance de 50%
                $matchType = $this->determineMatchType($lostItem, $foundItem);
                $lostItem->user->notify(new CorrespondanceNotification($foundItem, $matchType, $score));
            }
        }
    }

    protected function checkForFoundItem(Objet $foundItem)
    {
        $lostItems = Objet::perdus()
            ->where(function($query) use ($foundItem) {
                $query->where(function($q) use ($foundItem) {
                    // Correspondance par nom (recherche floue)
                    $q->where('nom', 'like', '%' . $foundItem->nom . '%')
                      ->orWhere(function($q) use ($foundItem) {
                          $words = explode(' ', $foundItem->nom);
                          foreach ($words as $word) {
                              if (strlen($word) > 3) {
                                  $q->orWhere('nom', 'like', '%' . $word . '%');
                              }
                          }
                      });
                })
                ->orWhere(function($q) use ($foundItem) {
                    // Correspondance par lieu
                    $q->where('lieu', 'like', '%' . $foundItem->lieu . '%');
                })
                ->orWhere(function($q) use ($foundItem) {
                    // Correspondance par date (à +/- 2 jours)
                    $date = Carbon::parse($foundItem->date_perte);
                    $q->whereBetween('date_perte', [
                        $date->copy()->subDays(2),
                        $date->copy()->addDays(2)
                    ]);
                });
            })
            ->get();

        foreach ($lostItems as $lostItem) {
            $score = $this->calculateMatchScore($lostItem, $foundItem);
            if ($score >= 0.5) { // Seuil de correspondance de 50%
                $matchType = $this->determineMatchType($lostItem, $foundItem);
                $lostItem->user->notify(new CorrespondanceNotification($foundItem, $matchType, $score));
            }
        }
    }

    protected function calculateMatchScore(Objet $objet1, Objet $objet2)
    {
        $score = 0;
        $weights = [
            'nom' => 0.4,
            'description' => 0.2,
            'lieu' => 0.2,
            'date' => 0.2
        ];

        // Score du nom (utilisation de similar_text pour une comparaison floue)
        similar_text(
            Str::lower($objet1->nom),
            Str::lower($objet2->nom),
            $namePercentage
        );
        $score += ($namePercentage / 100) * $weights['nom'];

        // Score de la description
        similar_text(
            Str::lower($objet1->description),
            Str::lower($objet2->description),
            $descriptionPercentage
        );
        $score += ($descriptionPercentage / 100) * $weights['description'];

        // Score du lieu
        similar_text(
            Str::lower($objet1->lieu),
            Str::lower($objet2->lieu),
            $lieuPercentage
        );
        $score += ($lieuPercentage / 100) * $weights['lieu'];

        // Score de la date
        $dateDiff = abs(Carbon::parse($objet1->date_perte)->diffInDays(Carbon::parse($objet2->date_perte)));
        $dateScore = $dateDiff <= 2 ? (2 - $dateDiff) / 2 : 0;
        $score += $dateScore * $weights['date'];

        return $score;
    }

    protected function determineMatchType($objet1, $objet2)
    {
        $matchTypes = [];

        if (Str::contains(Str::lower($objet2->nom), Str::lower($objet1->nom))) {
            $matchTypes[] = 'nom';
        }
        if (Str::contains(Str::lower($objet2->lieu), Str::lower($objet1->lieu))) {
            $matchTypes[] = 'lieu';
        }
        if (Carbon::parse($objet1->date_perte)->diffInDays(Carbon::parse($objet2->date_perte)) <= 2) {
            $matchTypes[] = 'date';
        }

        return implode(',', $matchTypes);
    }

    protected function notifyAdmin(Objet $objet)
    {
        $admin = User::where('role', 'admin')->first();
        
        if ($admin) {
            $stats = [
                'perdus' => Objet::where('statut', 'perdu')
                             ->whereDate('created_at', today())
                             ->count(),
                'trouves' => Objet::where('statut', 'trouvé')
                               ->whereDate('created_at', today())
                               ->count(),
                'matches' => Objet::where('created_at', '>=', now()->subDay())
                             ->whereHas('claims')
                             ->count()
            ];
            
            $admin->notify(new AdminDailyReport($stats, $objet));
        }
    }
}