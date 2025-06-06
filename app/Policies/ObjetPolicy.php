<?php

namespace App\Policies;

use App\Models\Objet;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ObjetPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->role === 'admin') {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Objet $objet)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    /**
     * Vérifie si l'utilisateur peut modifier l'objet
     */
    public function update(User $user, Objet $objet)
    {
        return $user->id === $objet->user_id;
    }

    /**
     * Vérifie si l'utilisateur peut supprimer l'objet
     */
    public function delete(User $user, Objet $objet)
    {
        return $user->id === $objet->user_id;
    }

    /**
     * Vérifie si l'utilisateur peut marquer comme trouvé/rendu
     */
    public function changeStatus(User $user, Objet $objet)
    {
        return $user->id === $objet->user_id;
    }

    public function claim(User $user, Objet $objet)
    {
        return $user->id !== $objet->user_id && !$objet->claims()->where('user_id', $user->id)->exists();
    }

    public function validateClaim(User $user, Objet $objet)
    {
        return $user->id === $objet->user_id;
    }
}