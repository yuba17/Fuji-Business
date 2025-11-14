@extends('layouts.dashboard')

@section('title', 'Dashboard - Técnico')

@section('breadcrumbs')
<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-red-600">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                Dashboard
            </a>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent">
        🔧 Dashboard Técnico
    </h1>
    <p class="text-gray-600 mt-2">Gestiona tus tareas asignadas</p>
</div>

<!-- Mis Tareas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <x-ui.card variant="gradient" border-color="gray">
        <div class="text-center">
            <p class="text-gray-100 text-xs font-medium uppercase tracking-wide">Por Hacer</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ $pending_tasks }}</p>
        </div>
    </x-ui.card>

    <x-ui.card variant="gradient" border-color="blue">
        <div class="text-center">
            <p class="text-blue-100 text-xs font-medium uppercase tracking-wide">En Progreso</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ $my_tasks_list->where('status', 'in_progress')->count() }}</p>
        </div>
    </x-ui.card>

    <x-ui.card variant="gradient" border-color="red">
        <div class="text-center">
            <p class="text-red-100 text-xs font-medium uppercase tracking-wide">Vencidas</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ $overdue_tasks }}</p>
        </div>
    </x-ui.card>

    <x-ui.card variant="gradient" border-color="green">
        <div class="text-center">
            <p class="text-green-100 text-xs font-medium uppercase tracking-wide">Completadas</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ $completed_tasks }}</p>
        </div>
    </x-ui.card>
</div>

<!-- Acciones Rápidas -->
<x-ui.card>
    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
            ✅
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Mis Tareas</h2>
    </div>
    <p class="text-sm text-gray-600 mb-4">Visualiza y gestiona las tareas asignadas a ti.</p>
    
    @if($my_tasks_list->count() > 0)
        <div class="space-y-3 mb-4">
            @foreach($my_tasks_list->take(5) as $task)
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">{{ $task->title }}</h3>
                        <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                            <span>{{ $task->plan->name ?? 'Sin plan' }}</span>
                            @if($task->due_date)
                                <span class="{{ $task->isOverdue() ? 'text-red-600 font-semibold' : '' }}">
                                    Vence: {{ $task->due_date->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-ui.badge variant="{{ $task->status === 'done' ? 'success' : ($task->status === 'in_progress' ? 'info' : ($task->isOverdue() ? 'error' : 'warning')) }}">
                            {{ $task->status_label }}
                        </x-ui.badge>
                        <a href="{{ route('tasks.show', $task) }}" class="text-sm text-red-600 hover:text-red-700 font-medium">
                            Ver →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-500 text-center py-8 mb-4">No tienes tareas asignadas</p>
    @endif
    
    <x-ui.button href="{{ route('tasks.index') }}" variant="primary">
        Ver Todas Mis Tareas
    </x-ui.button>
</x-ui.card>
@endsection

