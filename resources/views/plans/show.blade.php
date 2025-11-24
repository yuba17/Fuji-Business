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
<div class="flex items-center gap-2" x-data="{ showMenu: false }">
    @can('update', $plan)
        <x-ui.button href="{{ route('plans.edit', $plan) }}" variant="primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Editar
        </x-ui.button>
    @endcan
    
    <!-- Menú de acciones adicionales -->
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" 
                class="px-4 py-2 text-sm font-semibold bg-white hover:bg-gray-50 text-gray-700 rounded-xl border border-gray-200 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
            </svg>
            Más
        </button>
        
        <div x-show="open" 
             @click.away="open = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50"
             style="display: none;">
            <a href="{{ route('plans.presentation', $plan) }}" target="_blank" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Presentación
            </a>
            <a href="{{ route('plans.presentation.pdf', $plan) }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Exportar PDF
            </a>
            <a href="{{ route('plans.presentation.ppt', $plan) }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exportar PowerPoint
            </a>
            <div class="border-t border-gray-200 my-1"></div>
            <a href="{{ route('plans.roadmap', $plan) }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Roadmap
            </a>
            <a href="{{ route('scenarios.index', $plan) }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                Escenarios
            </a>
            <a href="{{ route('plans.versions', $plan) }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Versiones (v{{ $plan->version }})
            </a>
        </div>
    </div>
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
                    <h1 class="text-3xl md:text-4xl font-bold mb-2">{{ $plan->name }}</h1>
                    @if($plan->description)
                        <p class="text-red-50 text-sm line-clamp-2">{{ $plan->description }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-2 ml-4">
                    <x-ui.badge 
                        variant="{{ $plan->status === 'approved' ? 'success' : ($plan->status === 'in_progress' ? 'info' : 'warning') }}">
                        {{ $plan->status_label }}
                    </x-ui.badge>
                    @livewire('plans.plan-status-changer', ['plan' => $plan], key('status-changer-' . $plan->id))
                </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-3 text-xs md:text-sm text-red-50">
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span>{{ $plan->planType->name ?? 'Sin tipo' }}</span>
                </div>
                <span class="text-white/30">•</span>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>{{ $plan->area->name ?? 'Sin área' }}</span>
                </div>
                @if($plan->manager)
                    <span class="text-white/30">•</span>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>{{ $plan->manager->name }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@php
    $tabQueryParams = request()->except('tab', 'section');
    $tabUrl = function (string $key) use ($plan, $tabQueryParams) {
        return route('plans.show', array_merge(['plan' => $plan], $tabQueryParams, ['tab' => $key]));
    };
@endphp

<!-- Tabs de Navegación (Resumen / Organización / Ejecución) -->
<div class="mb-6">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-2 border border-gray-200">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ $tabUrl('summary') }}"
               @class([
                   'whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2',
                   'bg-gradient-to-r from-red-600 to-orange-600 text-white shadow-lg' => $activeTab === 'summary',
                   'bg-white text-gray-600 hover:bg-gray-50' => $activeTab !== 'summary',
               ])>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Resumen
            </a>

            @if($isInternalPlan)
                <a href="{{ $tabUrl('organization') }}"
                   @class([
                       'whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2',
                       'bg-gradient-to-r from-emerald-600 to-green-500 text-white shadow-lg' => $activeTab === 'organization',
                       'bg-white text-gray-600 hover:bg-gray-50' => $activeTab !== 'organization',
                   ])>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M12 11a4 4 0 110-8 4 4 0 010 8z" />
                    </svg>
                    Organización
                </a>

                <a href="{{ $tabUrl('competencies') }}"
                   @class([
                       'whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2',
                       'bg-gradient-to-r from-purple-600 to-indigo-500 text-white shadow-lg' => $activeTab === 'competencies',
                       'bg-white text-gray-600 hover:bg-gray-50' => $activeTab !== 'competencies',
                   ])>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    Competencias
                </a>

                <a href="{{ $tabUrl('infrastructure') }}"
                   @class([
                       'whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2',
                       'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg' => $activeTab === 'infrastructure',
                       'bg-white text-gray-600 hover:bg-gray-50' => $activeTab !== 'infrastructure',
                   ])>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                    </svg>
                    Infraestructura
                </a>

                <a href="{{ $tabUrl('certifications') }}"
                   @class([
                       'whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2',
                       'bg-gradient-to-r from-yellow-600 to-amber-500 text-white shadow-lg' => $activeTab === 'certifications',
                       'bg-white text-gray-600 hover:bg-gray-50' => $activeTab !== 'certifications',
                   ])>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    Certificaciones
                </a>

                <a href="{{ $tabUrl('operational-roadmap') }}"
                   @class([
                       'whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2',
                       'bg-gradient-to-r from-teal-600 to-cyan-500 text-white shadow-lg' => $activeTab === 'operational-roadmap',
                       'bg-white text-gray-600 hover:bg-gray-50' => $activeTab !== 'operational-roadmap',
                   ])>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Roadmap
                </a>

                <a href="{{ $tabUrl('innovation-tooling') }}"
                   @class([
                       'whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2',
                       'bg-gradient-to-r from-indigo-600 to-blue-500 text-white shadow-lg' => $activeTab === 'innovation-tooling',
                       'bg-white text-gray-600 hover:bg-gray-50' => $activeTab !== 'innovation-tooling',
                   ])>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3v8h8M21 3l-9 9-4-4-6 6"/>
                    </svg>
                    I+D & Tooling
                </a>

                <a href="{{ $tabUrl('team-culture') }}"
                   @class([
                       'whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2',
                       'bg-gradient-to-r from-pink-600 to-purple-500 text-white shadow-lg' => $activeTab === 'team-culture',
                       'bg-white text-gray-600 hover:bg-gray-50' => $activeTab !== 'team-culture',
                   ])>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M12 11a4 4 0 110-8 4 4 0 010 8z" />
                    </svg>
                    Cultura & Liderazgo
                </a>
            @endif

            @if($isCommercialPlan)
                <a href="{{ $tabUrl('sectorial') }}"
                   @class([
                       'whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2',
                       'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' => $activeTab === 'sectorial',
                       'bg-white text-gray-600 hover:bg-gray-50' => $activeTab !== 'sectorial',
                   ])>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Análisis Sectorial
                </a>
            @endif
        </div>
    </div>
    
    <!-- Tab Content: Resumen -->
    @if($activeTab === 'summary')
    <div class="mt-6">
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

            @php
                $kpisCount = $plan->kpis->count();
                $kpisRecent = $plan->kpis->where('updated_at', '>=', now()->subDays(30))->count();
                $milestonesCount = $plan->milestones->count();
                $milestonesDelayed = $plan->milestones->filter(fn($m) => $m->isDelayed())->count();
                $tasksCount = $plan->tasks->count();
                $tasksOverdue = $plan->tasks->filter(fn($t) => $t->isOverdue())->count();
                $risksCount = $plan->risks->count();
                $risksOpen = $plan->risks->where('status', 'open')->count();
            @endphp

            <div class="mt-8">
                <h3 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-600 to-cyan-500 text-white flex items-center justify-center text-xs font-bold">⚙️</span>
                    Operaciones del plan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-xl shadow-md p-6 border border-blue-100 hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 text-white flex items-center justify-center shadow-inner">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">KPIs</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $kpisCount }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 text-[10px] font-bold rounded-full {{ $kpisRecent > 0 ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $kpisRecent }} act. 30d
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mb-4">Indicadores clave del plan</p>
                        <div class="flex items-center justify-between text-xs font-semibold">
                            <a href="{{ route('kpis.index', ['plan_id' => $plan->id]) }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                Ver tablero
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('kpis.create', ['plan_id' => $plan->id]) }}" class="px-2 py-1 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors">
                                + Nuevo
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 border border-green-100 hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-green-500 text-white flex items-center justify-center shadow-inner">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Hitos</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $milestonesCount }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 text-[10px] font-bold rounded-full {{ $milestonesDelayed > 0 ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700' }}">
                                {{ $milestonesDelayed }} retraso(s)
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mb-4">Seguimiento del roadmap</p>
                        <div class="flex items-center justify-between text-xs font-semibold">
                            <a href="{{ route('plans.milestones.index', $plan) }}" class="text-green-600 hover:text-green-800 flex items-center gap-1">
                                Ver hitos
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('plans.milestones.create', $plan) }}" class="px-2 py-1 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors">
                                + Nuevo
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 border border-orange-100 hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-amber-500 text-white flex items-center justify-center shadow-inner">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tareas</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $tasksCount }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 text-[10px] font-bold rounded-full {{ $tasksOverdue > 0 ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700' }}">
                                {{ $tasksOverdue }} vencida(s)
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mb-4">Backlog operativo y acciones</p>
                        <div class="flex items-center justify-between text-xs font-semibold">
                            <a href="{{ route('tasks.index', ['plan_id' => $plan->id]) }}" class="text-orange-600 hover:text-orange-800 flex items-center gap-1">
                                Ver tareas
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('tasks.create', ['plan_id' => $plan->id]) }}" class="px-2 py-1 bg-orange-50 text-orange-700 rounded-lg hover:bg-orange-100 transition-colors">
                                + Nueva
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 border border-red-100 hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500 to-red-500 text-white flex items-center justify-center shadow-inner">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Riesgos</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $risksCount }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 text-[10px] font-bold rounded-full {{ $risksOpen > 0 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                {{ $risksOpen }} abiertos
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mb-4">Gestión de riesgos operativos</p>
                        <div class="flex items-center justify-between text-xs font-semibold">
                            <a href="{{ route('risks.index', ['plan_id' => $plan->id]) }}" class="text-red-600 hover:text-red-800 flex items-center gap-1">
                                Ver riesgos
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('risks.create', ['plan_id' => $plan->id]) }}" class="px-2 py-1 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors">
                                + Nuevo
                            </a>
                        </div>
        </div>
    </div>
    @endif

    @if($isInternalPlan && $activeTab === 'organization')
    <!-- Tab Content: Organización (solo Plan Desarrollo Interno) -->
    <div class="mt-6" x-data="{ orgSubTab: 'map' }">
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
                            <button @click="orgSubTab = 'profiles'" 
                                    :class="orgSubTab === 'profiles' ? 'bg-gradient-to-r from-indigo-600 to-blue-500 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                                    class="whitespace-nowrap py-2 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Perfiles
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
                                                    <a href="{{ route('profile.show', $member) }}" 
                                                       class="px-2 py-1 bg-white rounded-full shadow-sm border {{ $member->profile_completion_percent >= 100 ? 'border-green-300' : ($member->profile_completion_percent >= 70 ? 'border-yellow-300' : 'border-red-300') }} flex items-center gap-2 text-[11px] text-gray-700 hover:shadow-md transition-all group">
                                                        <span class="w-5 h-5 rounded-full bg-gradient-to-br from-red-500 to-orange-500 text-white flex items-center justify-center text-[9px] font-bold overflow-hidden">
                                                            @if($member->avatar_url)
                                                                <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                                            @else
                                                                {{ $member->initials() }}
                                                            @endif
                                                        </span>
                                                        <span class="group-hover:text-red-600 transition-colors">{{ $member->name }}</span>
                                                        <span class="px-1.5 py-0.5 text-[9px] font-bold rounded-full {{ $member->profile_completion_percent >= 100 ? 'bg-green-100 text-green-800' : ($member->profile_completion_percent >= 70 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                            {{ $member->profile_completion_percent ?? 0 }}%
                                                        </span>
                                                    </a>
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
                                        <a href="{{ route('profile.show', $member) }}" 
                                           class="px-3 py-1.5 bg-white rounded-full shadow-sm border {{ $member->profile_completion_percent >= 100 ? 'border-green-300' : ($member->profile_completion_percent >= 70 ? 'border-yellow-300' : 'border-red-300') }} flex items-center gap-2 text-xs text-gray-700 hover:shadow-md transition-all group">
                                            <span class="w-6 h-6 rounded-full bg-gradient-to-br from-red-500 to-orange-500 text-white flex items-center justify-center text-[10px] font-bold overflow-hidden">
                                                @if($member->avatar_url)
                                                    <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ $member->initials() }}
                                                @endif
                                            </span>
                                            <span class="group-hover:text-red-600 transition-colors">{{ $member->name }}</span>
                                            <span class="px-1.5 py-0.5 text-[9px] font-bold rounded-full {{ $member->profile_completion_percent >= 100 ? 'bg-green-100 text-green-800' : ($member->profile_completion_percent >= 70 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ $member->profile_completion_percent ?? 0 }}%
                                            </span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Subpestaña: Capacidad (Heatmap) -->
                <div x-show="orgSubTab === 'capacity'" x-transition>
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
                <div x-show="orgSubTab === 'talent'" x-transition>
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

                <!-- Subpestaña: Perfiles -->
                <div x-show="orgSubTab === 'profiles'" x-transition>
                    <div class="space-y-6">
                        <!-- Estadísticas de Perfiles -->
                        @if(isset($profileStats))
                        <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl p-5 border-2 border-indigo-200">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                                    👤
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Estado de Perfiles del Equipo</h3>
                                    <p class="text-xs text-gray-600">Completitud y datos de los perfiles de usuario</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                                <div class="bg-white rounded-lg p-3 border border-indigo-100">
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Completitud Media</p>
                                    <p class="text-2xl font-bold text-indigo-900">{{ $profileStats['avg_completion'] }}%</p>
                                    <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-gradient-to-r from-indigo-500 to-blue-500 h-1.5 rounded-full transition-all" 
                                             style="width: {{ $profileStats['avg_completion'] }}%"></div>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-green-100">
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Completos</p>
                                    <p class="text-2xl font-bold text-green-900">{{ $profileStats['complete_profiles'] }}</p>
                                    <p class="text-xs text-gray-600 mt-1">Perfiles al 100%</p>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-yellow-100">
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Incompletos</p>
                                    <p class="text-2xl font-bold text-yellow-900">{{ $profileStats['incomplete_profiles'] }}</p>
                                    <p class="text-xs text-gray-600 mt-1">Pendientes</p>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-purple-100">
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Competencias</p>
                                    <p class="text-2xl font-bold text-purple-900">{{ $profileStats['total_competencies'] }}</p>
                                    <p class="text-xs text-gray-600 mt-1">Total evaluadas</p>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-amber-100">
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Certificaciones</p>
                                    <p class="text-2xl font-bold text-amber-900">{{ $profileStats['total_certifications'] }}</p>
                                    <p class="text-xs text-gray-600 mt-1">Total obtenidas</p>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-pink-100">
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Avatares</p>
                                    <p class="text-2xl font-bold text-pink-900">{{ $profileStats['users_with_avatar'] }}/{{ $organizationStats['total_people'] ?? 0 }}</p>
                                    <p class="text-xs text-gray-600 mt-1">Con foto</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Perfiles del Equipo</h3>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-500">Ordenar por:</span>
                                <select x-model="profileSort" 
                                        class="text-xs border border-gray-300 rounded-lg px-2 py-1 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="completion">Completitud</option>
                                    <option value="name">Nombre</option>
                                    <option value="role">Rol</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" 
                             x-data="{ profileSort: 'completion' }"
                             x-init="$watch('profileSort', () => {})">
                            @foreach($teamUsers->sortByDesc('profile_completion_percent') as $user)
                            <div class="bg-white rounded-xl border-2 {{ $user->profile_completion_percent >= 100 ? 'border-green-300' : ($user->profile_completion_percent >= 70 ? 'border-yellow-300' : 'border-red-300') }} p-4 hover:shadow-lg transition-all">
                                <div class="flex items-start gap-4">
                                    <!-- Avatar -->
                                    <a href="{{ route('profile.show', $user) }}" class="flex-shrink-0">
                                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-red-500 to-orange-500 flex items-center justify-center text-xl font-bold text-white border-2 border-white shadow-lg overflow-hidden">
                                            @if($user->avatar_url)
                                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                            @else
                                                {{ $user->initials() }}
                                            @endif
                                        </div>
                                    </a>
                                    
                                    <!-- Información -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between mb-2">
                                            <div>
                                                <a href="{{ route('profile.show', $user) }}" class="text-base font-bold text-gray-900 hover:text-red-600 transition-colors">
                                                    {{ $user->name }}
                                                </a>
                                                @if($user->internalRole)
                                                    <p class="text-xs text-gray-600 mt-0.5">{{ $user->internalRole->name }}</p>
                                                @endif
                                            </div>
                                            <span class="px-2 py-1 text-[10px] font-bold rounded-full {{ $user->profile_completion_percent >= 100 ? 'bg-green-100 text-green-800' : ($user->profile_completion_percent >= 70 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ $user->profile_completion_percent ?? 0 }}%
                                            </span>
                                        </div>
                                        
                                        <!-- Barra de progreso -->
                                        <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
                                            <div class="h-2 rounded-full transition-all {{ $user->profile_completion_percent >= 100 ? 'bg-gradient-to-r from-green-500 to-green-600' : ($user->profile_completion_percent >= 70 ? 'bg-gradient-to-r from-yellow-500 to-yellow-600' : 'bg-gradient-to-r from-red-500 to-red-600') }}" 
                                                 style="width: {{ $user->profile_completion_percent ?? 0 }}%"></div>
                                        </div>
                                        
                                        <!-- Métricas rápidas -->
                                        <div class="grid grid-cols-3 gap-2 text-center">
                                            <div class="bg-purple-50 rounded-lg p-2">
                                                <p class="text-xs font-bold text-purple-900">{{ $user->competencies->count() }}</p>
                                                <p class="text-[10px] text-purple-600">Competencias</p>
                                            </div>
                                            <div class="bg-amber-50 rounded-lg p-2">
                                                <p class="text-xs font-bold text-amber-900">{{ $user->userCertifications->count() }}</p>
                                                <p class="text-[10px] text-amber-600">Certificaciones</p>
                                            </div>
                                            <div class="bg-blue-50 rounded-lg p-2">
                                                <p class="text-xs font-bold text-blue-900">
                                                    @if($user->avatar_url) ✅ @else ❌ @endif
                                                </p>
                                                <p class="text-[10px] text-blue-600">Avatar</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Acciones -->
                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            <a href="{{ route('profile.show', $user) }}" 
                                               class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Ver perfil completo
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
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

    @if($isInternalPlan && $activeTab === 'competencies')
    <!-- Tab Content: Competencias -->
    <div class="mt-6">
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
    @endif

    @if($isInternalPlan && $activeTab === 'infrastructure')
    <!-- Tab Content: Infraestructura Técnica -->
    <div class="mt-6">
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
    @endif

    @if($isInternalPlan && $activeTab === 'certifications')
    <!-- Tab Content: Certificaciones -->
    <div class="mt-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-amber-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                        🏆
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Certificaciones</h2>
                        <p class="text-xs text-gray-500">
                            Gestión de certificaciones, roadmap personalizado y gamificación.
                        </p>
                    </div>
                </div>
            </div>
            @livewire('plans.plan-desarrollo-certificaciones', ['plan' => $plan], key('certifications-' . $plan->id))
        </div>
    </div>
    @endif

    @if($isInternalPlan && $activeTab === 'innovation-tooling')
    <!-- Tab Content: I+D y Tooling Interno -->
    <div class="mt-6">
        @livewire('plans.plan-desarrollo-i-d-tooling', ['plan' => $plan])
    </div>
    @endif
    
    @if($isInternalPlan && $activeTab === 'team-culture')
    <!-- Tab Content: Cultura de Equipo y Liderazgo -->
    <div class="mt-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-pink-500">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-purple-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                        🤝
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Cultura de equipo y liderazgo</h2>
                        <p class="text-xs text-gray-500">Rutinas de sincronía, crecimiento técnico y principios culturales</p>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-xl border border-pink-200 p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-lg">🗓️</span>
                        <h3 class="text-sm font-bold text-gray-900">Rutinas de sincronía</h3>
                    </div>
                    <ul class="space-y-2 text-xs text-gray-700">
                        <li><span class="font-semibold text-pink-600">Daily Ops (15’)</span> — foco en bloqueos críticos y cobertura operativa.</li>
                        <li><span class="font-semibold text-pink-600">Weekly Replan (30’)</span> — revisión de KPIs de ejecución y reasignación.</li>
                        <li><span class="font-semibold text-pink-600">Monthly Deep Dive</span> — retrospectiva táctico/estratégica (roadmap, riesgos, staffing).</li>
                    </ul>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200 p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-lg">🧪</span>
                        <h3 class="text-sm font-bold text-gray-900">Días técnicos / I+D Days</h3>
                    </div>
                    <p class="text-xs text-gray-600 mb-3">Bloques específicos para innovación y mejora interna:</p>
                    <ul class="space-y-2 text-xs text-gray-700">
                        <li>1 día al mes reservado para research & tooling propio.</li>
                        <li>Showcase interno de resultados (demo lightning de 10’).</li>
                        <li>Repositorio de aprendizajes y propuestas de producto.</li>
                    </ul>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-lg">💬</span>
                        <h3 class="text-sm font-bold text-gray-900">Feedback 360 interno</h3>
                    </div>
                    <ul class="space-y-2 text-xs text-gray-700">
                        <li><span class="font-semibold text-gray-900">Quarterly 360:</span> managers ↔ ICs, managers ↔ managers.</li>
                        <li><span class="font-semibold text-gray-900">Peer-review técnico:</span> checklist de code quality / OPSEC.</li>
                        <li><span class="font-semibold text-gray-900">Radar de capacidades:</span> visión clara de gaps y objetivos personales.</li>
                    </ul>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-lg">🌟</span>
                        <h3 class="text-sm font-bold text-gray-900">Principios culturales del departamento</h3>
                    </div>
                    <ol class="space-y-2 text-xs text-gray-700 list-decimal pl-4">
                        <li><span class="font-semibold text-gray-900">Excelencia operativa primero:</span> la calidad mínima es la calidad de producción.</li>
                        <li><span class="font-semibold text-gray-900">Compartir antes que competir:</span> toda mejora se documenta y comparte.</li>
                        <li><span class="font-semibold text-gray-900">Seguridad es responsabilidad de todos:</span> OPSEC en cada entrega.</li>
                        <li><span class="font-semibold text-gray-900">Aprender cada semana:</span> pequeños ciclos de mejora continua.</li>
                        <li><span class="font-semibold text-gray-900">Feedback honesto y accionable:</span> sin sorpresas en las evaluaciones.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    @if($isInternalPlan && $activeTab === 'operational-roadmap')
    <!-- Tab Content: Roadmap -->
    <div class="mt-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-teal-500">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                        🗓️
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Roadmap</h2>
                        <p class="text-xs text-gray-500">Vista Gantt de hitos operativos y dependencias</p>
                    </div>
                </div>
            </div>
            @livewire('plans.plan-desarrollo-roadmap-operativo', ['plan' => $plan], key('operational-roadmap-' . $plan->id))
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
    
    <!-- Tab Content: Sectorial (Solo para Planes Comerciales) -->
    @if($isCommercialPlan && $activeTab === 'sectorial')
    <div class="mt-6">
        @livewire('plans.plan-sector-analysis', ['plan' => $plan], key('sector-analysis-' . $plan->id))
    </div>
    @endif
</div>
@endsection


