<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MemberPolicy
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
   public function view(User $user, Member $member): bool
   {
      // O usuário pode ver membros de escolas que ele possui
      return $user->isAdmin()
         || $user->isSuperAdmin()
         || ($member->school && $member->school->user_id === $user->id);
   }

   /**
    * Determine whether the user can create models.
    * CORREÇÃO: Método create() não deve receber o model como parâmetro
    */
   public function create(User $user): bool
   {
      // Qualquer usuário autenticado pode criar membros
      return $user !== null;
   }

   /**
    * Determine whether the user can update the model.
    */
   public function update(User $user, Member $member): bool
   {
      // O usuário só pode atualizar membros de suas próprias escolas
      return $user->isAdmin()
         || $user->isSuperAdmin()
         || ($member->school && $member->school->user_id === $user->id);
   }

   /**
    * Determine whether the user can delete the model.
    */
   public function delete(User $user, Member $member): bool
   {
      // O usuário só pode deletar membros de suas próprias escolas
      return $user->isAdmin()
         || $user->isSuperAdmin()
         || ($member->school && $member->school->user_id === $user->id);
   }

   /**
    * Determine whether the user can restore the model.
    */
   public function restore(User $user, Member $member): bool
   {
      // O usuário só pode restaurar membros de suas próprias escolas
      return $user->isAdmin()
         || $user->isSuperAdmin();
   }

   /**
    * Determine whether the user can permanently delete the model.
    */
   public function forceDelete(User $user, Member $member): bool
   {
      // O usuário só pode deletar permanentemente membros de suas próprias escolas
      return $user->isAdmin()
         || $user->isSuperAdmin();
   }

   /**
    * Determine whether the user can create members for a specific school.
    * Método adicional para verificar se o usuário pode criar membros em uma escola específica
    */
   public function createForSchool(User $user, int $schoolId): bool
   {
      // Verifica se a escola pertence ao usuário
      return $user->schools()->where('id', $schoolId)->exists();
   }

   /**
    * Determine whether the user can manage all members of a school.
    */
   public function manageSchoolMembers(User $user, int $schoolId): bool
   {
      // Verifica se o usuário é dono da escola
      return $user->schools()->where('id', $schoolId)->exists();
   }
}
