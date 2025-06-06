<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = ['conversation_id', 'user_id', 'body', 'type', 'attachment_path'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function readStatuses()
    {
        return $this->hasMany(MessageReadStatus::class);
    }

    public function reactions()
    {
        return $this->hasMany(MessageReaction::class);
    }
    // Dans app/Models/Message.php
    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }
}
