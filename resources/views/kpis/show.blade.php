@extends('layouts.dashboard')

@section('title', $kpi->name)

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
                <a href="{{ route('kpis.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 md:ml-2">KPIs</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $kpi->name }}</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('header-actions')
@if(auth()->user()->isDirector() || auth()->user()->isManager())
    <x-ui.button href="{{ route('kpis.edit', $kpi) }}" variant="secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Editar
    </x-ui.button>
@endif
@endsection

@section('content')
<!-- Header del KPI -->
<div class="mb-8">
    <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
            <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words mb-2">
                {{ $kpi->name }}
            </h1>
            <p class="text-gray-600">{{ $kpi->description }}</p>
        </div>
        <x-ui.badge 
            variant="{{ $kpi->status === 'green' ? 'success' : ($kpi->status === 'yellow' ? 'warning' : 'error') }}">
            {{ ucfirst($kpi->status) }}
        </x-ui.badge>
    </div>
    
    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
        @if($kpi->plan)
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>{{ $kpi->plan->name }}</span>
            </div>
        @endif
        @if($kpi->area)
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>{{ $kpi->area->name }}</span>
            </div>
        @endif
        @if($kpi->responsible)
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Responsable: {{ $kpi->responsible->name }}</span>
            </div>
        @endif
    </div>
</div>

<!-- Métricas Principales -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <x-ui.card variant="gradient" border-color="blue">
        <div class="text-center">
            <p class="text-blue-100 text-xs font-medium uppercase tracking-wide">Valor Actual</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ number_format($kpi->current_value ?? 0, 2) }} {{ $kpi->unit }}</p>
        </div>
    </x-ui.card>
    
    <x-ui.card variant="gradient" border-color="green">
        <div class="text-center">
            <p class="text-green-100 text-xs font-medium uppercase tracking-wide">Valor Objetivo</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ number_format($kpi->target_value, 2) }} {{ $kpi->unit }}</p>
        </div>
    </x-ui.card>
    
    <x-ui.card variant="gradient" border-color="orange">
        <div class="text-center">
            <p class="text-orange-100 text-xs font-medium uppercase tracking-wide">Progreso</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ $kpi->percentage ? number_format($kpi->percentage, 1) : '0' }}%</p>
        </div>
    </x-ui.card>
</div>

<!-- Progreso Visual -->
<x-ui.card class="mb-8">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Progreso</h2>
    <div class="mb-2">
        <div class="flex items-center justify-between text-sm text-gray-600 mb-1">
            <span>Progreso hacia el objetivo</span>
            <span class="font-bold">{{ $kpi->percentage ? number_format($kpi->percentage, 1) : '0' }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-4">
            <div class="bg-gradient-to-r from-red-600 to-orange-600 h-4 rounded-full transition-all duration-500" 
                 style="width: {{ min($kpi->percentage ?? 0, 100) }}%"></div>
        </div>
    </div>
    <div class="flex items-center justify-between text-xs text-gray-500 mt-2">
        <span>0 {{ $kpi->unit }}</span>
        <span>{{ number_format($kpi->target_value, 2) }} {{ $kpi->unit }}</span>
    </div>
</x-ui.card>

<!-- Información Detallada -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <x-ui.card>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Información General</h2>
        <dl class="space-y-3">
            <div>
                <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $kpi->type ?? 'No especificado' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Frecuencia de Actualización</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $kpi->update_frequency ?? 'No especificada' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $kpi->last_updated_at ? $kpi->last_updated_at->format('d/m/Y H:i') : 'Nunca' }}</dd>
            </div>
            @if($kpi->threshold_green)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Umbral Verde</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $kpi->threshold_green }}%</dd>
                </div>
            @endif
            @if($kpi->threshold_yellow)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Umbral Amarillo</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $kpi->threshold_yellow }}%</dd>
                </div>
            @endif
        </dl>
    </x-ui.card>
    
    <x-ui.card>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Historial</h2>
        @if($kpi->history->count() > 0)
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @foreach($kpi->history->take(10) as $history)
                    <div class="border-l-4 border-blue-500 pl-4 py-2">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ number_format($history->value, 2) }} {{ $kpi->unit }}</p>
                                <p class="text-xs text-gray-500">{{ $history->recorded_at->format('d/m/Y H:i') }}</p>
                            </div>
                            @if($history->updater)
                                <p class="text-xs text-gray-500">{{ $history->updater->name }}</p>
                            @endif
                        </div>
                        @if($history->notes)
                            <p class="text-xs text-gray-600 mt-1">{{ $history->notes }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 text-center py-8">No hay historial registrado</p>
        @endif
    </x-ui.card>
</div>
@endsection

