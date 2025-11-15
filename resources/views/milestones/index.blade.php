@extends('layouts.dashboard')

@section('title', 'Hitos: ' . $plan->name)

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
                <a href="{{ route('plans.show', $plan) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 md:ml-2">{{ $plan->name }}</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Hitos</span>
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
            Ver Roadmap
        </x-ui.button>
    </a>
    @can('update', $plan)
        <x-ui.button href="{{ route('plans.milestones.create', $plan) }}" variant="primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo Hito
        </x-ui.button>
    @endcan
</div>
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words">
        🎯 Hitos: {{ $plan->name }}
    </h1>
    <p class="text-gray-600 mt-2">Lista de hitos del plan</p>
</div>

<div class="space-y-4">
    @forelse($milestones as $milestone)
        <x-ui.card>
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-lg font-bold text-gray-900">
                            <a 
                                href="{{ route('plans.milestones.show', [$plan, $milestone]) }}"
                                class="hover:text-red-600 transition-colors"
                            >
                                {{ $milestone->name }}
                            </a>
                        </h3>
                        <x-ui.badge 
                            variant="{{ $milestone->status === 'completed' ? 'success' : ($milestone->status === 'delayed' ? 'error' : ($milestone->status === 'in_progress' ? 'warning' : 'info')) }}"
                        >
                            {{ $milestone->status_label }}
                        </x-ui.badge>
                        @if($milestone->isDelayed())
                            <x-ui.badge variant="error">⚠️ Retrasado</x-ui.badge>
                        @endif
                    </div>
                    
                    @if($milestone->description)
                        <p class="text-sm text-gray-600 mb-3">{{ $milestone->description }}</p>
                    @endif
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Inicio:</span>
                            <span class="font-medium text-gray-900 ml-1">
                                {{ $milestone->start_date ? $milestone->start_date->format('d/m/Y') : '-' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-500">Objetivo:</span>
                            <span class="font-medium text-gray-900 ml-1">
                                {{ $milestone->target_date->format('d/m/Y') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-500">Progreso:</span>
                            <span class="font-bold text-gray-900 ml-1">{{ $milestone->progress_percentage }}%</span>
                        </div>
                        @if($milestone->responsible)
                            <div>
                                <span class="text-gray-500">Responsable:</span>
                                <span class="font-medium text-gray-900 ml-1">{{ $milestone->responsible->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="flex gap-2 ml-4">
                    @can('update', $plan)
                        <a 
                            href="{{ route('plans.milestones.edit', [$plan, $milestone]) }}"
                            class="p-2 text-gray-400 hover:text-gray-600 transition-colors"
                            title="Editar"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                    @endcan
                </div>
            </div>
        </x-ui.card>
    @empty
        <x-ui.card>
            <div class="text-center py-12 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <p class="text-sm">No hay hitos definidos para este plan</p>
                @can('update', $plan)
                    <a 
                        href="{{ route('plans.milestones.create', $plan) }}"
                        class="mt-4 inline-block px-4 py-2 text-sm font-semibold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700"
                    >
                        Crear Primer Hito
                    </a>
                @endcan
            </div>
        </x-ui.card>
    @endforelse
</div>
@endsection


