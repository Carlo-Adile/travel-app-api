<?php

namespace App\Policies;

use App\Models\Step;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StepPolicy
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
    public function view(User $user, Step $step)
    {
        return $step->travel->user_id === $user->id
            ? Response::allow()
            : Response::deny('Non autorizzato');
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
    public function update(User $user, Step $step)
    {
        return $step->travel->user_id === $user->id
            ? Response::allow()
            : Response::deny('Non autorizzato');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Step $step)
    {
        return $step->travel->user_id === $user->id
            ? Response::allow()
            : Response::deny('Non autorizzato.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Step $step): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Step $step): bool
    {
        //
    }
}
