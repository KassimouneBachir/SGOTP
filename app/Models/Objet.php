<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Objet extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'statut',
        'lieu',
        'date_perte',
        'photo_url',
        'user_id',
        'details_specifiques'
    ];

    protected $casts = [
        'date_perte' => 'date',
        'details_specifiques' => 'array'
    ];

    // Relation utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec les réclamations
    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    // Accesseur pour l'URL de la photo
    public function getPhotoUrlAttribute($value)
    {
        if ($value) {
            return asset($value);
        }
        
        return asset('images/default-objet.png');
    }

    // Scope pour les objets perdus
    public function scopePerdus($query)
    {
        return $query->where('statut', 'perdu');
    }

    // Scope pour les objets trouvés
    public function scopeTrouves($query)
    {
        return $query->where('statut', 'trouvé');
    }

    // Scope pour la recherche
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nom', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('lieu', 'like', "%{$search}%");
        });
    }

    // Scope pour le filtrage par statut
    public function scopeStatus($query, $status)
    {
        if ($status) {
            return $query->where('statut', $status);
        }
        return $query;
    }
}

