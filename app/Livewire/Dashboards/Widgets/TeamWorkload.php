<?php

namespace App\Livewire\Dashboards\Widgets;

use App\Models\Task;
use App\Models\User;
use Livewire\Component;

class TeamWorkload extends Component
{
    public $teamWorkload = [];
    public $totalTasks = 0;
    public $overdueTasks = 0;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Carga de trabajo por usuario
        $users = User::whereHas('assignedTasks')->get();
        
        $this->teamWorkload = $users->map(function ($user) {
            $tasks = Task::where('assigned_to', $user->id)
                ->where('status', '!=', 'done')
                ->get();
            
            $overdue = $tasks->filter(function ($task) {
                return $task->due_date && $task->due_date < now();
            })->count();

            return [
                'user' => $user,
                'total_tasks' => $tasks->count(),
                'overdue_tasks' => $overdue,
                'estimated_hours' => $tasks->sum('estimated_hours') ?? 0,
            ];
        })->sortByDesc('total_tasks')->take(5)->values();

        $this->totalTasks = Task::where('status', '!=', 'done')->count();
        $this->overdueTasks = Task::where('status', '!=', 'done')
            ->where('due_date', '<', now())
            ->count();
    }

    public function render()
    {
        return view('livewire.dashboards.widgets.team-workload');
    }
}
