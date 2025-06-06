<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get the claims made by the user.
     */
    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    /**
     * Get the claims received for the user's objects.
     */
    public function receivedClaims()
    {
        return $this->hasManyThrough(
            Claim::class,
            Objet::class,
            'user_id', // Foreign key on objets table...
            'objet_id', // Foreign key on claims table...
            'id', // Local key on users table...
            'id' // Local key on objets table...
        );
    }

     public function conversations()
    {
        return $this->hasManyThrough(
            Conversation::class,
            Participant::class,
            'user_id', // Clé étrangère sur participants
            'id', // Clé locale sur conversations
            'id', // Clé locale sur users
            'conversation_id' // Clé étrangère sur participants
        );
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    public function readNotifications()
    {
        return $this->notifications()->whereNotNull('read_at');
    }

    public function unreadMessagesCount()
    {
        return MessageReadStatus::where('user_id', $this->id)
            ->where('is_read', false)
            ->count();
    }

    public function objets()
    {
        return $this->hasMany(Objet::class);
    }

    // Dans app/Models/User.php
/*public function getRoleAttribute()
{
    return $this->is_admin ? 'admin' : 'user'; // Adaptez à votre logique
}*/

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
