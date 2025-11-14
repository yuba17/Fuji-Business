<?php

namespace App\Helpers;

use App\Models\Plan;
use App\Models\User;

class PermissionHelper
{
    /**
     * Verificar si el usuario puede acceder a un plan
     */
    public static function canAccessPlan(User $user, Plan $plan): bool
    {
        // Director: acceso total
        if ($user->isDirector()) {
            return true;
        }

        // Manager: acceso si es manager o director del plan, o si el plan pertenece a sus áreas
        if ($user->isManager()) {
            return $plan->manager_id === $user->id 
                || $plan->director_id === $user->id
                || $user->areas->contains($plan->area_id);
        }

        // Técnico: acceso si tiene tareas asignadas en el plan
        if ($user->isTecnico()) {
            return $plan->tasks()->where('assigned_to', $user->id)->exists();
        }

        // Visualización: acceso de solo lectura
        if ($user->isVisualizacion()) {
            return true;
        }

        return false;
    }

    /**
     * Verificar si el usuario puede editar un plan
     */
    public static function canEditPlan(User $user, Plan $plan): bool
    {
        // Director: puede editar todos los planes
        if ($user->isDirector()) {
            return true;
        }

        // Manager: puede editar si es manager o director del plan, o si el plan pertenece a sus áreas
        if ($user->isManager()) {
            return $plan->manager_id === $user->id 
                || $plan->director_id === $user->id
                || $user->areas->contains($plan->area_id);
        }

        return false;
    }

    /**
     * Verificar si el usuario puede aprobar un plan
     */
    public static function canApprovePlan(User $user, Plan $plan): bool
    {
        // Solo director puede aprobar planes
        return $user->isDirector();
    }

    /**
     * Verificar si el usuario puede eliminar un plan
     */
    public static function canDeletePlan(User $user, Plan $plan): bool
    {
        // Solo director puede eliminar planes
        return $user->isDirector();
    }
}

