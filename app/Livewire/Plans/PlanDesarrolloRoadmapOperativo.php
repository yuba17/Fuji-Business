<?php

namespace App\Livewire\Plans;

use App\Models\Plan;
use App\Models\Milestone;
use App\Models\Task;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;

class PlanDesarrolloRoadmapOperativo extends Component
{
    public Plan $plan;
    
    // Filtros
    public $status = '';
    public $responsibleId = '';
    public $taskStatus = '';
    public $viewMode = 'gantt'; // gantt, list, timeline
    
    // Configuración del Gantt
    public $startDate = null;
    public $endDate = null;
    public $zoomLevel = 'month'; // week, month, quarter

    public function mount(Plan $plan)
    {
        $this->plan = $plan;
        
        // Calcular fechas por defecto (últimos 3 meses y próximos 9 meses)
        $this->startDate = now()->subMonths(3)->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->addMonths(9)->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        // Obtener milestones del plan
        $milestonesQuery = Milestone::where('plan_id', $this->plan->id)
            ->with(['responsible', 'predecessorDependencies.predecessor', 'successorDependencies.successor', 'tasks']);

        if ($this->status) {
            $milestonesQuery->where('status', $this->status);
        }

        if ($this->responsibleId) {
            $milestonesQuery->where('responsible_id', $this->responsibleId);
        }

        $milestones = $milestonesQuery->orderBy('target_date')->get();

        // Obtener tareas del plan para enriquecer el roadmap
        $tasksQuery = Task::where('plan_id', $this->plan->id)
            ->with(['assignedUser', 'milestone']);

        if ($this->taskStatus) {
            $tasksQuery->where('status', $this->taskStatus);
        }

        if ($this->responsibleId) {
            $tasksQuery->where('assigned_to', $this->responsibleId);
        }

        $tasks = $tasksQuery->orderBy('due_date')->get();

        // Calcular estadísticas
        $stats = [
            'total' => $milestones->count(),
            'not_started' => $milestones->where('status', 'not_started')->count(),
            'in_progress' => $milestones->where('status', 'in_progress')->count(),
            'completed' => $milestones->where('status', 'completed')->count(),
            'delayed' => $milestones->where('status', 'delayed')->count(),
            'cancelled' => $milestones->where('status', 'cancelled')->count(),
            'overdue' => $milestones->filter(fn($m) => $m->isDelayed())->count(),
            'tasks_total' => $tasks->count(),
            'tasks_overdue' => $tasks->filter(fn($task) => $task->isOverdue())->count(),
            'tasks_completed' => $tasks->where('status', 'done')->count(),
        ];

        // Preparar datos para el Gantt
        $ganttData = $this->prepareGanttData($milestones);

        // Obtener usuarios del área para filtro
        $teamUsers = User::where('area_id', $this->plan->area_id)
            ->orderBy('name')
            ->get();

        // Generar timeline de fechas para el Gantt
        $timeline = $this->generateTimeline();

        // Próximos hitos y tareas
        $upcomingMilestones = $milestones->filter(function ($milestone) {
            return $milestone->target_date && $milestone->target_date->between(now(), now()->addDays(60));
        })->sortBy('target_date');

        $upcomingTasks = $tasks->filter(function ($task) {
            return $task->due_date && $task->due_date->between(now(), now()->addDays(30)) && $task->status !== 'done';
        })->sortBy('due_date');

        // Swimlane por responsable
        $swimlaneData = $tasks->groupBy(function ($task) {
            return $task->assignedUser->name ?? 'Sin asignar';
        })->map(function ($group) {
            $completed = $group->where('status', 'done')->count();
            $progress = $group->count() > 0 ? round(($completed / $group->count()) * 100) : 0;

            return [
                'user' => $group->first()->assignedUser,
                'tasks' => $group,
                'progress' => $progress,
            ];
        })->sortByDesc('progress');

        // Alertas y dependencias
        $dependencyAlerts = [
            'delayedMilestones' => $milestones->filter(fn($m) => $m->isDelayed()),
            'overdueTasks' => $tasks->filter(fn($t) => $t->isOverdue()),
        ];

        return view('livewire.plans.plan-desarrollo-roadmap-operativo', [
            'milestones' => $milestones,
            'stats' => $stats,
            'ganttData' => $ganttData,
            'teamUsers' => $teamUsers,
            'timeline' => $timeline,
            'tasks' => $tasks,
            'upcomingMilestones' => $upcomingMilestones,
            'upcomingTasks' => $upcomingTasks,
            'swimlaneData' => $swimlaneData,
            'dependencyAlerts' => $dependencyAlerts,
            'plan' => $this->plan,
        ]);
    }

    protected function prepareGanttData($milestones)
    {
        return $milestones->map(function($milestone) {
            $start = $milestone->start_date ?? $milestone->target_date;
            $end = $milestone->end_date ?? $milestone->target_date;
            
            // Calcular posición y ancho en el timeline
            $startCarbon = Carbon::parse($start);
            $endCarbon = Carbon::parse($end);
            $duration = max(1, $startCarbon->diffInDays($endCarbon) + 1);
            
            return [
                'milestone' => $milestone,
                'start' => $start,
                'end' => $end,
                'duration' => $duration,
                'startCarbon' => $startCarbon,
                'endCarbon' => $endCarbon,
                'isDelayed' => $milestone->isDelayed(),
                'dependencies' => $milestone->predecessorDependencies,
            ];
        });
    }

    protected function generateTimeline()
    {
        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);
        $timeline = [];
        
        $current = $start->copy();
        
        while ($current <= $end) {
            if ($this->zoomLevel === 'week') {
                $timeline[] = [
                    'date' => $current->copy(),
                    'label' => $current->format('d/m'),
                    'isWeekend' => $current->isWeekend(),
                ];
                $current->addDay();
            } elseif ($this->zoomLevel === 'month') {
                $timeline[] = [
                    'date' => $current->copy(),
                    'label' => $current->locale('es')->translatedFormat('M Y'),
                    'days' => $current->daysInMonth,
                ];
                $current->addMonth();
            } else { // quarter
                $quarterEnd = $current->copy()->endOfQuarter();
                $daysInQuarter = $current->diffInDays($quarterEnd) + 1;
                $timeline[] = [
                    'date' => $current->copy(),
                    'label' => 'Q' . $current->quarter . ' ' . $current->format('Y'),
                    'days' => $daysInQuarter,
                ];
                $current->addQuarter();
            }
        }
        
        return $timeline;
    }
}
