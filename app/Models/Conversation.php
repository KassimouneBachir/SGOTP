<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    protected $fillable = ['title'];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->latest();
    }


        public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'participants');
    }
    // Dans app/Models/Conversation.php
    public function scopeBetweenUsers($query, $userId1, $userId2)
    {
        return $query->whereHas('participants', function($q) use ($userId1) {
            $q->where('user_id', $userId1);
        })->whereHas('participants', function($q) use ($userId2) {
            $q->where('user_id', $userId2);
        });
    }
}
