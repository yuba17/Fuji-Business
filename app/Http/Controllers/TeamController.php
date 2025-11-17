<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Task;
use App\Models\User;
use App\Services\TeamService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Ver el equipo del usuario actual.
     */
    public function myTeam(TeamService $teamService): View
    {
        /** @var User $user */
        $user = auth()->user();

        $teamMembers = $teamService->getUserTeam($user);
        $areas = $teamService->getUserTeamAreas($user);

        // Personas que reportan directamente a este usuario
        $directReports = $user->directReports()->with(['roles', 'areas'])->orderBy('name')->get();

        // Managers a cargo (directos)
        $managers = $directReports->filter(fn (User $u) => $u->isManager());

        // Personas a cargo de mis managers (segundo nivel)
        $secondLevelReports = User::query()
            ->whereIn('manager_id', $managers->pluck('id'))
            ->with(['roles', 'areas', 'manager'])
            ->orderBy('name')
            ->get();

        return view('teams.my', [
            'user' => $user,
            'teamMembers' => $teamMembers,
            'areas' => $areas,
            'directReports' => $directReports,
            'managers' => $managers,
            'secondLevelReports' => $secondLevelReports,
        ]);
    }

    /**
     * Ver calendario de equipo (tareas del equipo ordenadas por fecha).
     */
    public function calendar(TeamService $teamService): View
    {
        /** @var User $user */
        $user = auth()->user();

        $teamMembers = $teamService->getUserTeam($user);
        $memberIds = $teamMembers->pluck('id')->push($user->id)->unique()->all();

        $tasks = Task::with(['plan', 'area'])
            ->whereIn('assigned_to', $memberIds)
            ->orderBy('due_date')
            ->orderBy('priority')
            ->get();

        return view('teams.calendar', [
            'user' => $user,
            'tasks' => $tasks,
            'teamMembers' => $teamMembers,
        ]);
    }

    /**
     * Ver todos los equipos (solo director).
     */
    public function index(TeamService $teamService): View|Response
    {
        /** @var User $user */
        $user = auth()->user();

        if (! $user->isDirector()) {
            abort(403, 'Solo los directores pueden ver todos los equipos.');
        }

        $areasWithTeams = $teamService->getAllTeamsByArea();

        return view('teams.index', [
            'user' => $user,
            'areasWithTeams' => $areasWithTeams,
        ]);
    }
}


