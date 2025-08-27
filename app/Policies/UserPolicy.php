<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }


   public function viewAny(User $user): bool
   {
      return  $user->isSuperAdmin();
   }

   /**
    * Determine whether the user can view the model.
    */
   public function view(User $user): bool
   {
      return $user->isAdmin()
         || $user->isSuperAdmin();
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
   public function update(User $user): bool
   {
      return $user->isAdmin()
         || $user->isSuperAdmin();
   }

   /**
    * Determine whether the user can delete the model.
    */
   public function delete(User $user): bool
   {
      return $user->isAdmin()
         || $user->isSuperAdmin();
   }

   /**
    * Determine whether the user can restore the model.
    */
   public function restore(User $user): bool
   {
      return $user->isSuperAdmin() || $user->isAdmin();
   }

   /**
    * Determine whether the user can permanently delete the model.
    */
   public function forceDelete(User $user): bool
   {
      return $user->isSuperAdmin() || $user->isAdmin();
   }
}
