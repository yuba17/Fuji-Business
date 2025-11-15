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
<div x-data="tecnicoDashboard()" x-cloak>
<!-- Header Técnico - Diseño Limpio y Moderno -->
<div class="mb-8">
    <div class="bg-gradient-to-r from-green-500 via-emerald-500 to-green-600 rounded-2xl shadow-lg p-8 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-white/10 via-transparent to-transparent"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/30">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold mb-2">Dashboard Técnico</h1>
                        <p class="text-green-50 text-sm">Gestiona tus tareas asignadas y seguimiento de trabajo</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas de Tareas - Diseño Limpio con Fondos Blancos -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Por Hacer -->
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-gray-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-gray-600 text-sm font-medium mb-2">Por Hacer</p>
        <p class="text-4xl font-bold text-gray-900 mb-3">{{ $pending_tasks }}</p>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-lg">Pendientes</span>
        </div>
    </div>

    <!-- En Progreso -->
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
        </div>
        <p class="text-gray-600 text-sm font-medium mb-2">En Progreso</p>
        <p class="text-4xl font-bold text-gray-900 mb-3">{{ $my_tasks_list->where('status', 'in_progress')->count() }}</p>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-lg">Activas</span>
        </div>
    </div>

    <!-- Vencidas -->
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-gray-600 text-sm font-medium mb-2">Vencidas</p>
        <p class="text-4xl font-bold text-gray-900 mb-3">{{ $overdue_tasks }}</p>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-lg">Urgente</span>
        </div>
    </div>

    <!-- Completadas -->
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-gray-600 text-sm font-medium mb-2">Completadas</p>
        <p class="text-4xl font-bold text-gray-900 mb-3">{{ $completed_tasks }}</p>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-lg">Finalizadas</span>
        </div>
    </div>
</div>

<!-- Mis Tareas - Diseño Limpio -->
<div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex items-center gap-4 mb-6">
        <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-900">Mis Tareas</h2>
            <p class="text-sm text-gray-600">Visualiza y gestiona tus tareas asignadas</p>
        </div>
    </div>
    
    @if($my_tasks_list->count() > 0)
        <div class="space-y-3 mb-6">
            @foreach($my_tasks_list->take(5) as $task)
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-green-300 hover:bg-gray-100 transition-all group">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-semibold text-gray-900 group-hover:text-green-600 transition-colors">{{ $task->title }}</h3>
                                <x-ui.badge variant="{{ $task->status === 'done' ? 'success' : ($task->status === 'in_progress' ? 'info' : ($task->isOverdue() ? 'error' : 'warning')) }}">
                                    {{ $task->status_label }}
                                </x-ui.badge>
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="font-medium">{{ $task->plan->name ?? 'Sin plan' }}</span>
                                </div>
                                @if($task->due_date)
                                    <div class="flex items-center gap-1.5 {{ $task->isOverdue() ? 'bg-red-50 px-2 py-1 rounded-md border border-red-200' : '' }}">
                                        <svg class="w-4 h-4 {{ $task->isOverdue() ? 'text-red-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="{{ $task->isOverdue() ? 'text-red-700 font-bold' : 'font-medium' }}">
                                            Vence: {{ $task->due_date->format('d/m/Y') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('tasks.show', $task) }}" class="ml-4 px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-lg border border-gray-300 transition-all flex items-center gap-2">
                            Ver
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 mb-6">
            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="text-gray-600 text-sm mb-4 font-medium">No tienes tareas asignadas</p>
        </div>
    @endif
    
    <a href="{{ route('tasks.index') }}" class="w-full px-6 py-3 text-sm font-semibold bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all shadow-sm text-center block">
        Ver Todas Mis Tareas
    </a>
</div>
</div>

<script>
function tecnicoDashboard() {
    return {
        init() {
            // Inicialización si es necesaria
        }
    }
}
</script>
@endsection
