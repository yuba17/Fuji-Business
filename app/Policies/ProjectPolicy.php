<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Todos los usuarios autenticados pueden ver proyectos
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        // Director: puede ver todos los proyectos
        if ($user->isDirector()) {
            return true;
        }

        // Manager: puede ver proyectos donde es manager
        if ($user->isManager()) {
            return $project->manager_id === $user->id
                || ($project->planComercial && ($project->planComercial->manager_id === $user->id || $project->planComercial->director_id === $user->id));
        }

        // Técnico: puede ver proyectos donde tiene tareas asignadas
        if ($user->isTecnico()) {
            return $project->tasks()->where('assigned_to', $user->id)->exists();
        }

        // Visualización: puede ver todos los proyectos
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
        // Director y manager pueden crear proyectos
        return $user->isDirector() || $user->isManager();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        // Director: puede editar todos los proyectos
        if ($user->isDirector()) {
            return true;
        }

        // Manager: puede editar proyectos donde es manager
        if ($user->isManager()) {
            return $project->manager_id === $user->id
                || ($project->planComercial && ($project->planComercial->manager_id === $user->id || $project->planComercial->director_id === $user->id));
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        // Solo director puede eliminar proyectos
        return $user->isDirector();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        // Solo director puede restaurar proyectos
        return $user->isDirector();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        // Solo director puede eliminar permanentemente
        return $user->isDirector();
    }
}
