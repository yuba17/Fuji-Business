@extends('layouts.dashboard')

@section('title', 'Proyectos')

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
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Proyectos</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('header-actions')
@if(auth()->user()->isDirector() || auth()->user()->isManager())
    <x-ui.button href="{{ route('projects.create') }}" variant="primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Crear Proyecto
    </x-ui.button>
@endif
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words">
        📦 Proyectos
    </h1>
    <p class="text-gray-600 mt-2">Gestión de proyectos comerciales</p>
</div>

<!-- Filtros -->
<div class="mb-6">
    <x-ui.card variant="compact">
        <form method="GET" action="{{ route('projects.index') }}" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <x-ui.input 
                    type="search" 
                    placeholder="Buscar proyectos..." 
                    name="search"
                    value="{{ request('search') }}" />
            </div>
            <div class="w-full sm:w-auto">
                <x-ui.select name="client_id" label="Cliente">
                    <option value="">Todos los clientes</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                    @endforeach
                </x-ui.select>
            </div>
            <div class="w-full sm:w-auto">
                <x-ui.select name="status" label="Estado">
                    <option value="">Todos los estados</option>
                    <option value="prospecto" {{ request('status') == 'prospecto' ? 'selected' : '' }}>Prospecto</option>
                    <option value="en_negociacion" {{ request('status') == 'en_negociacion' ? 'selected' : '' }}>En Negociación</option>
                    <option value="activo" {{ request('status') == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="en_pausa" {{ request('status') == 'en_pausa' ? 'selected' : '' }}>En Pausa</option>
                    <option value="completado" {{ request('status') == 'completado' ? 'selected' : '' }}>Completado</option>
                    <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </x-ui.select>
            </div>
            <div class="w-full sm:w-auto">
                <x-ui.button variant="secondary" type="submit">
                    Filtrar
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>
</div>

<!-- Lista de Proyectos -->
@if($projects->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($projects as $project)
            <x-ui.card class="hover:shadow-xl transition-shadow cursor-pointer" onclick="window.location.href='{{ route('projects.show', $project) }}'">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $project->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $project->client->name ?? 'Sin cliente' }}</p>
                    </div>
                    <x-ui.badge 
                        variant="{{ $project->status === 'activo' ? 'success' : ($project->status === 'completado' ? 'info' : ($project->status === 'cancelado' ? 'error' : 'warning')) }}">
                        {{ $project->status_label }}
                    </x-ui.badge>
                </div>
                
                @if($project->description)
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $project->description }}</p>
                @endif
                
                <div class="flex items-center justify-between text-xs text-gray-500 pt-4 border-t border-gray-200">
                    <div class="flex items-center gap-4">
                        @if($project->presupuesto)
                            <span class="font-semibold">{{ number_format($project->presupuesto, 2) }} {{ $project->moneda }}</span>
                        @endif
                        @if($project->manager)
                            <span>{{ $project->manager->name }}</span>
                        @endif
                    </div>
                    <a href="{{ route('projects.show', $project) }}" 
                       class="text-xs font-medium text-red-600 hover:text-red-700">
                        Ver →
                    </a>
                </div>
            </x-ui.card>
        @endforeach
    </div>
    
    <!-- Paginación -->
    <div class="mt-6">
        {{ $projects->links() }}
    </div>
@else
    <x-ui.card>
        <div class="text-center py-12 text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <p class="text-sm mb-4">No hay proyectos creados aún</p>
            @if(auth()->user()->isDirector() || auth()->user()->isManager())
                <x-ui.button href="{{ route('projects.create') }}" variant="primary">
                    Crear Primer Proyecto
                </x-ui.button>
            @endif
        </div>
    </x-ui.card>
@endif
@endsection

