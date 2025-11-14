<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Plan;
use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Task::class);
        
        $user = auth()->user();
        
        $query = Task::with(['plan', 'area', 'assignedUser', 'milestone']);
        
        // Filtrar según rol
        if ($user->isManager()) {
            $query->where(function($q) use ($user) {
                $q->where('assigned_to', $user->id)
                  ->orWhere('created_by', $user->id)
                  ->orWhereIn('area_id', $user->areas->pluck('id'))
                  ->orWhereHas('plan', function($planQ) use ($user) {
                      $planQ->where('manager_id', $user->id)
                            ->orWhere('director_id', $user->id);
                  });
            });
        } elseif ($user->isTecnico()) {
            $query->where(function($q) use ($user) {
                $q->where('assigned_to', $user->id)
                  ->orWhere('created_by', $user->id);
            });
        } elseif ($user->isVisualizacion()) {
            // Visualización ve todas las tareas
        }
        
        // Filtros
        if ($request->filled('plan_id')) {
            $query->where('plan_id', $request->plan_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        $tasks = $query->latest()->paginate(20);
        $plans = Plan::where('status', '!=', 'archived')->get();
        
        return view('tasks.index', compact('tasks', 'plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Task::class);
        
        $user = auth()->user();
        $plans = Plan::where('status', '!=', 'archived')->get();
        $areas = Area::where('is_active', true)->get();
        $users = User::all();
        $planId = $request->get('plan_id');
        
        return view('tasks.create', compact('plans', 'areas', 'users', 'planId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Task::class);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'plan_id' => 'required|exists:plans,id',
            'area_id' => 'nullable|exists:areas,id',
            'milestone_id' => 'nullable|exists:milestones,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'nullable|in:todo,in_progress,blocked,review,done',
            'priority' => 'nullable|in:low,medium,high,critical',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = $validated['status'] ?? 'todo';
        $validated['priority'] = $validated['priority'] ?? 'medium';

        $task = Task::create($validated);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Tarea creada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): View
    {
        $this->authorize('view', $task);
        
        $task->load(['plan', 'area', 'assignedUser', 'creator', 'milestone', 'parentTask', 'subtasks']);
        
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): View
    {
        $this->authorize('update', $task);
        
        $plans = Plan::where('status', '!=', 'archived')->get();
        $areas = Area::where('is_active', true)->get();
        $users = User::all();
        
        return view('tasks.edit', compact('task', 'plans', 'areas', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'plan_id' => 'required|exists:plans,id',
            'area_id' => 'nullable|exists:areas,id',
            'milestone_id' => 'nullable|exists:milestones,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'nullable|in:todo,in_progress,blocked,review,done',
            'priority' => 'nullable|in:low,medium,high,critical',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'actual_hours' => 'nullable|numeric|min:0',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Tarea actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);
        
        $task->delete();
        
        return redirect()->route('tasks.index')
            ->with('success', 'Tarea eliminada correctamente');
    }

    /**
     * Mostrar vista Kanban de tareas
     */
    public function kanban(Request $request): View
    {
        $this->authorize('viewAny', Task::class);
        
        $planId = $request->get('plan');
        
        return view('tasks.kanban', [
            'planId' => $planId,
        ]);
    }
}
