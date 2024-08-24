<?php

namespace App\Policies;

use App\Models\Travel;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class TravelPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Travel $travel)
    {
        return $travel->user_id === $user->id
            ? Response::allow()
            : Response::deny('Non autorizzato.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Travel $travel)
    {
        // Permette solo all'utente proprietario di aggiornare il viaggio
        return $travel->user_id === $user->id
            ? Response::allow()
            : Response::deny('Non autorizzato.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Travel $travel)
    {
        // Permette solo all'utente proprietario di eliminare il viaggio
        return $travel->user_id === $user->id
            ? Response::allow()
            : Response::deny('Non autorizzato.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Travel $travel): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Travel $travel): bool
    {
        //
    }
}
