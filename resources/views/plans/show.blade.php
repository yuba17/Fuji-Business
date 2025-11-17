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
    <a href="{{ route('plans.presentation', $plan) }}" class="inline-block" target="_blank">
        <x-ui.button variant="primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            Presentación
        </x-ui.button>
    </a>
    <a href="{{ route('plans.presentation.pdf', $plan) }}" class="inline-block">
        <x-ui.button variant="secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            Exportar PDF
        </x-ui.button>
    </a>
    <a href="{{ route('plans.presentation.ppt', $plan) }}" class="inline-block">
        <x-ui.button variant="secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Exportar PowerPoint
        </x-ui.button>
    </a>
    <a href="{{ route('scenarios.index', $plan) }}" class="inline-block">
        <x-ui.button variant="secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
            Escenarios
        </x-ui.button>
    </a>
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

<!-- Tabs de Navegación (Resumen / Organización / Ejecución) -->
<div class="mb-6" x-data="{ activeTab: 'summary' }">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-2 border border-gray-200">
        <nav class="flex flex-wrap gap-2">
            <button @click="activeTab = 'summary'" 
                    :class="activeTab === 'summary' ? 'bg-gradient-to-r from-red-600 to-orange-600 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Resumen
            </button>
            @if($plan->planType && str_contains(strtolower($plan->planType->name), 'desarrollo interno'))
            <button @click="activeTab = 'organization'" 
                    :class="activeTab === 'organization' ? 'bg-gradient-to-r from-emerald-600 to-green-500 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M12 11a4 4 0 110-8 4 4 0 010 8z" />
                </svg>
                Organización
            </button>
            <button @click="activeTab = 'competencies'" 
                    :class="activeTab === 'competencies' ? 'bg-gradient-to-r from-purple-600 to-indigo-500 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                Competencias
            </button>
            <button @click="activeTab = 'infrastructure'" 
                    :class="activeTab === 'infrastructure' ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                </svg>
                Infraestructura
            </button>
            <button @click="activeTab = 'processes'" 
                    :class="activeTab === 'processes' ? 'bg-gradient-to-r from-orange-600 to-red-500 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Procesos
            </button>
            <button @click="activeTab = 'quality'" 
                    :class="activeTab === 'quality' ? 'bg-gradient-to-r from-green-600 to-emerald-500 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Calidad
            </button>
            <button @click="activeTab = 'training'" 
                    :class="activeTab === 'training' ? 'bg-gradient-to-r from-yellow-600 to-orange-500 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Formación
            </button>
            <button @click="activeTab = 'rd'" 
                    :class="activeTab === 'rd' ? 'bg-gradient-to-r from-indigo-600 to-purple-500 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
                I+D
            </button>
            <button @click="activeTab = 'opsec'" 
                    :class="activeTab === 'opsec' ? 'bg-gradient-to-r from-red-600 to-pink-500 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                OPSEC
            </button>
            <button @click="activeTab = 'operational-roadmap'" 
                    :class="activeTab === 'operational-roadmap' ? 'bg-gradient-to-r from-teal-600 to-cyan-500 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Roadmap Operativo
            </button>
            @endif
            <button @click="activeTab = 'execution'" 
                    :class="activeTab === 'execution' ? 'bg-gradient-to-r from-blue-600 to-cyan-600 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Ejecución
            </button>
            @if($plan->planType && str_contains(strtolower($plan->planType->name), 'comercial'))
            <button @click="activeTab = 'sectorial'" 
                    :class="activeTab === 'sectorial' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Análisis Sectorial
            </button>
            @endif
        </nav>
    </div>
    
    <!-- Tab Content: Resumen -->
    <div x-show="activeTab === 'summary'" class="mt-6">
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

    @if($plan->planType && str_contains(strtolower($plan->planType->name), 'desarrollo interno'))
    <!-- Tab Content: Organización (solo Plan Desarrollo Interno) -->
    <div x-show="activeTab === 'organization'" class="mt-6" x-data="{ orgSubTab: 'map' }" style="display: none;">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-green-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                        👥
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Organización y Equipo</h2>
                        <p class="text-xs text-gray-500">
                            Vista estructural de las personas vinculadas al área {{ $plan->area->name ?? 'sin área' }} y sus roles internos.
                        </p>
                    </div>
                </div>
            </div>

            @if(isset($teamUsers) && $teamUsers && $teamUsers->count() > 0)
                <!-- Estadísticas rápidas -->
                @if(isset($organizationStats))
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gradient-to-br from-emerald-50 to-green-100 rounded-xl p-4 border border-emerald-200">
                        <p class="text-xs font-medium text-emerald-700 uppercase tracking-wide">Personas</p>
                        <p class="mt-2 text-3xl font-bold text-emerald-900">{{ $organizationStats['total_people'] }}</p>
                        <p class="mt-1 text-xs text-emerald-800">Equipo total</p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                        <p class="text-xs font-medium text-blue-700 uppercase tracking-wide">Roles</p>
                        <p class="mt-2 text-3xl font-bold text-blue-900">{{ $organizationStats['total_roles'] }}</p>
                        <p class="mt-1 text-xs text-blue-800">Diversidad de roles</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                        <p class="text-xs font-medium text-purple-700 uppercase tracking-wide">Líneas</p>
                        <p class="mt-2 text-3xl font-bold text-purple-900">{{ $organizationStats['total_service_lines'] }}</p>
                        <p class="mt-1 text-xs text-purple-800">Líneas de servicio</p>
                    </div>
                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 border border-orange-200">
                        <p class="text-xs font-medium text-orange-700 uppercase tracking-wide">Ratio IC:Manager</p>
                        <p class="mt-2 text-3xl font-bold text-orange-900">{{ $organizationStats['manager_ic_ratio'] }}:1</p>
                        <p class="mt-1 text-xs text-orange-800">Individual Contributors por Manager</p>
                    </div>
                </div>
                @endif

                <!-- Subpestañas de Organización -->
                <div class="mb-6">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-2 border border-gray-200">
                        <nav class="flex flex-wrap gap-2">
                            <button @click="orgSubTab = 'map'" 
                                    :class="orgSubTab === 'map' ? 'bg-gradient-to-r from-emerald-600 to-green-500 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                                    class="whitespace-nowrap py-2 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                                Mapa de Equipo
                            </button>
                            <button @click="orgSubTab = 'capacity'" 
                                    :class="orgSubTab === 'capacity' ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                                    class="whitespace-nowrap py-2 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Capacidad
                            </button>
                            <button @click="orgSubTab = 'talent'" 
                                    :class="orgSubTab === 'talent' ? 'bg-gradient-to-r from-purple-600 to-indigo-500 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                                    class="whitespace-nowrap py-2 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                                Talento
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Subpestaña: Mapa de Equipo -->
                <div x-show="orgSubTab === 'map'" x-transition>
                    {{-- Líneas de servicio dentro del área --}}
                    @if(isset($serviceLines) && $serviceLines && $serviceLines->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-gray-900 mb-3">Líneas de servicio del área</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($serviceLines as $line)
                                <div x-data="{ open: false }" class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200 p-4">
                                    <button @click="open = !open" class="w-full flex items-center justify-between mb-1">
                                        <div class="text-left">
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Línea de servicio</p>
                                            <p class="text-sm font-bold text-gray-900">{{ $line->name }}</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @if($line->manager)
                                                <span class="w-7 h-7 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 text-white flex items-center justify-center text-[11px] font-bold">
                                                    {{ $line->manager->initials() }}
                                                </span>
                                            @endif
                                            <span class="w-6 h-6 rounded-full bg-white/70 text-gray-600 flex items-center justify-center text-xs" x-text="open ? '-' : '+'"></span>
                                        </div>
                                    </button>
                                    <p class="text-[11px] text-gray-500 mb-1">
                                        {{ $line->description ?? 'Línea de servicio sin descripción detallada.' }}
                                    </p>
                                    <p class="text-[11px] text-gray-600 mb-2">
                                        <span class="font-semibold">{{ $line->users->count() }}</span> persona(s) asociadas
                                    </p>

                                    <div x-show="open" x-transition class="mt-2 border-t border-gray-200 pt-2">
                                        @if($line->users->count() > 0)
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($line->users as $member)
                                                    <div class="px-2 py-1 bg-white rounded-full shadow-sm border border-gray-200 flex items-center gap-2 text-[11px] text-gray-700">
                                                        <span class="w-5 h-5 rounded-full bg-gradient-to-br from-red-500 to-orange-500 text-white flex items-center justify-center text-[9px] font-bold">
                                                            {{ $member->initials() }}
                                                        </span>
                                                        <span>{{ $member->name }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-[11px] text-gray-400">No hay personas asignadas todavía a esta línea.</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="space-y-4">
                        @foreach($groupedByInternalRole as $roleName => $usersInRole)
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200 p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <span class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-500 to-green-500 text-white flex items-center justify-center text-sm font-bold">
                                            {{ mb_substr($roleName, 0, 2) }}
                                        </span>
                                        <div>
                                            <h3 class="text-sm font-bold text-gray-900">{{ $roleName }}</h3>
                                            <p class="text-[11px] text-gray-500">{{ $usersInRole->count() }} persona(s) en este rol</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($usersInRole as $member)
                                        <div class="px-3 py-1.5 bg-white rounded-full shadow-sm border border-gray-200 flex items-center gap-2 text-xs text-gray-700">
                                            <span class="w-6 h-6 rounded-full bg-gradient-to-br from-red-500 to-orange-500 text-white flex items-center justify-center text-[10px] font-bold">
                                                {{ $member->initials() }}
                                            </span>
                                            <span>{{ $member->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Subpestaña: Capacidad (Heatmap) -->
                <div x-show="orgSubTab === 'capacity'" x-transition style="display: none;">
                    @if(isset($capacityHeatmap) && count($capacityHeatmap) > 0)
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-gray-900 mb-4">Heatmap de Capacidad por Línea de Servicio</h3>
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Línea de Servicio</th>
                                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wide">Director</th>
                                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wide">Manager</th>
                                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wide">Lead</th>
                                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wide">Senior</th>
                                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wide">Mid</th>
                                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wide">Junior</th>
                                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wide">Ratio IC:Manager</th>
                                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wide">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($capacityHeatmap as $lineData)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm font-semibold text-gray-900">{{ $lineData['line']->name }}</span>
                                                    @if($lineData['line']->manager)
                                                        <span class="w-6 h-6 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 text-white flex items-center justify-center text-[10px] font-bold" title="{{ $lineData['line']->manager->name }}">
                                                            {{ $lineData['line']->manager->initials() }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $lineData['levels']['director'] > 0 ? 'bg-red-100 text-red-800 font-bold' : 'bg-gray-100 text-gray-400' }}">
                                                    {{ $lineData['levels']['director'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $lineData['levels']['manager'] > 0 ? 'bg-blue-100 text-blue-800 font-bold' : 'bg-gray-100 text-gray-400' }}">
                                                    {{ $lineData['levels']['manager'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $lineData['levels']['lead'] > 0 ? 'bg-purple-100 text-purple-800 font-bold' : 'bg-gray-100 text-gray-400' }}">
                                                    {{ $lineData['levels']['lead'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $lineData['levels']['senior'] > 0 ? 'bg-green-100 text-green-800 font-bold' : 'bg-gray-100 text-gray-400' }}">
                                                    {{ $lineData['levels']['senior'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $lineData['levels']['mid'] > 0 ? 'bg-yellow-100 text-yellow-800 font-bold' : 'bg-gray-100 text-gray-400' }}">
                                                    {{ $lineData['levels']['mid'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $lineData['levels']['junior'] > 0 ? 'bg-orange-100 text-orange-800 font-bold' : 'bg-gray-100 text-gray-400' }}">
                                                    {{ $lineData['levels']['junior'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="text-sm font-semibold text-gray-900">{{ $lineData['ratio'] }}:1</span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if($lineData['health'] === 'warning')
                                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">⚠️ Crítico</span>
                                                @elseif($lineData['health'] === 'caution')
                                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">⚠️ Atención</span>
                                                @else
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">✅ Saludable</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-xs text-blue-800">
                                <strong>Leyenda:</strong> El ratio IC:Manager indica cuántos Individual Contributors hay por cada Manager. 
                                Un ratio > 12:1 puede indicar sobrecarga de managers. Un ratio < 8:1 puede indicar sobrecontratación de managers.
                            </p>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-10">
                        <p class="text-sm text-gray-500">No hay líneas de servicio configuradas para mostrar el heatmap.</p>
                    </div>
                    @endif
                </div>

                <!-- Subpestaña: Talento (Pirámide) -->
                <div x-show="orgSubTab === 'talent'" x-transition style="display: none;">
                    @if(isset($talentPyramid))
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-gray-900 mb-4">Pirámide de Talento</h3>
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <div class="flex flex-col items-center gap-2 max-w-md mx-auto">
                                @php
                                    $levels = [
                                        'director' => ['label' => 'Director', 'color' => 'from-red-500 to-red-600', 'bg' => 'bg-red-100', 'text' => 'text-red-800'],
                                        'manager' => ['label' => 'Manager', 'color' => 'from-blue-500 to-blue-600', 'bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                                        'lead' => ['label' => 'Lead', 'color' => 'from-purple-500 to-purple-600', 'bg' => 'bg-purple-100', 'text' => 'text-purple-800'],
                                        'senior' => ['label' => 'Senior', 'color' => 'from-green-500 to-green-600', 'bg' => 'bg-green-100', 'text' => 'text-green-800'],
                                        'mid' => ['label' => 'Mid', 'color' => 'from-yellow-500 to-yellow-600', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
                                        'junior' => ['label' => 'Junior', 'color' => 'from-orange-500 to-orange-600', 'bg' => 'bg-orange-100', 'text' => 'text-orange-800'],
                                    ];
                                @endphp
                                @foreach($levels as $levelKey => $levelInfo)
                                    @php
                                        $count = $talentPyramid[$levelKey] ?? 0;
                                        $pct = $talentPyramid[$levelKey . '_pct'] ?? 0;
                                        $width = max(20, min(100, ($count * 10) + 20)); // Ancho mínimo 20%, máximo 100%
                                    @endphp
                                    <div class="w-full flex items-center gap-4">
                                        <div class="w-24 text-right">
                                            <p class="text-xs font-semibold text-gray-700">{{ $levelInfo['label'] }}</p>
                                            <p class="text-[10px] text-gray-500">{{ $pct }}%</p>
                                        </div>
                                        <div class="flex-1 relative">
                                            <div class="bg-gradient-to-r {{ $levelInfo['color'] }} rounded-lg py-2 px-4 text-white text-sm font-bold text-center transition-all duration-300" 
                                                 style="width: {{ $width }}%; min-width: 60px;">
                                                {{ $count }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            @else
                <div class="text-center py-10">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="text-sm text-gray-500">Todavía no hay personas asignadas al área de este plan o no se ha definido el área.</p>
                </div>
            @endif
        </div>
    </div>
    @endif

    @if($plan->planType && str_contains(strtolower($plan->planType->name), 'desarrollo interno'))
    <!-- Tab Content: Competencias -->
    <div x-show="activeTab === 'competencies'" class="mt-6" style="display: none;">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                    🧠
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Gestión de Competencias</h2>
                    <p class="text-xs text-gray-500">
                        Define, evalúa y desarrolla las competencias clave del equipo.
                    </p>
                </div>
            </div>
            @livewire('plans.plan-desarrollo-competencias', ['plan' => $plan], key('competencies-' . $plan->id))
        </div>
    </div>

    <!-- Tab Content: Infraestructura Técnica -->
    <div x-show="activeTab === 'infrastructure'" class="mt-6" style="display: none;">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                    🖥️
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Infraestructura Técnica</h2>
                    <p class="text-xs text-gray-500">
                        Inventario y gestión de los recursos técnicos y tecnológicos.
                    </p>
                </div>
            </div>
            @livewire('plans.plan-desarrollo-infraestructura', ['plan' => $plan], key('infrastructure-' . $plan->id))
        </div>
    </div>

    <!-- Tab Content: Procesos Operativos -->
    <div x-show="activeTab === 'processes'" class="mt-6" style="display: none;">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                        🔄
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Procesos Operativos</h2>
                        <p class="text-xs text-gray-500">Mapa de procesos y mejoras operativas</p>
                    </div>
                </div>
            </div>
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <p class="text-sm text-gray-500 mb-2">Sección en desarrollo</p>
                <p class="text-xs text-gray-400">Próximamente: Mapa de procesos, diagramas de flujo y mejoras</p>
            </div>
        </div>
    </div>

    <!-- Tab Content: Calidad -->
    <div x-show="activeTab === 'quality'" class="mt-6" style="display: none;">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                        ✅
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Calidad</h2>
                        <p class="text-xs text-gray-500">Estándares de calidad, métricas y procesos de QA</p>
                    </div>
                </div>
            </div>
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-gray-500 mb-2">Sección en desarrollo</p>
                <p class="text-xs text-gray-400">Próximamente: Estándares de calidad, métricas y auditorías</p>
            </div>
        </div>
    </div>

    <!-- Tab Content: Formación -->
    <div x-show="activeTab === 'training'" class="mt-6" style="display: none;">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                        📚
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Formación</h2>
                        <p class="text-xs text-gray-500">Plan de formación y seguimiento de competencias</p>
                    </div>
                </div>
            </div>
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <p class="text-sm text-gray-500 mb-2">Sección en desarrollo</p>
                <p class="text-xs text-gray-400">Próximamente: Plan de formación, cursos y seguimiento</p>
            </div>
        </div>
    </div>

    <!-- Tab Content: I+D -->
    <div x-show="activeTab === 'rd'" class="mt-6" style="display: none;">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                        🔬
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">I+D</h2>
                        <p class="text-xs text-gray-500">Proyectos de investigación y desarrollo</p>
                    </div>
                </div>
            </div>
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
                <p class="text-sm text-gray-500 mb-2">Sección en desarrollo</p>
                <p class="text-xs text-gray-400">Próximamente: Proyectos de I+D, roadmap y presupuesto</p>
            </div>
        </div>
    </div>

    <!-- Tab Content: OPSEC -->
    <div x-show="activeTab === 'opsec'" class="mt-6" style="display: none;">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-pink-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                        🔒
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">OPSEC</h2>
                        <p class="text-xs text-gray-500">Políticas de seguridad y análisis de riesgos</p>
                    </div>
                </div>
            </div>
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <p class="text-sm text-gray-500 mb-2">Sección en desarrollo</p>
                <p class="text-xs text-gray-400">Próximamente: Políticas de seguridad, análisis de riesgos y auditorías</p>
            </div>
        </div>
    </div>

    <!-- Tab Content: Roadmap Operativo -->
    <div x-show="activeTab === 'operational-roadmap'" class="mt-6" style="display: none;">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-teal-500">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                        🗓️
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Roadmap Operativo</h2>
                        <p class="text-xs text-gray-500">Vista Gantt de hitos operativos y dependencias</p>
                    </div>
                </div>
            </div>
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <p class="text-sm text-gray-500 mb-2">Sección en desarrollo</p>
                <p class="text-xs text-gray-400">Próximamente: Vista Gantt de hitos operativos e integración con milestones</p>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Mensajes de éxito/error -->
    @if(session('success'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 3000)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed top-4 right-4 z-50">
        <div class="bg-green-50 border-l-4 border-green-400 rounded-r-lg p-4 shadow-lg">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif
    
    @if(session('error'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 3000)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed top-4 right-4 z-50">
        <div class="bg-red-50 border-l-4 border-red-400 rounded-r-lg p-4 shadow-lg">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-red-800 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Tab Content: Ejecución (KPIs, Hitos, Tareas, Riesgos) -->
    <div x-show="activeTab === 'execution'" class="mt-6" style="display: none;">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('kpis.index', ['plan_id' => $plan->id]) }}" class="bg-white rounded-xl shadow-md p-6 border border-gray-200 hover:shadow-lg hover:border-blue-300 transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-gray-600 text-sm font-medium mb-2">KPIs</p>
                <p class="text-4xl font-bold text-gray-900 mb-3">{{ $plan->kpis->count() }}</p>
                <div class="flex items-center gap-2 text-blue-600 text-xs font-semibold">
                    <span>Ver detalles</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
            
            <a href="{{ route('plans.milestones.index', $plan) }}" class="bg-white rounded-xl shadow-md p-6 border border-gray-200 hover:shadow-lg hover:border-green-300 transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-gray-600 text-sm font-medium mb-2">Hitos</p>
                <p class="text-4xl font-bold text-gray-900 mb-3">{{ $plan->milestones->count() }}</p>
                <div class="flex items-center gap-2 text-green-600 text-xs font-semibold">
                    <span>Ver detalles</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
            
            <a href="{{ route('tasks.index', ['plan_id' => $plan->id]) }}" class="bg-white rounded-xl shadow-md p-6 border border-gray-200 hover:shadow-lg hover:border-orange-300 transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <p class="text-gray-600 text-sm font-medium mb-2">Tareas</p>
                <p class="text-4xl font-bold text-gray-900 mb-3">{{ $plan->tasks->count() }}</p>
                <div class="flex items-center gap-2 text-orange-600 text-xs font-semibold">
                    <span>Ver detalles</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
            
            <a href="{{ route('risks.index', ['plan_id' => $plan->id]) }}" class="bg-white rounded-xl shadow-md p-6 border border-gray-200 hover:shadow-lg hover:border-red-300 transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200 transition-colors">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-gray-600 text-sm font-medium mb-2">Riesgos</p>
                <p class="text-4xl font-bold text-gray-900 mb-3">{{ $plan->risks->count() }}</p>
                <div class="flex items-center gap-2 text-red-600 text-xs font-semibold">
                    <span>Ver detalles</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
        </div>
    </div>
    
    <!-- Tab Content: Sectorial (Solo para Planes Comerciales) -->
    @if($plan->planType && str_contains(strtolower($plan->planType->name), 'comercial'))
    <div x-show="activeTab === 'sectorial'" class="mt-6" style="display: none;">
        @livewire('plans.plan-sector-analysis', ['plan' => $plan], key('sector-analysis-' . $plan->id))
    </div>
    @endif
</div>
@endsection

