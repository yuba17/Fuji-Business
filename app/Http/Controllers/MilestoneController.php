<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MilestoneController extends Controller
{
    /**
     * Listar hitos de un plan
     */
    public function index(Request $request, Plan $plan): View
    {
        $this->authorize('view', $plan);
        
        $milestones = $plan->milestones()
            ->with(['responsible', 'tasks'])
            ->orderBy('order')
            ->orderBy('target_date')
            ->get();
        
        return view('milestones.index', compact('plan', 'milestones'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create(Plan $plan): View
    {
        $this->authorize('update', $plan);
        
        $milestones = $plan->milestones()->orderBy('name')->get();
        // Optimización: Pasar usuarios desde el controlador en lugar de cargarlos en la vista
        $users = User::select('id', 'name')->orderBy('name')->get();
        
        return view('milestones.create', compact('plan', 'milestones', 'users'));
    }

    /**
     * Guardar nuevo hito
     */
    public function store(Request $request, Plan $plan): RedirectResponse
    {
        $this->authorize('update', $plan);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'target_date' => 'required|date',
            'responsible_id' => 'nullable|exists:users,id',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['plan_id'] = $plan->id;
        $validated['status'] = 'not_started';
        $validated['progress_percentage'] = $validated['progress_percentage'] ?? 0;
        $validated['order'] = $validated['order'] ?? 0;

        $milestone = Milestone::create($validated);

        return redirect()->route('plans.roadmap', $plan)
            ->with('success', 'Hito creado correctamente');
    }

    /**
     * Mostrar hito
     */
    public function show(Plan $plan, Milestone $milestone): View
    {
        $this->authorize('view', $plan);
        
        if ($milestone->plan_id !== $plan->id) {
            abort(404);
        }
        
        $milestone->load(['responsible', 'tasks', 'predecessorDependencies.predecessor', 'successorDependencies.successor']);
        
        return view('milestones.show', compact('plan', 'milestone'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Plan $plan, Milestone $milestone): View
    {
        $this->authorize('update', $plan);
        
        if ($milestone->plan_id !== $plan->id) {
            abort(404);
        }
        
        $milestones = $plan->milestones()
            ->where('id', '!=', $milestone->id)
            ->orderBy('name')
            ->get();
        // Optimización: Pasar usuarios desde el controlador en lugar de cargarlos en la vista
        $users = User::select('id', 'name')->orderBy('name')->get();
        
        return view('milestones.edit', compact('plan', 'milestone', 'milestones', 'users'));
    }

    /**
     * Actualizar hito
     */
    public function update(Request $request, Plan $plan, Milestone $milestone): RedirectResponse
    {
        $this->authorize('update', $plan);
        
        if ($milestone->plan_id !== $plan->id) {
            abort(404);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'target_date' => 'required|date',
            'status' => 'required|in:not_started,in_progress,completed,delayed,cancelled',
            'responsible_id' => 'nullable|exists:users,id',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'order' => 'nullable|integer|min:0',
        ]);

        // Actualizar estado si está retrasado
        if ($milestone->isDelayed() && $validated['status'] !== 'completed' && $validated['status'] !== 'cancelled') {
            $validated['status'] = 'delayed';
        }

        $milestone->update($validated);

        return redirect()->route('plans.roadmap', $plan)
            ->with('success', 'Hito actualizado correctamente');
    }

    /**
     * Eliminar hito
     */
    public function destroy(Plan $plan, Milestone $milestone): RedirectResponse
    {
        $this->authorize('update', $plan);
        
        if ($milestone->plan_id !== $plan->id) {
            abort(404);
        }
        
        $milestone->delete();

        return redirect()->route('plans.roadmap', $plan)
            ->with('success', 'Hito eliminado correctamente');
    }
}
