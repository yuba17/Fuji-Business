<?php

namespace App\Services;

use App\Models\Area;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TeamService
{
    /**
     * Obtener el equipo del usuario actual (personas con áreas compartidas).
     */
    public function getUserTeam(User $user): Collection
    {
        // Áreas asociadas al usuario (manager / miembro)
        $areaIds = $user->areas()->pluck('areas.id')->toArray();

        // Para directores, incluir áreas donde es director
        $directorAreaIds = Area::where('director_id', $user->id)->pluck('id')->toArray();

        $allAreaIds = array_unique(array_merge($areaIds, $directorAreaIds));

        if (empty($allAreaIds)) {
            // Si no tiene áreas, devolver una colección Eloquent vacía
            return User::query()->whereRaw('1 = 0')->get();
        }

        return User::query()
            ->where('id', '!=', $user->id)
            ->whereHas('areas', function ($q) use ($allAreaIds) {
                $q->whereIn('areas.id', $allAreaIds);
            })
            ->with(['roles', 'areas'])
            ->orderBy('name')
            ->get();
    }

    /**
     * Áreas que forman parte del equipo del usuario.
     */
    public function getUserTeamAreas(User $user): Collection
    {
        $areaIds = $user->areas()->pluck('areas.id')->toArray();
        $directorAreaIds = Area::where('director_id', $user->id)->pluck('id')->toArray();

        $allAreaIds = array_unique(array_merge($areaIds, $directorAreaIds));

        return Area::whereIn('id', $allAreaIds)
            ->with(['director', 'managers'])
            ->orderBy('name')
            ->get();
    }

    /**
     * Obtener todos los equipos agrupados por área (para directores).
     *
     * @return Collection<Area>
     */
    public function getAllTeamsByArea(): Collection
    {
        return Area::with([
                'director',
                'managers',
                'tasks.assignedUser',
            ])
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get();
    }
}


