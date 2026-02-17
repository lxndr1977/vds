<?php

namespace App\Policies;

use App\Models\Choreography;
use App\Models\User;

class ChoreographyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin()
           || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Choreography $choreography): bool
    {
        return $user->isAdmin()
           || $user->isSuperAdmin()
           || ($choreography->school && $choreography->school->user_id === $user->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Choreography $choreography): bool
    {
        return $user->isAdmin()
           || $user->isSuperAdmin()
           || ($choreography->school && $choreography->school->user_id === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Choreography $choreography): bool
    {
        return $user->isAdmin()
           || $user->isSuperAdmin()
           || ($choreography->school && $choreography->school->user_id === $user->id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Choreography $choreography): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Choreography $choreography): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }
}
