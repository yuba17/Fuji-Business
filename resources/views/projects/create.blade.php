@extends('layouts.dashboard')

@section('title', 'Crear Proyecto')

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
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Crear</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words">
        ➕ Crear Nuevo Proyecto
    </h1>
    <p class="text-gray-600 mt-2">Crea un nuevo proyecto comercial</p>
</div>

<x-ui.card>
    <form action="{{ route('projects.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <x-ui.input 
                label="Nombre del Proyecto" 
                name="name" 
                value="{{ old('name') }}"
                required 
                placeholder="Ej: Proyecto de Seguridad XYZ" />
        </div>
        
        <div>
            <x-ui.textarea 
                label="Descripción" 
                name="description" 
                rows="4"
                placeholder="Describe el proyecto..."
                >{{ old('description') }}</x-ui.textarea>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.select 
                    label="Cliente" 
                    name="client_id" 
                    required>
                    <option value="">-- Selecciona un cliente --</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ (old('client_id', $clientId) == $client->id) ? 'selected' : '' }}>
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
                        <option value="{{ $plan->id }}" {{ old('plan_comercial_id') == $plan->id ? 'selected' : '' }}>
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
                    value="{{ old('sector_economico') }}"
                    placeholder="Ej: Tecnología" />
            </div>
            
            <div>
                <x-ui.select 
                    label="Estado" 
                    name="status">
                    <option value="prospecto" {{ old('status', 'prospecto') == 'prospecto' ? 'selected' : '' }}>Prospecto</option>
                    <option value="en_negociacion" {{ old('status') == 'en_negociacion' ? 'selected' : '' }}>En Negociación</option>
                    <option value="activo" {{ old('status') == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="en_pausa" {{ old('status') == 'en_pausa' ? 'selected' : '' }}>En Pausa</option>
                </x-ui.select>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.input 
                    label="Fecha de Inicio" 
                    name="fecha_inicio" 
                    type="date"
                    value="{{ old('fecha_inicio') }}" />
            </div>
            
            <div>
                <x-ui.input 
                    label="Fecha de Fin" 
                    name="fecha_fin" 
                    type="date"
                    value="{{ old('fecha_fin') }}" />
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
                    value="{{ old('presupuesto') }}"
                    placeholder="0.00" />
            </div>
            
            <div>
                <x-ui.input 
                    label="Moneda" 
                    name="moneda" 
                    value="{{ old('moneda', 'EUR') }}"
                    placeholder="EUR" />
            </div>
            
            <div>
                <x-ui.select 
                    label="Manager" 
                    name="manager_id">
                    <option value="">-- Sin asignar --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('manager_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </x-ui.select>
            </div>
        </div>
        
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
            <x-ui.button href="{{ route('projects.index') }}" variant="secondary" type="button">
                Cancelar
            </x-ui.button>
            <x-ui.button variant="primary" type="submit">
                Crear Proyecto
            </x-ui.button>
        </div>
    </form>
</x-ui.card>
@endsection

