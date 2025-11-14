@extends('layouts.dashboard')

@section('title', $risk->name)

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
                <a href="{{ route('risks.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 md:ml-2">Riesgos</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $risk->name }}</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('header-actions')
@if(auth()->user()->isDirector() || auth()->user()->isManager())
    <x-ui.button href="{{ route('risks.edit', $risk) }}" variant="secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Editar
    </x-ui.button>
@endif
@endsection

@section('content')
<div class="mb-8">
    <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
            <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words mb-2">
                {{ $risk->name }}
            </h1>
            <p class="text-gray-600">{{ $risk->description }}</p>
        </div>
        @php
            $borderColor = match($risk->category) {
                'critico' => 'border-red-500',
                'alto' => 'border-orange-500',
                'medio' => 'border-yellow-500',
                'bajo' => 'border-green-500',
                default => 'border-gray-500',
            };
        @endphp
        <x-ui.badge 
            variant="{{ $risk->category === 'critico' ? 'error' : ($risk->category === 'alto' ? 'warning' : ($risk->category === 'medio' ? 'info' : 'success')) }}">
            {{ ucfirst($risk->category) }}
        </x-ui.badge>
    </div>
</div>

<!-- Métricas del Riesgo -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <x-ui.card variant="gradient" border-color="red">
        <div class="text-center">
            <p class="text-red-100 text-xs font-medium uppercase tracking-wide">Probabilidad</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ $risk->probability }}/5</p>
        </div>
    </x-ui.card>
    
    <x-ui.card variant="gradient" border-color="orange">
        <div class="text-center">
            <p class="text-orange-100 text-xs font-medium uppercase tracking-wide">Impacto</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ $risk->impact }}/5</p>
        </div>
    </x-ui.card>
    
    <x-ui.card variant="gradient" border-color="purple">
        <div class="text-center">
            <p class="text-purple-100 text-xs font-medium uppercase tracking-wide">Nivel de Riesgo</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ $risk->risk_level }}/25</p>
        </div>
    </x-ui.card>
</div>

<!-- Información Detallada -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <x-ui.card>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Información General</h2>
        <dl class="space-y-3">
            @if($risk->plan)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Plan</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $risk->plan->name }}</dd>
                </div>
            @endif
            @if($risk->area)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Área</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $risk->area->name }}</dd>
                </div>
            @endif
            @if($risk->owner)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Propietario</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $risk->owner->name }}</dd>
                </div>
            @endif
            @if($risk->strategy)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Estrategia</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $risk->strategy_label }}</dd>
                </div>
            @endif
            <div>
                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($risk->status) }}</dd>
            </div>
        </dl>
    </x-ui.card>
    
    @if($risk->mitigationActions->count() > 0)
        <x-ui.card>
            <h2 class="text-xl font-bold text-gray-800 mb-4">Acciones de Mitigación</h2>
            <div class="space-y-3">
                @foreach($risk->mitigationActions as $action)
                    <div class="border-l-4 border-blue-500 pl-4 py-2">
                        <h3 class="font-semibold text-gray-900">{{ $action->action }}</h3>
                        @if($action->description)
                            <p class="text-sm text-gray-600 mt-1">{{ $action->description }}</p>
                        @endif
                        <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
                            @if($action->responsible)
                                <span>Responsable: {{ $action->responsible->name }}</span>
                            @endif
                            @if($action->target_date)
                                <span>Objetivo: {{ $action->target_date->format('d/m/Y') }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </x-ui.card>
    @endif
</div>
@endsection

