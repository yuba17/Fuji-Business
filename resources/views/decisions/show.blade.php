@extends('layouts.dashboard')

@section('title', $decision->title)

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
                <a href="{{ route('decisions.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 md:ml-2">Decisiones</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $decision->title }}</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('header-actions')
@if(auth()->user()->isDirector())
    <x-ui.button href="{{ route('decisions.edit', $decision) }}" variant="secondary">
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
                {{ $decision->title }}
            </h1>
            @if($decision->description)
                <p class="text-gray-600">{{ $decision->description }}</p>
            @endif
        </div>
        <x-ui.badge 
            variant="{{ $decision->status === 'approved' ? 'success' : ($decision->status === 'rejected' ? 'error' : ($decision->status === 'implemented' ? 'info' : 'warning')) }}">
            {{ $decision->status_label }}
        </x-ui.badge>
    </div>
    
    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
        @if($decision->proponent)
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Propuesta por: {{ $decision->proponent->name }}</span>
            </div>
        @endif
        @if($decision->decision_date)
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Fecha: {{ $decision->decision_date->format('d/m/Y') }}</span>
            </div>
        @endif
    </div>
</div>

<!-- Información Detallada -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <x-ui.card>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Información General</h2>
        <dl class="space-y-3">
            @if($decision->impact_type)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tipo de Impacto</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($decision->impact_type) }}</dd>
                </div>
            @endif
            @if($decision->alternatives_considered)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Alternativas Consideradas</dt>
                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $decision->alternatives_considered }}</dd>
                </div>
            @endif
            @if($decision->rationale)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Razonamiento</dt>
                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $decision->rationale }}</dd>
                </div>
            @endif
            @if($decision->expected_impact)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Impacto Esperado</dt>
                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $decision->expected_impact }}</dd>
                </div>
            @endif
        </dl>
    </x-ui.card>
    
    @if($decision->plans->count() > 0)
        <x-ui.card>
            <h2 class="text-xl font-bold text-gray-800 mb-4">Planes Relacionados</h2>
            <div class="space-y-2">
                @foreach($decision->plans as $plan)
                    <div class="border-l-4 border-blue-500 pl-4 py-2">
                        <h3 class="font-semibold text-gray-900">{{ $plan->name }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $plan->planType->name ?? 'Sin tipo' }}</p>
                        <a href="{{ route('plans.show', $plan) }}" class="text-xs text-red-600 hover:text-red-700 mt-1 inline-block">
                            Ver plan →
                        </a>
                    </div>
                @endforeach
            </div>
        </x-ui.card>
    @endif
</div>
@endsection

