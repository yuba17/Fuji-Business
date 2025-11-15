@extends('layouts.dashboard')

@section('title', $plan->name)

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
                <a href="{{ route('plans.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 md:ml-2">Planes</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $plan->name }}</span>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Roadmap
        </x-ui.button>
    </a>
    <a href="{{ route('plans.versions', $plan) }}" class="inline-block">
        <x-ui.button variant="secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Versiones (v{{ $plan->version }})
        </x-ui.button>
    </a>
    @can('update', $plan)
        <x-ui.button href="{{ route('plans.edit', $plan) }}" variant="secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Editar
        </x-ui.button>
    @endcan
</div>
@endsection

@section('content')
<!-- Header del Plan - Diseño Limpio -->
<div class="mb-8">
    <div class="bg-gradient-to-r from-red-500 via-orange-500 to-red-600 rounded-2xl shadow-lg p-8 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-white/10 via-transparent to-transparent"></div>
        <div class="relative z-10">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h1 class="text-4xl font-bold mb-3">{{ $plan->name }}</h1>
                    <p class="text-red-50 text-sm">{{ $plan->description }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-ui.badge 
                        variant="{{ $plan->status === 'approved' ? 'success' : ($plan->status === 'in_progress' ? 'info' : 'warning') }}">
                        {{ $plan->status_label }}
                    </x-ui.badge>
                    @livewire('plans.plan-status-changer', ['plan' => $plan], key('status-changer-' . $plan->id))
                </div>
            </div>
            
            <div class="flex flex-wrap gap-4 text-sm text-red-50">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span>{{ $plan->planType->name ?? 'Sin tipo' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>{{ $plan->area->name ?? 'Sin área' }}</span>
                </div>
                @if($plan->manager)
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Manager: {{ $plan->manager->name }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas Rápidas -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
        </div>
        <p class="text-gray-600 text-sm font-medium mb-2">KPIs</p>
        <p class="text-4xl font-bold text-gray-900 mb-3">{{ $plan->kpis->count() }}</p>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-lg">Indicadores clave</span>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-gray-600 text-sm font-medium mb-2">Hitos</p>
        <p class="text-4xl font-bold text-gray-900 mb-3">{{ $plan->milestones->count() }}</p>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-lg">Marcos temporales</span>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
        <p class="text-gray-600 text-sm font-medium mb-2">Tareas</p>
        <p class="text-4xl font-bold text-gray-900 mb-3">{{ $plan->tasks->count() }}</p>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-orange-100 text-orange-800 text-xs font-semibold rounded-lg">Acciones pendientes</span>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
        <p class="text-gray-600 text-sm font-medium mb-2">Riesgos</p>
        <p class="text-4xl font-bold text-gray-900 mb-3">{{ $plan->risks->count() }}</p>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-lg">Identificados</span>
        </div>
    </div>
</div>

<!-- Tabs de Navegación -->
<div class="mb-6" x-data="{ activeTab: 'overview' }">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-2 border border-gray-200">
        <nav class="flex flex-wrap gap-2">
            <button @click="activeTab = 'overview'" 
                    :class="activeTab === 'overview' ? 'bg-gradient-to-r from-red-600 to-orange-600 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Resumen
            </button>
            <button @click="activeTab = 'sections'" 
                    :class="activeTab === 'sections' ? 'bg-gradient-to-r from-purple-600 to-blue-600 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                Secciones
            </button>
            <button @click="activeTab = 'kpis'" 
                    :class="activeTab === 'kpis' ? 'bg-gradient-to-r from-blue-600 to-cyan-600 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                KPIs
            </button>
            <button @click="activeTab = 'milestones'" 
                    :class="activeTab === 'milestones' ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Hitos
            </button>
            <button @click="activeTab = 'tasks'" 
                    :class="activeTab === 'tasks' ? 'bg-gradient-to-r from-orange-600 to-amber-600 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Tareas
            </button>
            <button @click="activeTab = 'risks'" 
                    :class="activeTab === 'risks' ? 'bg-gradient-to-r from-red-600 to-rose-600 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Riesgos
            </button>
        </nav>
    </div>
    
    <!-- Tab Content: Overview -->
    <div x-show="activeTab === 'overview'" class="mt-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-500">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-orange-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                    ℹ️
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Información General</h2>
            </div>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 border border-gray-200">
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Fecha de Inicio</dt>
                    <dd class="text-base font-semibold text-gray-900">{{ $plan->start_date ? $plan->start_date->format('d/m/Y') : 'No definida' }}</dd>
                </div>
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 border border-gray-200">
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Fecha Objetivo</dt>
                    <dd class="text-base font-semibold text-gray-900">{{ $plan->target_date ? $plan->target_date->format('d/m/Y') : 'No definida' }}</dd>
                </div>
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 border border-gray-200">
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Versión</dt>
                    <dd class="text-base font-semibold text-gray-900">{{ $plan->version ?? '1.0' }}</dd>
                </div>
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 border border-gray-200">
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Director</dt>
                    <dd class="text-base font-semibold text-gray-900">{{ $plan->director->name ?? 'No asignado' }}</dd>
                </div>
            </dl>
        </div>
    </div>
    
    <!-- Tab Content: Sections -->
    <div x-show="activeTab === 'sections'" class="mt-6" style="display: none;">
        @livewire('plans.plan-section-editor', ['plan' => $plan], key('section-editor-' . $plan->id))
    </div>
    
    <!-- Tab Content: KPIs -->
    <div x-show="activeTab === 'kpis'" class="mt-6" style="display: none;">
        <x-ui.card>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">KPIs del Plan</h2>
                <x-ui.button href="{{ route('kpis.create', ['plan_id' => $plan->id]) }}" variant="primary">
                    Crear KPI
                </x-ui.button>
            </div>
            @if($plan->kpis->count() > 0)
                <div class="space-y-4">
                    @foreach($plan->kpis as $kpi)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $kpi->name }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $kpi->description }}</p>
                                    <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                        <span>Objetivo: {{ $kpi->target_value }} {{ $kpi->unit }}</span>
                                        <span>Actual: {{ $kpi->current_value }} {{ $kpi->unit }}</span>
                                    </div>
                                </div>
                                <x-ui.badge variant="{{ $kpi->status === 'green' ? 'success' : ($kpi->status === 'yellow' ? 'warning' : 'error') }}">
                                    {{ $kpi->status }}
                                </x-ui.badge>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 text-center py-8">No hay KPIs definidos aún</p>
            @endif
        </x-ui.card>
    </div>
    
    <!-- Tab Content: Milestones -->
    <div x-show="activeTab === 'milestones'" class="mt-6" style="display: none;">
        <x-ui.card>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Hitos del Plan</h2>
            </div>
            @if($plan->milestones->count() > 0)
                <div class="space-y-4">
                    @foreach($plan->milestones as $milestone)
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $milestone->name }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $milestone->description }}</p>
                                    <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                        @if($milestone->target_date)
                                            <span>Objetivo: {{ $milestone->target_date->format('d/m/Y') }}</span>
                                        @endif
                                        <span>Progreso: {{ $milestone->progress_percentage }}%</span>
                                    </div>
                                </div>
                                <x-ui.badge variant="{{ $milestone->status === 'completed' ? 'success' : ($milestone->isDelayed() ? 'error' : 'warning') }}">
                                    {{ $milestone->status_label }}
                                </x-ui.badge>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 text-center py-8">No hay hitos definidos aún</p>
            @endif
        </x-ui.card>
    </div>
    
    <!-- Tab Content: Tasks -->
    <div x-show="activeTab === 'tasks'" class="mt-6" style="display: none;">
        <x-ui.card>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Tareas del Plan</h2>
                <x-ui.button href="{{ route('tasks.create', ['plan_id' => $plan->id]) }}" variant="primary">
                    Crear Tarea
                </x-ui.button>
            </div>
            @if($plan->tasks->count() > 0)
                <div class="space-y-3">
                    @foreach($plan->tasks->take(10) as $task)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900">{{ $task->title }}</h3>
                                <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                                    <span>{{ $task->status_label }}</span>
                                    @if($task->due_date)
                                        <span>Vence: {{ $task->due_date->format('d/m/Y') }}</span>
                                    @endif
                                </div>
                            </div>
                            <x-ui.badge variant="{{ $task->priority === 'critical' ? 'error' : ($task->priority === 'high' ? 'warning' : 'info') }}">
                                {{ $task->priority_label }}
                            </x-ui.badge>
                        </div>
                    @endforeach
                    @if($plan->tasks->count() > 10)
                        <div class="text-center pt-4">
                            <a href="{{ route('tasks.index', ['plan_id' => $plan->id]) }}" class="text-sm text-red-600 hover:text-red-700 font-medium">
                                Ver todas las tareas ({{ $plan->tasks->count() }})
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <p class="text-sm text-gray-500 text-center py-8">No hay tareas definidas aún</p>
            @endif
        </x-ui.card>
    </div>
    
    <!-- Tab Content: Risks -->
    <div x-show="activeTab === 'risks'" class="mt-6" style="display: none;">
        <x-ui.card>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Riesgos del Plan</h2>
                <x-ui.button href="{{ route('risks.create', ['plan_id' => $plan->id]) }}" variant="primary">
                    Crear Riesgo
                </x-ui.button>
            </div>
            @if($plan->risks->count() > 0)
                <div class="space-y-4">
                    @foreach($plan->risks as $risk)
                        @php
                            $borderColor = match($risk->category) {
                                'critico' => 'border-red-500',
                                'alto' => 'border-orange-500',
                                'medio' => 'border-yellow-500',
                                'bajo' => 'border-green-500',
                                default => 'border-gray-500',
                            };
                        @endphp
                        <div class="border-l-4 {{ $borderColor }} pl-4 py-2">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $risk->name }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $risk->description }}</p>
                                    <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                        <span>Probabilidad: {{ $risk->probability }}/5</span>
                                        <span>Impacto: {{ $risk->impact }}/5</span>
                                        <span>Nivel: {{ $risk->risk_level }}/25</span>
                                    </div>
                                </div>
                                <x-ui.badge variant="{{ $risk->category === 'critico' ? 'error' : ($risk->category === 'alto' ? 'warning' : 'info') }}">
                                    {{ ucfirst($risk->category) }}
                                </x-ui.badge>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 text-center py-8">No hay riesgos identificados aún</p>
            @endif
        </x-ui.card>
    </div>
</div>
@endsection

