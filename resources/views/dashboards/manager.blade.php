@extends('layouts.dashboard')

@section('title', 'Dashboard - Manager')

@section('breadcrumbs')
<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-red-600">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                <span class="truncate">Dashboard</span>
            </a>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div x-data="managerDashboard()" x-cloak>
<!-- Header Manager - Diseño Limpio y Moderno -->
<div class="mb-8">
    <div class="bg-gradient-to-r from-blue-500 via-indigo-500 to-blue-600 rounded-2xl shadow-lg p-8 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-white/10 via-transparent to-transparent"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/30">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold mb-2">Dashboard Manager</h1>
                        <p class="text-blue-50 text-sm">Gestiona los planes y actividades de tu área de responsabilidad</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas del Área - Diseño Limpio con Fondos Blancos -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Mis Planes -->
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-gray-600 text-sm font-medium mb-2">Mis Planes</p>
        <p class="text-4xl font-bold text-gray-900 mb-3">{{ $my_plans }}</p>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-lg">{{ $active_plans }} activos</span>
        </div>
    </div>

    <!-- Tareas Activas -->
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
        <p class="text-gray-600 text-sm font-medium mb-2">Tareas Activas</p>
        <p class="text-4xl font-bold text-gray-900 mb-3">{{ $my_tasks }}</p>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-lg">Pendientes</span>
        </div>
    </div>

    <!-- Riesgos -->
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
        <p class="text-gray-600 text-sm font-medium mb-2">Riesgos</p>
        <p class="text-4xl font-bold text-gray-900 mb-3">{{ $my_risks }}</p>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-orange-100 text-orange-800 text-xs font-semibold rounded-lg">Identificados</span>
        </div>
    </div>
</div>

<!-- Acciones Rápidas - Diseño Limpio -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Mis Planes -->
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Mis Planes</h2>
                <p class="text-sm text-gray-600">Gestión de planes del área</p>
            </div>
        </div>
        <p class="text-gray-700 text-sm mb-6">Gestiona los planes estratégicos y operativos de tu área de responsabilidad.</p>
        <div class="flex gap-3">
            <a href="{{ route('plans.index') }}" class="flex-1 px-4 py-2.5 text-sm font-semibold bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all text-center shadow-sm">
                Ver Planes
            </a>
            <a href="{{ route('plans.create') }}" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all text-center">
                Crear Plan
            </a>
        </div>
    </div>

    <!-- Tareas del Equipo -->
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Tareas del Equipo</h2>
                <p class="text-sm text-gray-600">Gestión de tareas</p>
            </div>
        </div>
        <p class="text-gray-700 text-sm mb-6">Gestiona y supervisa las tareas asignadas a tu equipo y área.</p>
        <div class="flex gap-3">
            <a href="{{ route('tasks.index') }}" class="flex-1 px-4 py-2.5 text-sm font-semibold bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all text-center shadow-sm">
                Ver Tareas
            </a>
            <a href="{{ route('tasks.create') }}" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all text-center">
                Crear Tarea
            </a>
        </div>
    </div>
</div>

<!-- Planes Recientes - Diseño Limpio -->
@if(isset($recent_plans) && $recent_plans->count() > 0)
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900">Mis Planes Recientes</h2>
            </div>
            <a href="{{ route('plans.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all">
                Ver Todos
            </a>
        </div>
        <div class="space-y-3">
            @foreach($recent_plans as $plan)
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-blue-300 hover:bg-gray-100 transition-all group">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors mb-2">{{ $plan->name }}</h3>
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <span class="font-medium">{{ $plan->planType->name ?? 'Sin tipo' }}</span>
                                </div>
                                @if($plan->area)
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <span class="font-medium">{{ $plan->area->name }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('plans.show', $plan) }}" class="ml-4 px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-lg border border-gray-300 transition-all flex items-center gap-2">
                            Ver
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Tareas Recientes - Diseño Limpio -->
@if(isset($recent_tasks) && $recent_tasks->count() > 0)
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900">Tareas Recientes</h2>
            </div>
            <a href="{{ route('tasks.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all">
                Ver Todas
            </a>
        </div>
        <div class="space-y-3">
            @foreach($recent_tasks as $task)
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-green-300 hover:bg-gray-100 transition-all group">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-semibold text-gray-900 group-hover:text-green-600 transition-colors">{{ $task->title }}</h3>
                                <x-ui.badge variant="{{ $task->status === 'done' ? 'success' : ($task->status === 'in_progress' ? 'info' : 'warning') }}">
                                    {{ $task->status_label }}
                                </x-ui.badge>
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                @if($task->plan)
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span class="font-medium">{{ $task->plan->name }}</span>
                                    </div>
                                @endif
                                @if($task->assignedUser)
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span class="font-medium">{{ $task->assignedUser->name }}</span>
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
    </div>
@endif
</div>

<script>
function managerDashboard() {
    return {
        init() {
            // Inicialización si es necesaria
        }
    }
}
</script>
@endsection
