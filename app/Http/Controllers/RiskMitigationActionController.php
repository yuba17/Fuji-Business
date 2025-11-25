<?php

namespace App\Http\Controllers;

use App\Models\Risk;
use App\Models\RiskMitigationAction;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RiskMitigationActionController extends Controller
{
    /**
     * Mostrar formulario de creación
     */
    public function create(Risk $risk): View
    {
        $this->authorize('update', $risk);
        
        // Optimización: Solo seleccionar campos necesarios y ordenar por nombre
        $users = \App\Models\User::select('id', 'name')->orderBy('name')->get();
        
        return view('risks.mitigation-actions.create', compact('risk', 'users'));
    }

    /**
     * Guardar nueva acción de mitigación
     */
    public function store(Request $request, Risk $risk): RedirectResponse
    {
        $this->authorize('update', $risk);
        
        $validated = $request->validate([
            'action' => 'required|string|max:255',
            'description' => 'nullable|string',
            'responsible_id' => 'nullable|exists:users,id',
            'target_date' => 'nullable|date',
            'status' => 'nullable|in:pending,in_progress,completed,cancelled',
            'cost' => 'nullable|numeric|min:0',
            'expected_probability_reduction' => 'nullable|integer|min:0|max:5',
            'expected_impact_reduction' => 'nullable|integer|min:0|max:5',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['risk_id'] = $risk->id;
        $validated['status'] = $validated['status'] ?? 'pending';

        RiskMitigationAction::create($validated);

        return redirect()->route('risks.show', $risk)
            ->with('success', 'Acción de mitigación creada correctamente');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Risk $risk, RiskMitigationAction $mitigationAction): View
    {
        $this->authorize('update', $risk);
        
        if ($mitigationAction->risk_id !== $risk->id) {
            abort(404);
        }
        
        // Optimización: Solo seleccionar campos necesarios y ordenar por nombre
        $users = \App\Models\User::select('id', 'name')->orderBy('name')->get();
        
        return view('risks.mitigation-actions.edit', compact('risk', 'mitigationAction', 'users'));
    }

    /**
     * Actualizar acción de mitigación
     */
    public function update(Request $request, Risk $risk, RiskMitigationAction $mitigationAction): RedirectResponse
    {
        $this->authorize('update', $risk);
        
        if ($mitigationAction->risk_id !== $risk->id) {
            abort(404);
        }
        
        $validated = $request->validate([
            'action' => 'required|string|max:255',
            'description' => 'nullable|string',
            'responsible_id' => 'nullable|exists:users,id',
            'target_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'cost' => 'nullable|numeric|min:0',
            'expected_probability_reduction' => 'nullable|integer|min:0|max:5',
            'expected_impact_reduction' => 'nullable|integer|min:0|max:5',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validated['status'] === 'completed' && !$mitigationAction->completed_at) {
            $validated['completed_at'] = now();
        }

        $mitigationAction->update($validated);

        return redirect()->route('risks.show', $risk)
            ->with('success', 'Acción de mitigación actualizada correctamente');
    }

    /**
     * Eliminar acción de mitigación
     */
    public function destroy(Risk $risk, RiskMitigationAction $mitigationAction): RedirectResponse
    {
        $this->authorize('update', $risk);
        
        if ($mitigationAction->risk_id !== $risk->id) {
            abort(404);
        }
        
        $mitigationAction->delete();

        return redirect()->route('risks.show', $risk)
            ->with('success', 'Acción de mitigación eliminada correctamente');
    }
}
