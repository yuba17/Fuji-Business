@extends('layouts.dashboard')

@section('title', 'Dashboard - Manager')

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
        📋 Dashboard Manager
    </h1>
    <p class="text-gray-600 mt-2">Gestiona los planes y actividades de tu área</p>
</div>

<!-- Estadísticas del Área -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <x-ui.card variant="gradient" border-color="blue">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-xs font-medium uppercase tracking-wide">Mis Planes</p>
                <p class="text-3xl font-bold mt-1 text-white">{{ $my_plans }}</p>
                <p class="text-blue-100 text-xs mt-0.5">{{ $active_plans }} activos</p>
            </div>
            <svg class="w-12 h-12 opacity-80" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
            </svg>
        </div>
    </x-ui.card>

    <x-ui.card variant="gradient" border-color="green">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-xs font-medium uppercase tracking-wide">Tareas Activas</p>
                <p class="text-3xl font-bold mt-1 text-white">{{ $my_tasks }}</p>
                <p class="text-green-100 text-xs mt-0.5">Pendientes</p>
            </div>
            <svg class="w-12 h-12 opacity-80" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
            </svg>
        </div>
    </x-ui.card>

    <x-ui.card variant="gradient" border-color="orange">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-xs font-medium uppercase tracking-wide">Riesgos</p>
                <p class="text-3xl font-bold mt-1 text-white">{{ $my_risks }}</p>
                <p class="text-orange-100 text-xs mt-0.5">Identificados</p>
            </div>
            <svg class="w-12 h-12 opacity-80" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
        </div>
    </x-ui.card>
</div>

<!-- Acciones Rápidas -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <x-ui.card>
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-orange-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                📊
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Mis Planes</h2>
        </div>
        <p class="text-sm text-gray-600 mb-4">Gestiona los planes de tu área.</p>
        <div class="flex gap-3">
            <x-ui.button href="{{ route('plans.index') }}" variant="primary">
                Ver Planes
            </x-ui.button>
            <x-ui.button href="{{ route('plans.create') }}" variant="secondary">
                Crear Plan
            </x-ui.button>
        </div>
    </x-ui.card>

    <x-ui.card>
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                ✅
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Tareas</h2>
        </div>
        <p class="text-sm text-gray-600 mb-4">Gestiona las tareas de tu equipo.</p>
        <div class="flex gap-3">
            <x-ui.button href="{{ route('tasks.index') }}" variant="primary">
                Ver Tareas
            </x-ui.button>
            <x-ui.button href="{{ route('tasks.create') }}" variant="secondary">
                Crear Tarea
            </x-ui.button>
        </div>
    </x-ui.card>
</div>

<!-- Planes Recientes -->
@if(isset($recent_plans) && $recent_plans->count() > 0)
    <x-ui.card class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Mis Planes Recientes</h2>
            <x-ui.button href="{{ route('plans.index') }}" variant="secondary">
                Ver Todos
            </x-ui.button>
        </div>
        <div class="space-y-3">
            @foreach($recent_plans as $plan)
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">{{ $plan->name }}</h3>
                        <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                            <span>{{ $plan->planType->name ?? 'Sin tipo' }}</span>
                            @if($plan->area)
                                <span>• {{ $plan->area->name }}</span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('plans.show', $plan) }}" class="text-sm text-red-600 hover:text-red-700 font-medium">
                        Ver →
                    </a>
                </div>
            @endforeach
        </div>
    </x-ui.card>
@endif

<!-- Tareas Recientes -->
@if(isset($recent_tasks) && $recent_tasks->count() > 0)
    <x-ui.card>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Tareas Recientes</h2>
            <x-ui.button href="{{ route('tasks.index') }}" variant="secondary">
                Ver Todas
            </x-ui.button>
        </div>
        <div class="space-y-3">
            @foreach($recent_tasks as $task)
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">{{ $task->title }}</h3>
                        <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                            <span>{{ $task->plan->name ?? 'Sin plan' }}</span>
                            @if($task->assignedUser)
                                <span>• {{ $task->assignedUser->name }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-ui.badge variant="{{ $task->status === 'done' ? 'success' : ($task->status === 'in_progress' ? 'info' : 'warning') }}">
                            {{ $task->status_label }}
                        </x-ui.badge>
                        <a href="{{ route('tasks.show', $task) }}" class="text-sm text-red-600 hover:text-red-700 font-medium">
                            Ver →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </x-ui.card>
@endif
@endsection

