@extends('layouts.dashboard')

@section('title', $milestone->name)

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
                <a href="{{ route('plans.show', $plan) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 md:ml-2">{{ $plan->name }}</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $milestone->name }}</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('header-actions')
<div class="flex gap-2">
    <a href="{{ route('plans.roadmap', $plan) }}" class="inline-block">
        <x-ui.button variant="secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver al Roadmap
        </x-ui.button>
    </a>
    @can('update', $plan)
        <x-ui.button href="{{ route('plans.milestones.edit', [$plan, $milestone]) }}" variant="secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Editar
        </x-ui.button>
    @endcan
</div>
@endsection

@section('content')
<div class="mb-8">
    <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
            <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words mb-2">
                {{ $milestone->name }}
            </h1>
            <p class="text-gray-600">{{ $milestone->description }}</p>
        </div>
        <x-ui.badge 
            variant="{{ $milestone->status === 'completed' ? 'success' : ($milestone->status === 'delayed' ? 'error' : ($milestone->status === 'in_progress' ? 'warning' : 'info')) }}"
        >
            {{ $milestone->status_label }}
        </x-ui.badge>
    </div>
</div>

<!-- Información Principal -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <x-ui.card variant="gradient" border-color="blue">
        <div class="text-center">
            <p class="text-blue-100 text-xs font-medium uppercase tracking-wide">Progreso</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ $milestone->progress_percentage }}%</p>
        </div>
    </x-ui.card>
    
    <x-ui.card variant="gradient" border-color="green">
        <div class="text-center">
            <p class="text-green-100 text-xs font-medium uppercase tracking-wide">Fecha Objetivo</p>
            <p class="text-2xl font-bold mt-1 text-white">{{ $milestone->target_date->format('d/m/Y') }}</p>
        </div>
    </x-ui.card>
    
    <x-ui.card variant="gradient" border-color="orange">
        <div class="text-center">
            <p class="text-orange-100 text-xs font-medium uppercase tracking-wide">Tareas</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ $milestone->tasks->count() }}</p>
        </div>
    </x-ui.card>
</div>

<!-- Detalles -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <x-ui.card>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Información General</h2>
        <dl class="space-y-3">
            <div>
                <dt class="text-sm font-medium text-gray-500">Fecha de Inicio</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $milestone->start_date ? $milestone->start_date->format('d/m/Y') : 'No definida' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Fecha Objetivo</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $milestone->target_date->format('d/m/Y') }}</dd>
            </div>
            @if($milestone->end_date)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Fecha de Fin</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $milestone->end_date->format('d/m/Y') }}</dd>
                </div>
            @endif
            @if($milestone->responsible)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Responsable</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $milestone->responsible->name }}</dd>
                </div>
            @endif
            @if($milestone->isDelayed())
                <div>
                    <dt class="text-sm font-medium text-red-600">Días de Retraso</dt>
                    <dd class="mt-1 text-sm font-bold text-red-600">{{ now()->diffInDays($milestone->target_date) }} días</dd>
                </div>
            @endif
        </dl>
    </x-ui.card>
    
    <x-ui.card>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Tareas Asociadas</h2>
        @if($milestone->tasks->count() > 0)
            <div class="space-y-2">
                @foreach($milestone->tasks as $task)
                    <a 
                        href="{{ route('tasks.show', $task) }}"
                        class="block p-3 border border-gray-200 rounded-lg hover:border-red-300 hover:bg-gray-50 transition-all"
                    >
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-900">{{ $task->title }}</span>
                            <x-ui.badge 
                                variant="{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'warning' : 'info') }}"
                            >
                                {{ $task->status_label }}
                            </x-ui.badge>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 text-center py-8">No hay tareas asociadas a este hito</p>
        @endif
    </x-ui.card>
</div>
@endsection


