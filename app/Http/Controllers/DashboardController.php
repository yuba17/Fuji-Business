<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Kpi;
use App\Models\Task;
use App\Models\Risk;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Datos según el rol
        if ($user->isDirector()) {
            return $this->directorDashboard();
        } elseif ($user->isManager()) {
            return $this->managerDashboard();
        } elseif ($user->isTecnico()) {
            return $this->tecnicoDashboard();
        } else {
            return $this->visualizationDashboard();
        }
    }

    /**
     * Dashboard para Director
     */
    private function directorDashboard(): View
    {
        // Optimización: Combinar consultas de planes en una sola
        $plansStats = Plan::selectRaw('
            COUNT(*) as total_plans,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as active_plans
        ', ['in_progress'])->first();

        $stats = [
            'total_plans' => (int) $plansStats->total_plans,
            'active_plans' => (int) $plansStats->active_plans,
            'total_kpis' => Kpi::where('is_active', true)->count(),
            'critical_risks' => Risk::where('category', 'critico')->count(),
            'pending_tasks' => Task::where('status', '!=', 'done')->count(),
            'recent_plans' => Plan::with(['planType', 'area', 'manager'])->latest()->take(5)->get(),
        ];
        
        return view('dashboards.director', $stats);
    }

    /**
     * Dashboard para Manager
     */
    private function managerDashboard(): View
    {
        $user = auth()->user();
        
        // Optimización: Usar joins en lugar de whereHas para mejor rendimiento
        $plansQuery = Plan::where('manager_id', $user->id);
        
        $plansStats = $plansQuery->selectRaw('
            COUNT(*) as my_plans,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as active_plans
        ', ['in_progress'])->first();

        // Optimización: Usar join en lugar de whereHas para KPIs
        $myKpis = Kpi::join('plans', 'kpis.plan_id', '=', 'plans.id')
            ->where('plans.manager_id', $user->id)
            ->where('kpis.is_active', true)
            ->count();

        // Optimización: Usar join en lugar de whereHas para Risks
        $myRisks = Risk::join('plans', 'risks.plan_id', '=', 'plans.id')
            ->where('plans.manager_id', $user->id)
            ->count();

        // Optimización: Usar join en lugar de whereHas para Tasks
        $myTasks = Task::join('plans', 'tasks.plan_id', '=', 'plans.id')
            ->where('plans.manager_id', $user->id)
            ->where('tasks.status', '!=', 'done')
            ->count();

        $stats = [
            'my_plans' => (int) $plansStats->my_plans,
            'active_plans' => (int) $plansStats->active_plans,
            'my_kpis' => $myKpis,
            'my_risks' => $myRisks,
            'my_tasks' => $myTasks,
            'recent_plans' => $plansQuery->with(['planType', 'area'])->latest()->take(5)->get(),
            'recent_tasks' => Task::join('plans', 'tasks.plan_id', '=', 'plans.id')
                ->where('plans.manager_id', $user->id)
                ->select('tasks.*')
                ->with(['plan', 'assignedUser'])
                ->latest('tasks.created_at')
                ->take(5)
                ->get(),
        ];
        
        return view('dashboards.manager', $stats);
    }

    /**
     * Dashboard para Técnico
     */
    private function tecnicoDashboard(): View
    {
        $user = auth()->user();
        
        // Optimización: Una sola consulta con agregaciones para todos los counts
        $tasksStats = Task::where('assigned_to', $user->id)
            ->selectRaw('
                COUNT(*) as my_tasks,
                SUM(CASE WHEN status != ? THEN 1 ELSE 0 END) as pending_tasks,
                SUM(CASE WHEN status != ? AND due_date < ? THEN 1 ELSE 0 END) as overdue_tasks,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as completed_tasks
            ', ['done', 'done', now()->toDateString(), 'done'])
            ->first();
        
        $stats = [
            'my_tasks' => (int) $tasksStats->my_tasks,
            'pending_tasks' => (int) $tasksStats->pending_tasks,
            'overdue_tasks' => (int) $tasksStats->overdue_tasks,
            'completed_tasks' => (int) $tasksStats->completed_tasks,
            'my_tasks_list' => Task::where('assigned_to', $user->id)
                ->with(['plan', 'milestone'])
                ->latest()
                ->take(10)
                ->get(),
        ];
        
        return view('dashboards.tecnico', $stats);
    }

    /**
     * Dashboard para Visualización
     */
    private function visualizationDashboard(): View
    {
        $stats = [
            'total_plans' => Plan::count(),
            'total_kpis' => Kpi::where('is_active', true)->count(),
            'total_tasks' => Task::count(),
            'total_risks' => Risk::count(),
        ];
        
        return view('dashboards.visualization', $stats);
    }
}
