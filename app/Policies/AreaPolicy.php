<?php

namespace App\Policies;

use App\Models\Area;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AreaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Todos los usuarios autenticados pueden ver áreas
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Area $area): bool
    {
        // Todos pueden ver áreas
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Solo director puede crear áreas
        return $user->isDirector();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Area $area): bool
    {
        // Director: puede editar todas las áreas
        if ($user->isDirector()) {
            return true;
        }

        // Manager: puede editar áreas donde es director o manager
        if ($user->isManager()) {
            return $area->director_id === $user->id 
                || $user->areas->contains($area->id);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Area $area): bool
    {
        // Solo director puede eliminar áreas
        return $user->isDirector();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Area $area): bool
    {
        // Solo director puede restaurar áreas
        return $user->isDirector();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Area $area): bool
    {
        // Solo director puede eliminar permanentemente
        return $user->isDirector();
    }
}
