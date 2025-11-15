<?php

namespace App\Http\Controllers;

use App\Models\Kpi;
use App\Models\KpiHistory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class KpiHistoryController extends Controller
{
    /**
     * Mostrar historial de un KPI
     */
    public function index(Kpi $kpi): View
    {
        $this->authorize('view', $kpi);
        
        $history = $kpi->history()
            ->with('updater')
            ->orderBy('recorded_at', 'desc')
            ->paginate(20);
        
        return view('kpis.history', compact('kpi', 'history'));
    }

    /**
     * Crear nueva entrada en el historial
     */
    public function store(Request $request, Kpi $kpi): RedirectResponse
    {
        $this->authorize('update', $kpi);
        
        $validated = $request->validate([
            'value' => 'required|numeric',
            'recorded_at' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['kpi_id'] = $kpi->id;
        $validated['updated_by'] = auth()->id();
        $validated['recorded_at'] = $validated['recorded_at'] ?? now();

        KpiHistory::create($validated);

        // Actualizar valor actual del KPI
        $kpi->update([
            'current_value' => $validated['value'],
            'last_updated_at' => $validated['recorded_at'],
        ]);

        // Actualizar estado
        $kpi->updateStatus();

        return redirect()->back()
            ->with('success', 'Valor registrado en el historial correctamente');
    }

    /**
     * Eliminar entrada del historial
     */
    public function destroy(Kpi $kpi, KpiHistory $history): RedirectResponse
    {
        $this->authorize('update', $kpi);
        
        if ($history->kpi_id !== $kpi->id) {
            abort(404);
        }

        $history->delete();

        return redirect()->back()
            ->with('success', 'Entrada del historial eliminada correctamente');
    }
}
