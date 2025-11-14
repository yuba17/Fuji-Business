@extends('layouts.dashboard')

@section('title', 'Editar Proyecto: ' . $project->name)

@section('breadcrumbs')
<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-red-600">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('projects.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 md:ml-2">Proyectos</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('projects.show', $project) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 md:ml-2">{{ $project->name }}</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Editar</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words">
        ✏️ Editar Proyecto
    </h1>
    <p class="text-gray-600 mt-2">Modifica la información del proyecto</p>
</div>

<x-ui.card>
    <form action="{{ route('projects.update', $project) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <x-ui.input 
                label="Nombre del Proyecto" 
                name="name" 
                value="{{ old('name', $project->name) }}"
                required 
                placeholder="Ej: Proyecto de Seguridad XYZ" />
        </div>
        
        <div>
            <x-ui.textarea 
                label="Descripción" 
                name="description" 
                rows="4"
                placeholder="Describe el proyecto..."
                >{{ old('description', $project->description) }}</x-ui.textarea>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.select 
                    label="Cliente" 
                    name="client_id" 
                    required>
                    <option value="">-- Selecciona un cliente --</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id', $project->client_id) == $client->id ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                    @endforeach
                </x-ui.select>
            </div>
            
            <div>
                <x-ui.select 
                    label="Plan Comercial" 
                    name="plan_comercial_id">
                    <option value="">-- Sin plan comercial --</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_comercial_id', $project->plan_comercial_id) == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }}
                        </option>
                    @endforeach
                </x-ui.select>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.input 
                    label="Sector Económico" 
                    name="sector_economico" 
                    value="{{ old('sector_economico', $project->sector_economico) }}"
                    placeholder="Ej: Tecnología" />
            </div>
            
            <div>
                <x-ui.select 
                    label="Estado" 
                    name="status">
                    <option value="prospecto" {{ old('status', $project->status) == 'prospecto' ? 'selected' : '' }}>Prospecto</option>
                    <option value="en_negociacion" {{ old('status', $project->status) == 'en_negociacion' ? 'selected' : '' }}>En Negociación</option>
                    <option value="activo" {{ old('status', $project->status) == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="en_pausa" {{ old('status', $project->status) == 'en_pausa' ? 'selected' : '' }}>En Pausa</option>
                    <option value="completado" {{ old('status', $project->status) == 'completado' ? 'selected' : '' }}>Completado</option>
                    <option value="cancelado" {{ old('status', $project->status) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </x-ui.select>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.input 
                    label="Fecha de Inicio" 
                    name="fecha_inicio" 
                    type="date"
                    value="{{ old('fecha_inicio', $project->fecha_inicio?->format('Y-m-d')) }}" />
            </div>
            
            <div>
                <x-ui.input 
                    label="Fecha de Fin" 
                    name="fecha_fin" 
                    type="date"
                    value="{{ old('fecha_fin', $project->fecha_fin?->format('Y-m-d')) }}" />
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <x-ui.input 
                    label="Presupuesto" 
                    name="presupuesto" 
                    type="number"
                    step="0.01"
                    min="0"
                    value="{{ old('presupuesto', $project->presupuesto) }}"
                    placeholder="0.00" />
            </div>
            
            <div>
                <x-ui.input 
                    label="Moneda" 
                    name="moneda" 
                    value="{{ old('moneda', $project->moneda) }}"
                    placeholder="EUR" />
            </div>
            
            <div>
                <x-ui.select 
                    label="Manager" 
                    name="manager_id">
                    <option value="">-- Sin asignar --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('manager_id', $project->manager_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </x-ui.select>
            </div>
        </div>
        
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
            <x-ui.button href="{{ route('projects.show', $project) }}" variant="secondary" type="button">
                Cancelar
            </x-ui.button>
            <x-ui.button variant="primary" type="submit">
                Guardar Cambios
            </x-ui.button>
        </div>
    </form>
</x-ui.card>
@endsection

