<?php

namespace App\Policies;

use App\Models\Risk;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RiskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Todos los usuarios autenticados pueden ver riesgos
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Risk $risk): bool
    {
        // Director: puede ver todos los riesgos
        if ($user->isDirector()) {
            return true;
        }

        // Manager: puede ver riesgos de sus áreas o donde es owner
        if ($user->isManager()) {
            return $risk->owner_id === $user->id 
                || $user->areas->contains($risk->area_id)
                || ($risk->plan && ($risk->plan->manager_id === $user->id || $risk->plan->director_id === $user->id));
        }

        // Técnico: puede ver riesgos donde es owner
        if ($user->isTecnico()) {
            return $risk->owner_id === $user->id;
        }

        // Visualización: puede ver todos los riesgos
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
        // Solo director y manager pueden crear riesgos
        return $user->isDirector() || $user->isManager();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Risk $risk): bool
    {
        // Director: puede editar todos los riesgos
        if ($user->isDirector()) {
            return true;
        }

        // Manager: puede editar riesgos de sus áreas o donde es owner
        if ($user->isManager()) {
            return $risk->owner_id === $user->id 
                || $user->areas->contains($risk->area_id)
                || ($risk->plan && ($risk->plan->manager_id === $user->id || $risk->plan->director_id === $user->id));
        }

        // Técnico: puede editar riesgos donde es owner
        if ($user->isTecnico()) {
            return $risk->owner_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Risk $risk): bool
    {
        // Solo director puede eliminar riesgos
        return $user->isDirector();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Risk $risk): bool
    {
        // Solo director puede restaurar riesgos
        return $user->isDirector();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Risk $risk): bool
    {
        // Solo director puede eliminar permanentemente
        return $user->isDirector();
    }
}
