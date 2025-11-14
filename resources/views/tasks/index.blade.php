@extends('layouts.dashboard')

@section('title', 'Tareas')

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
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Tareas</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('header-actions')
<div class="flex gap-2">
    <a href="{{ route('tasks.kanban') }}" class="inline-block">
        <x-ui.button variant="secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
            </svg>
            Vista Kanban
        </x-ui.button>
    </a>
    @can('create', \App\Models\Task::class)
        <x-ui.button href="{{ route('tasks.create') }}" variant="primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Crear Tarea
        </x-ui.button>
    @endcan
</div>
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words">
        ✅ Tareas
    </h1>
    <p class="text-gray-600 mt-2">Gestiona tus tareas y seguimiento de trabajo</p>
</div>

<!-- Filtros -->
<div class="mb-6">
    <x-ui.card variant="compact">
        <form method="GET" action="{{ route('tasks.index') }}" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <x-ui.input 
                    type="search" 
                    placeholder="Buscar tareas..." 
                    name="search"
                    value="{{ request('search') }}" />
            </div>
            <div class="w-full sm:w-auto">
                <x-ui.select name="plan_id" label="Plan">
                    <option value="">Todos los planes</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }}
                        </option>
                    @endforeach
                </x-ui.select>
            </div>
            <div class="w-full sm:w-auto">
                <x-ui.select name="status" label="Estado">
                    <option value="">Todos los estados</option>
                    <option value="todo" {{ request('status') == 'todo' ? 'selected' : '' }}>📋 Por Hacer</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>🔄 En Progreso</option>
                    <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>🚫 Bloqueada</option>
                    <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>👀 En Revisión</option>
                    <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>✅ Completada</option>
                </x-ui.select>
            </div>
            <div class="w-full sm:w-auto">
                <x-ui.select name="priority" label="Prioridad">
                    <option value="">Todas las prioridades</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>🟢 Baja</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>🟡 Media</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>🟠 Alta</option>
                    <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>🔴 Crítica</option>
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

<!-- Lista de Tareas -->
@if($tasks->count() > 0)
    <div class="space-y-4">
        @foreach($tasks as $task)
            <x-ui.card class="hover:shadow-lg transition-shadow cursor-pointer" onclick="window.location.href='{{ route('tasks.show', $task) }}'">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-lg font-bold text-gray-900">{{ $task->title }}</h3>
                            <x-ui.badge 
                                variant="{{ $task->priority === 'critical' ? 'error' : ($task->priority === 'high' ? 'warning' : ($task->priority === 'medium' ? 'info' : 'success')) }}">
                                {{ $task->priority_label }}
                            </x-ui.badge>
                            <x-ui.badge 
                                variant="{{ $task->status === 'done' ? 'success' : ($task->status === 'in_progress' ? 'info' : ($task->status === 'blocked' ? 'error' : 'warning')) }}">
                                {{ $task->status_label }}
                            </x-ui.badge>
                        </div>
                        
                        @if($task->description)
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $task->description }}</p>
                        @endif
                        
                        <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500">
                            @if($task->plan)
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ $task->plan->name }}
                                </div>
                            @endif
                            @if($task->assignedUser)
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ $task->assignedUser->name }}
                                </div>
                            @endif
                            @if($task->due_date)
                                <div class="flex items-center gap-1 {{ $task->isOverdue() ? 'text-red-600 font-semibold' : '' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Vence: {{ $task->due_date->format('d/m/Y') }}
                                </div>
                            @endif
                            @if($task->estimated_hours)
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $task->estimated_hours }}h estimadas
                                </div>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('tasks.show', $task) }}" 
                       class="ml-4 text-sm font-medium text-red-600 hover:text-red-700">
                        Ver →
                    </a>
                </div>
            </x-ui.card>
        @endforeach
    </div>
    
    <!-- Paginación -->
    <div class="mt-6">
        {{ $tasks->links() }}
    </div>
@else
    <x-ui.card>
        <div class="text-center py-12 text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="text-sm mb-4">No hay tareas creadas aún</p>
            @if(auth()->user()->isDirector() || auth()->user()->isManager() || auth()->user()->isTecnico())
                <x-ui.button href="{{ route('tasks.create') }}" variant="primary">
                    Crear Primera Tarea
                </x-ui.button>
            @endif
        </div>
    </x-ui.card>
@endif
@endsection

