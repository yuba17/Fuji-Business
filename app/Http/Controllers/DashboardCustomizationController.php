<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DashboardCustomizationController extends Controller
{
    /**
     * Mostrar vista de personalización
     */
    public function customize($dashboardId = null): View
    {
        if ($dashboardId) {
            $dashboard = Dashboard::findOrFail($dashboardId);
            $this->authorize('update', $dashboard);
        }

        return view('dashboards.customize', compact('dashboardId'));
    }

    /**
     * Listar dashboards del usuario
     */
    public function index(): View
    {
        $dashboards = Dashboard::where('user_id', auth()->id())
            ->orWhere('is_shared', true)
            ->with('user')
            ->latest()
            ->get();

        return view('dashboards.index', compact('dashboards'));
    }

    /**
     * Crear nuevo dashboard
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $dashboard = Dashboard::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'user_id' => auth()->id(),
            'type' => 'custom',
            'is_default' => false,
        ]);

        return redirect()->route('dashboards.customize', $dashboard)
            ->with('success', 'Dashboard creado correctamente');
    }

    /**
     * Eliminar dashboard
     */
    public function destroy(Dashboard $dashboard): RedirectResponse
    {
        $this->authorize('delete', $dashboard);
        $dashboard->delete();

        return redirect()->route('dashboards.index')
            ->with('success', 'Dashboard eliminado correctamente');
    }
}
