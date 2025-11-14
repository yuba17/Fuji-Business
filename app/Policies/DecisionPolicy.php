<?php

namespace App\Policies;

use App\Models\Decision;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DecisionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Todos los usuarios autenticados pueden ver decisiones
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Decision $decision): bool
    {
        // Todos pueden ver decisiones
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Director y manager pueden crear decisiones
        return $user->isDirector() || $user->isManager();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Decision $decision): bool
    {
        // Director: puede editar todas las decisiones
        if ($user->isDirector()) {
            return true;
        }

        // Manager: puede editar decisiones que propuso
        if ($user->isManager()) {
            return $decision->proponent_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Decision $decision): bool
    {
        // Solo director puede eliminar decisiones
        return $user->isDirector();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Decision $decision): bool
    {
        // Solo director puede restaurar decisiones
        return $user->isDirector();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Decision $decision): bool
    {
        // Solo director puede eliminar permanentemente
        return $user->isDirector();
    }
}
