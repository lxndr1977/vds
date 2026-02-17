<?php

namespace App\Policies;

use App\Models\School;
use App\Models\User;

class SchoolPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Apenas usuários autenticados podem criar escolas.
        return $user !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, School $school): bool
    {
        // O usuário pode atualizar a escola se ele for o proprietário dela.
        return $user->id === $school->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, School $school): bool
    {
        // O usuário pode deletar a escola se ele for o proprietário dela.
        return $user->id === $school->user_id;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Qualquer usuário autenticado pode visualizar a lista de escolas.
        return $user !== null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, School $school): bool
    {
        // O usuário pode visualizar a escola se ele for o proprietário dela.
        // Ou, se a escola é um projeto social/universitário, pode ser pública.
        // Ajuste esta lógica conforme a necessidade do seu projeto.
        return $user->id === $school->user_id;
    }
}
