<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Kpi;
use App\Models\Task;
use App\Models\Risk;
use App\Models\Area;
use Illuminate\Http\Request;
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
        $stats = [
            'total_plans' => Plan::count(),
            'active_plans' => Plan::where('status', 'in_progress')->count(),
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
        
        $stats = [
            'my_plans' => Plan::where('manager_id', $user->id)->count(),
            'active_plans' => Plan::where('manager_id', $user->id)->where('status', 'in_progress')->count(),
            'my_kpis' => Kpi::whereHas('plan', function($q) use ($user) {
                $q->where('manager_id', $user->id);
            })->where('is_active', true)->count(),
            'my_risks' => Risk::whereHas('plan', function($q) use ($user) {
                $q->where('manager_id', $user->id);
            })->count(),
            'my_tasks' => Task::whereHas('plan', function($q) use ($user) {
                $q->where('manager_id', $user->id);
            })->where('status', '!=', 'done')->count(),
            'recent_plans' => Plan::where('manager_id', $user->id)->with(['planType', 'area'])->latest()->take(5)->get(),
            'recent_tasks' => Task::whereHas('plan', function($q) use ($user) {
                $q->where('manager_id', $user->id);
            })->with(['plan', 'assignedUser'])->latest()->take(5)->get(),
        ];
        
        return view('dashboards.manager', $stats);
    }

    /**
     * Dashboard para Técnico
     */
    private function tecnicoDashboard(): View
    {
        $user = auth()->user();
        
        $stats = [
            'my_tasks' => Task::where('assigned_to', $user->id)->count(),
            'pending_tasks' => Task::where('assigned_to', $user->id)->where('status', '!=', 'done')->count(),
            'overdue_tasks' => Task::where('assigned_to', $user->id)
                ->where('status', '!=', 'done')
                ->where('due_date', '<', now())
                ->count(),
            'completed_tasks' => Task::where('assigned_to', $user->id)->where('status', 'done')->count(),
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
