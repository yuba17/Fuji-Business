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
@if(auth()->user()->isDirector() || (auth()->user()->isManager() && $plan->manager_id === auth()->id()))
    <x-ui.button href="{{ route('plans.edit', $plan) }}" variant="secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Editar
    </x-ui.button>
@endif
@endsection

@section('content')
<!-- Header del Plan -->
<div class="mb-8">
    <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
            <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words mb-2">
                {{ $plan->name }}
            </h1>
            <p class="text-gray-600">{{ $plan->description }}</p>
        </div>
        <x-ui.badge 
            variant="{{ $plan->status === 'approved' ? 'success' : ($plan->status === 'in_progress' ? 'info' : 'warning') }}">
            {{ $plan->status_label }}
        </x-ui.badge>
    </div>
    
    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <span>{{ $plan->planType->name ?? 'Sin tipo' }}</span>
        </div>
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span>{{ $plan->area->name ?? 'Sin área' }}</span>
        </div>
        @if($plan->manager)
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Manager: {{ $plan->manager->name }}</span>
            </div>
        @endif
    </div>
</div>

<!-- Estadísticas Rápidas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <x-ui.card variant="gradient" border-color="blue">
        <div class="text-center">
            <p class="text-blue-100 text-xs font-medium uppercase tracking-wide">KPIs</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ $plan->kpis->count() }}</p>
        </div>
    </x-ui.card>
    
    <x-ui.card variant="gradient" border-color="green">
        <div class="text-center">
            <p class="text-green-100 text-xs font-medium uppercase tracking-wide">Hitos</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ $plan->milestones->count() }}</p>
        </div>
    </x-ui.card>
    
    <x-ui.card variant="gradient" border-color="orange">
        <div class="text-center">
            <p class="text-orange-100 text-xs font-medium uppercase tracking-wide">Tareas</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ $plan->tasks->count() }}</p>
        </div>
    </x-ui.card>
    
    <x-ui.card variant="gradient" border-color="red">
        <div class="text-center">
            <p class="text-red-100 text-xs font-medium uppercase tracking-wide">Riesgos</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ $plan->risks->count() }}</p>
        </div>
    </x-ui.card>
</div>

<!-- Tabs de Navegación -->
<div class="mb-6" x-data="{ activeTab: 'overview' }">
    <div class="border-b border-gray-200">
        <nav class="flex space-x-8">
            <button @click="activeTab = 'overview'" 
                    :class="activeTab === 'overview' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Resumen
            </button>
            <button @click="activeTab = 'sections'" 
                    :class="activeTab === 'sections' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Secciones
            </button>
            <button @click="activeTab = 'kpis'" 
                    :class="activeTab === 'kpis' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                KPIs
            </button>
            <button @click="activeTab = 'milestones'" 
                    :class="activeTab === 'milestones' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Hitos
            </button>
            <button @click="activeTab = 'tasks'" 
                    :class="activeTab === 'tasks' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Tareas
            </button>
            <button @click="activeTab = 'risks'" 
                    :class="activeTab === 'risks' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Riesgos
            </button>
        </nav>
    </div>
    
    <!-- Tab Content: Overview -->
    <div x-show="activeTab === 'overview'" class="mt-6">
        <x-ui.card>
            <h2 class="text-xl font-bold text-gray-800 mb-4">Información General</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Fecha de Inicio</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $plan->start_date ? $plan->start_date->format('d/m/Y') : 'No definida' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Fecha Objetivo</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $plan->target_date ? $plan->target_date->format('d/m/Y') : 'No definida' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Versión</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $plan->version ?? '1.0' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Director</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $plan->director->name ?? 'No asignado' }}</dd>
                </div>
            </dl>
        </x-ui.card>
    </div>
    
    <!-- Tab Content: Sections -->
    <div x-show="activeTab === 'sections'" class="mt-6" style="display: none;">
        <x-ui.card>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Secciones del Plan</h2>
            </div>
            @if($plan->sections->count() > 0)
                <div class="space-y-4">
                    @foreach($plan->sections as $section)
                        <div class="border-l-4 border-red-500 pl-4 py-2">
                            <h3 class="font-semibold text-gray-900">{{ $section->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($section->content ?? '', 200) }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 text-center py-8">No hay secciones definidas aún</p>
            @endif
        </x-ui.card>
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

