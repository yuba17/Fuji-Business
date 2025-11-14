<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $query = Client::query();
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('sector_economico')) {
            $query->where('sector_economico', $request->sector_economico);
        }
        
        $clients = $query->latest()->paginate(15);
        
        return view('clients.index', compact('clients'));
    }

    public function create(): View
    {
        return view('clients.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sector_economico' => 'nullable|string|max:255',
            'tamaño_empresa' => 'nullable|string|max:255',
            'ubicacion' => 'nullable|string|max:255',
            'contacto_principal' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:255',
            'sitio_web' => 'nullable|url|max:255',
            'notas' => 'nullable|string',
        ]);

        $client = Client::create($validated);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Cliente creado correctamente');
    }

    public function show(Client $client): View
    {
        $client->load(['projects', 'plans']);
        
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client): View
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sector_economico' => 'nullable|string|max:255',
            'tamaño_empresa' => 'nullable|string|max:255',
            'ubicacion' => 'nullable|string|max:255',
            'contacto_principal' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:255',
            'sitio_web' => 'nullable|url|max:255',
            'notas' => 'nullable|string',
        ]);

        $client->update($validated);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Cliente actualizado correctamente');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();
        
        return redirect()->route('clients.index')
            ->with('success', 'Cliente eliminado correctamente');
    }
}
