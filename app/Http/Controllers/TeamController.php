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
        $directReports = $user->directReports()
            ->with(['roles', 'areas', 'internalRole', 'competencies', 'userCertifications'])
            ->orderBy('name')
            ->get();

        // Managers a cargo (directos)
        $managers = $directReports->filter(fn (User $u) => $u->isManager());

        // Personas a cargo de mis managers (segundo nivel)
        $secondLevelReports = User::query()
            ->whereIn('manager_id', $managers->pluck('id'))
            ->with(['roles', 'areas', 'manager', 'internalRole', 'competencies', 'userCertifications'])
            ->orderBy('name')
            ->get();

        // Todos los miembros del equipo para estadísticas de perfiles
        $allTeamMembers = $directReports->merge($secondLevelReports)->unique('id');
        
        // Estadísticas de perfiles del equipo
        $profileStats = [
            'total_members' => $allTeamMembers->count(),
            'avg_completion' => round($allTeamMembers->avg('profile_completion_percent') ?? 0, 1),
            'complete_profiles' => $allTeamMembers->where('profile_completion_percent', '>=', 100)->count(),
            'incomplete_profiles' => $allTeamMembers->where('profile_completion_percent', '<', 100)->count(),
            'low_completion' => $allTeamMembers->where('profile_completion_percent', '<', 50)->count(),
            'total_competencies' => $allTeamMembers->sum(fn($u) => $u->competencies->count()),
            'total_certifications' => $allTeamMembers->sum(fn($u) => $u->userCertifications->count()),
            'users_with_avatar' => $allTeamMembers->whereNotNull('avatar_url')->count(),
            'users_without_avatar' => $allTeamMembers->whereNull('avatar_url')->count(),
        ];

        return view('teams.my', [
            'user' => $user,
            'teamMembers' => $teamMembers,
            'areas' => $areas,
            'directReports' => $directReports,
            'managers' => $managers,
            'secondLevelReports' => $secondLevelReports,
            'allTeamMembers' => $allTeamMembers,
            'profileStats' => $profileStats,
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


