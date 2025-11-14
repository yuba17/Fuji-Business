<?php

namespace App\Policies;

use App\Models\Kpi;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class KpiPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Todos los usuarios autenticados pueden ver KPIs
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Kpi $kpi): bool
    {
        // Director: puede ver todos los KPIs
        if ($user->isDirector()) {
            return true;
        }

        // Manager: puede ver KPIs de sus áreas o donde es responsable
        if ($user->isManager()) {
            return $kpi->responsible_id === $user->id 
                || $user->areas->contains($kpi->area_id)
                || ($kpi->plan && ($kpi->plan->manager_id === $user->id || $kpi->plan->director_id === $user->id));
        }

        // Técnico: puede ver KPIs donde es responsable
        if ($user->isTecnico()) {
            return $kpi->responsible_id === $user->id;
        }

        // Visualización: puede ver todos los KPIs
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
        // Solo director y manager pueden crear KPIs
        return $user->isDirector() || $user->isManager();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Kpi $kpi): bool
    {
        // Director: puede editar todos los KPIs
        if ($user->isDirector()) {
            return true;
        }

        // Manager: puede editar KPIs de sus áreas o donde es responsable
        if ($user->isManager()) {
            return $kpi->responsible_id === $user->id 
                || $user->areas->contains($kpi->area_id)
                || ($kpi->plan && ($kpi->plan->manager_id === $user->id || $kpi->plan->director_id === $user->id));
        }

        // Técnico: puede actualizar valores de KPIs donde es responsable
        if ($user->isTecnico()) {
            return $kpi->responsible_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Kpi $kpi): bool
    {
        // Solo director puede eliminar KPIs
        return $user->isDirector();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Kpi $kpi): bool
    {
        // Solo director puede restaurar KPIs
        return $user->isDirector();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Kpi $kpi): bool
    {
        // Solo director puede eliminar permanentemente
        return $user->isDirector();
    }
}
