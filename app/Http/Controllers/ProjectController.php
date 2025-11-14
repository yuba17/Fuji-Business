<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = Project::with(['client', 'planComercial', 'manager']);
        
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $projects = $query->latest()->paginate(15);
        $clients = Client::where('is_active', true)->get();
        
        return view('projects.index', compact('projects', 'clients'));
    }

    public function create(Request $request): View
    {
        $clients = Client::where('is_active', true)->get();
        $plans = Plan::whereHas('planType', function($q) {
            $q->where('slug', 'plan-comercial');
        })->where('status', '!=', 'archived')->get();
        $users = User::all();
        $clientId = $request->get('client_id');
        
        return view('projects.create', compact('clients', 'plans', 'users', 'clientId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'plan_comercial_id' => 'nullable|exists:plans,id',
            'sector_economico' => 'nullable|string|max:255',
            'status' => 'nullable|in:prospecto,en_negociacion,activo,en_pausa,completado,cancelado',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'presupuesto' => 'nullable|numeric|min:0',
            'moneda' => 'nullable|string|max:3',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $validated['status'] = $validated['status'] ?? 'prospecto';
        $validated['moneda'] = $validated['moneda'] ?? 'EUR';

        $project = Project::create($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Proyecto creado correctamente');
    }

    public function show(Project $project): View
    {
        $project->load(['client', 'planComercial', 'manager', 'tasks', 'risks']);
        
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        $clients = Client::where('is_active', true)->get();
        $plans = Plan::whereHas('planType', function($q) {
            $q->where('slug', 'plan-comercial');
        })->where('status', '!=', 'archived')->get();
        $users = User::all();
        
        return view('projects.edit', compact('project', 'clients', 'plans', 'users'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'plan_comercial_id' => 'nullable|exists:plans,id',
            'sector_economico' => 'nullable|string|max:255',
            'status' => 'nullable|in:prospecto,en_negociacion,activo,en_pausa,completado,cancelado',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'presupuesto' => 'nullable|numeric|min:0',
            'moneda' => 'nullable|string|max:3',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Proyecto actualizado correctamente');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();
        
        return redirect()->route('projects.index')
            ->with('success', 'Proyecto eliminado correctamente');
    }
}
