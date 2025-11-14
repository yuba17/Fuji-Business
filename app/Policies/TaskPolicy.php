<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Todos los usuarios autenticados pueden ver tareas
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        // Director: puede ver todas las tareas
        if ($user->isDirector()) {
            return true;
        }

        // Manager: puede ver tareas de sus áreas o planes
        if ($user->isManager()) {
            return $task->assigned_to === $user->id
                || $task->created_by === $user->id
                || $user->areas->contains($task->area_id)
                || ($task->plan && ($task->plan->manager_id === $user->id || $task->plan->director_id === $user->id));
        }

        // Técnico: puede ver tareas asignadas a él o creadas por él
        if ($user->isTecnico()) {
            return $task->assigned_to === $user->id || $task->created_by === $user->id;
        }

        // Visualización: puede ver todas las tareas
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
        // Director, manager y técnico pueden crear tareas
        return $user->isDirector() || $user->isManager() || $user->isTecnico();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        // Director: puede editar todas las tareas
        if ($user->isDirector()) {
            return true;
        }

        // Manager: puede editar tareas de sus áreas o planes
        if ($user->isManager()) {
            return $task->assigned_to === $user->id
                || $task->created_by === $user->id
                || $user->areas->contains($task->area_id)
                || ($task->plan && ($task->plan->manager_id === $user->id || $task->plan->director_id === $user->id));
        }

        // Técnico: puede editar tareas asignadas a él o creadas por él
        if ($user->isTecnico()) {
            return $task->assigned_to === $user->id || $task->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        // Director y manager pueden eliminar tareas
        if ($user->isDirector()) {
            return true;
        }

        // Manager: puede eliminar tareas de sus áreas o planes
        if ($user->isManager()) {
            return $task->created_by === $user->id
                || $user->areas->contains($task->area_id)
                || ($task->plan && ($task->plan->manager_id === $user->id || $task->plan->director_id === $user->id));
        }

        // Técnico: puede eliminar tareas creadas por él
        if ($user->isTecnico()) {
            return $task->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        // Director y manager pueden restaurar tareas
        return $user->isDirector() || $user->isManager();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        // Solo director puede eliminar permanentemente
        return $user->isDirector();
    }
}
