<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MessagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
  public function view(User $user, Message $message)
    {
        return $message->conversation->participants()->where('user_id', $user->id)->exists();
    }

    public function create(User $user, Message $message)
    {
        return $message->conversation->participants()->where('user_id', $user->id)->exists();
    }

    public function update(User $user, Message $message)
    {
        return $message->user_id === $user->id;
    }

    public function delete(User $user, Message $message)
    {
        return $message->user_id === $user->id;
    }

    public function react(User $user, Message $message)
    {
        return $message->conversation->participants()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Message $message): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Message $message): bool
    {
        //
    }
}
