<?php

// app/Models/Claim.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'objet_id',
        'description',
        'proof_url',
        'answers',
        'status',
        'rejection_reason'
    ];

    protected $casts = [
        'answers' => 'array'
    ];

    // Statuts possibles
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function objet()
    {
        return $this->belongsTo(Objet::class);
    }

    // Accesseurs
    public function getProofUrlAttribute($value)
    {
        if ($value) {
            return asset($value);
        }
        return null;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    // Méthodes
    public function approve()
    {
        $this->update(['status' => self::STATUS_APPROVED]);
        
        // Mettre à jour le statut de l'objet
        $this->objet->update(['statut' => 'rendu']);
        
        // Notifier l'utilisateur
        $this->user->notify(new \App\Notifications\ClaimStatusNotification($this));
    }

    public function reject($reason = null)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejection_reason' => $reason
        ]);
        
        // Notifier l'utilisateur
        $this->user->notify(new \App\Notifications\ClaimStatusNotification($this));
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function canBeProcessedBy(User $user)
    {
        return $this->objet->user_id === $user->id || $user->isAdmin();
    }
}