<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ConversationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

   
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Conversation $conversation): bool
    {
        //
    }


    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Conversation $conversation): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Conversation $conversation): bool
    {
        //
    }

    public function view(User $user, Conversation $conversation)
    {
        return $conversation->participants()->where('user_id', $user->id)->exists();
    }

    public function create(User $user)
    {
        return true; // Tous les utilisateurs authentifiés peuvent créer des conversations
    }

    public function delete(User $user, Conversation $conversation)
    {
        return $conversation->participants()->where('user_id', $user->id)->exists();
    }
}
