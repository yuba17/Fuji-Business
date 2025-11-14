<?php

namespace App\Policies;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PlanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Todos los usuarios autenticados pueden ver la lista de planes
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Plan $plan): bool
    {
        // Director: puede ver todos los planes
        if ($user->isDirector()) {
            return true;
        }

        // Manager: puede ver planes de sus áreas o donde es manager
        if ($user->isManager()) {
            return $plan->manager_id === $user->id 
                || $plan->director_id === $user->id
                || $user->areas->contains($plan->area_id);
        }

        // Técnico: puede ver planes donde tiene tareas asignadas
        if ($user->isTecnico()) {
            return $plan->tasks()->where('assigned_to', $user->id)->exists();
        }

        // Visualización: puede ver todos los planes (solo lectura)
        if ($user->isVisualizacion()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Solo director y manager pueden crear planes
        return $user->isDirector() || $user->isManager();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Plan $plan): bool
    {
        // Director: puede editar todos los planes
        if ($user->isDirector()) {
            return true;
        }

        // Manager: puede editar planes donde es manager o director
        if ($user->isManager()) {
            return $plan->manager_id === $user->id 
                || $plan->director_id === $user->id
                || $user->areas->contains($plan->area_id);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Plan $plan): bool
    {
        // Solo director puede eliminar planes
        return $user->isDirector();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Plan $plan): bool
    {
        // Solo director puede restaurar planes
        return $user->isDirector();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Plan $plan): bool
    {
        // Solo director puede eliminar permanentemente
        return $user->isDirector();
    }
}
