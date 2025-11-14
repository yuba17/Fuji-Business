@extends('layouts.dashboard')

@section('title', $project->name)

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
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $project->name }}</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('header-actions')
@if(auth()->user()->isDirector() || auth()->user()->isManager())
    <x-ui.button href="{{ route('projects.edit', $project) }}" variant="secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Editar
    </x-ui.button>
@endif
@endsection

@section('content')
<div class="mb-8">
    <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
            <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words mb-2">
                {{ $project->name }}
            </h1>
            @if($project->description)
                <p class="text-gray-600">{{ $project->description }}</p>
            @endif
        </div>
        <x-ui.badge 
            variant="{{ $project->status === 'activo' ? 'success' : ($project->status === 'completado' ? 'info' : ($project->status === 'cancelado' ? 'error' : 'warning')) }}">
            {{ $project->status_label }}
        </x-ui.badge>
    </div>
    
    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
        @if($project->client)
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>{{ $project->client->name }}</span>
            </div>
        @endif
        @if($project->manager)
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Manager: {{ $project->manager->name }}</span>
            </div>
        @endif
    </div>
</div>

<!-- Información del Proyecto -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <x-ui.card>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Información General</h2>
        <dl class="space-y-3">
            @if($project->sector_economico)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Sector Económico</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $project->sector_economico }}</dd>
                </div>
            @endif
            @if($project->fecha_inicio)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Fecha de Inicio</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $project->fecha_inicio->format('d/m/Y') }}</dd>
                </div>
            @endif
            @if($project->fecha_fin)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Fecha de Fin</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $project->fecha_fin->format('d/m/Y') }}</dd>
                </div>
            @endif
            @if($project->presupuesto)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Presupuesto</dt>
                    <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ number_format($project->presupuesto, 2) }} {{ $project->moneda }}</dd>
                </div>
            @endif
        </dl>
    </x-ui.card>
    
    <x-ui.card>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Estadísticas</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-500">Tareas</p>
                <p class="text-2xl font-bold text-gray-900">{{ $project->tasks->count() }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Riesgos</p>
                <p class="text-2xl font-bold text-gray-900">{{ $project->risks->count() }}</p>
            </div>
        </div>
    </x-ui.card>
</div>
@endsection

