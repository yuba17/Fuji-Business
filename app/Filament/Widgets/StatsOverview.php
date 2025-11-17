<?php

namespace App\Filament\Widgets;

use App\Models\Plan;
use App\Models\Task;
use App\Models\Risk;
use App\Models\Kpi;
use App\Models\Client;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalPlans = Plan::count();
        $activePlans = Plan::where('status', 'in_progress')->count();
        $approvedPlans = Plan::where('status', 'approved')->count();
        
        $totalTasks = Task::count();
        $pendingTasks = Task::where('status', 'pending')->count();
        $completedTasks = Task::where('status', 'completed')->count();
        
        $totalRisks = Risk::count();
        $criticalRisks = Risk::where('category', 'critico')->count();
        $highRisks = Risk::where('category', 'alto')->count();
        
        $totalKpis = Kpi::where('is_active', true)->count();
        $greenKpis = Kpi::where('status', 'green')->where('is_active', true)->count();
        $redKpis = Kpi::where('status', 'red')->where('is_active', true)->count();
        
        $totalClients = Client::where('is_active', true)->count();
        $totalProjects = Project::count();
        $activeProjects = Project::where('status', 'activo')->count();

        return [
            Stat::make('Planes Activos', $activePlans)
                ->description('De ' . $totalPlans . ' planes totales')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success')
                ->chart([
                    Plan::where('status', 'in_progress')->whereMonth('created_at', now()->subMonths(5))->count(),
                    Plan::where('status', 'in_progress')->whereMonth('created_at', now()->subMonths(4))->count(),
                    Plan::where('status', 'in_progress')->whereMonth('created_at', now()->subMonths(3))->count(),
                    Plan::where('status', 'in_progress')->whereMonth('created_at', now()->subMonths(2))->count(),
                    Plan::where('status', 'in_progress')->whereMonth('created_at', now()->subMonths(1))->count(),
                    $activePlans,
                ]),
            
            Stat::make('Tareas Pendientes', $pendingTasks)
                ->description($completedTasks . ' completadas de ' . $totalTasks)
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('warning')
                ->chart([
                    Task::where('status', 'pending')->whereMonth('created_at', now()->subMonths(5))->count(),
                    Task::where('status', 'pending')->whereMonth('created_at', now()->subMonths(4))->count(),
                    Task::where('status', 'pending')->whereMonth('created_at', now()->subMonths(3))->count(),
                    Task::where('status', 'pending')->whereMonth('created_at', now()->subMonths(2))->count(),
                    Task::where('status', 'pending')->whereMonth('created_at', now()->subMonths(1))->count(),
                    $pendingTasks,
                ]),
            
            Stat::make('Riesgos Críticos', $criticalRisks)
                ->description($highRisks . ' de alto riesgo, ' . $totalRisks . ' totales')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger')
                ->chart([
                    Risk::where('category', 'critico')->whereMonth('created_at', now()->subMonths(5))->count(),
                    Risk::where('category', 'critico')->whereMonth('created_at', now()->subMonths(4))->count(),
                    Risk::where('category', 'critico')->whereMonth('created_at', now()->subMonths(3))->count(),
                    Risk::where('category', 'critico')->whereMonth('created_at', now()->subMonths(2))->count(),
                    Risk::where('category', 'critico')->whereMonth('created_at', now()->subMonths(1))->count(),
                    $criticalRisks,
                ]),
            
            Stat::make('KPIs en Verde', $greenKpis)
                ->description($redKpis . ' en rojo de ' . $totalKpis . ' KPIs activos')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('success')
                ->chart([
                    Kpi::where('status', 'green')->where('is_active', true)->whereMonth('created_at', now()->subMonths(5))->count(),
                    Kpi::where('status', 'green')->where('is_active', true)->whereMonth('created_at', now()->subMonths(4))->count(),
                    Kpi::where('status', 'green')->where('is_active', true)->whereMonth('created_at', now()->subMonths(3))->count(),
                    Kpi::where('status', 'green')->where('is_active', true)->whereMonth('created_at', now()->subMonths(2))->count(),
                    Kpi::where('status', 'green')->where('is_active', true)->whereMonth('created_at', now()->subMonths(1))->count(),
                    $greenKpis,
                ]),
            
            Stat::make('Clientes Activos', $totalClients)
                ->description($activeProjects . ' proyectos activos de ' . $totalProjects)
                ->descriptionIcon('heroicon-m-building-office')
                ->color('info')
                ->chart([
                    Client::where('is_active', true)->whereMonth('created_at', now()->subMonths(5))->count(),
                    Client::where('is_active', true)->whereMonth('created_at', now()->subMonths(4))->count(),
                    Client::where('is_active', true)->whereMonth('created_at', now()->subMonths(3))->count(),
                    Client::where('is_active', true)->whereMonth('created_at', now()->subMonths(2))->count(),
                    Client::where('is_active', true)->whereMonth('created_at', now()->subMonths(1))->count(),
                    $totalClients,
                ]),
            
            Stat::make('Planes Aprobados', $approvedPlans)
                ->description('Listos para ejecución')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([
                    Plan::where('status', 'approved')->whereMonth('created_at', now()->subMonths(5))->count(),
                    Plan::where('status', 'approved')->whereMonth('created_at', now()->subMonths(4))->count(),
                    Plan::where('status', 'approved')->whereMonth('created_at', now()->subMonths(3))->count(),
                    Plan::where('status', 'approved')->whereMonth('created_at', now()->subMonths(2))->count(),
                    Plan::where('status', 'approved')->whereMonth('created_at', now()->subMonths(1))->count(),
                    $approvedPlans,
                ]),
        ];
    }
}
