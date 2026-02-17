<?php

namespace App\Policies;

use App\Models\Dancer;
use App\Models\User;

class DancerPolicy
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
    public function view(User $user, Dancer $dancer): bool
    {
        return $user->isAdmin()
           || $user->isSuperAdmin()
           || ($dancer->school && $dancer->school->user_id === $user->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user !== null
           || $user->isSuperAdmin()
           || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Dancer $dancer): bool
    {
        return $user->isAdmin()
           || $user->isSuperAdmin()
           || ($dancer->school && $dancer->school->user_id === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Dancer $dancer): bool
    {
        return $user->isAdmin()
           || $user->isSuperAdmin()
           || ($dancer->school && $dancer->school->user_id === $user->id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Dancer $dancer): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Dancer $dancer): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }
}
