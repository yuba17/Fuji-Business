<?php

namespace App\Policies;

use App\Models\Dashboard;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DashboardPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Todos los usuarios autenticados pueden ver la lista de dashboards
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Dashboard $dashboard): bool
    {
        // Director: puede ver todos los dashboards
        if ($user->isDirector()) {
            return true;
        }

        // Usuario puede ver sus propios dashboards
        if ($dashboard->user_id === $user->id) {
            return true;
        }

        // Usuario puede ver dashboards compartidos
        if ($dashboard->is_shared) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Todos los usuarios autenticados pueden crear dashboards
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Dashboard $dashboard): bool
    {
        // Director: puede editar todos los dashboards
        if ($user->isDirector()) {
            return true;
        }

        // Usuario solo puede editar sus propios dashboards
        return $dashboard->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Dashboard $dashboard): bool
    {
        // Director: puede eliminar todos los dashboards
        if ($user->isDirector()) {
            return true;
        }

        // Usuario solo puede eliminar sus propios dashboards
        return $dashboard->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Dashboard $dashboard): bool
    {
        // Solo director puede restaurar dashboards
        return $user->isDirector();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Dashboard $dashboard): bool
    {
        // Solo director puede eliminar permanentemente
        return $user->isDirector();
    }
}
