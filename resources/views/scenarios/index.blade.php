@extends('layouts.dashboard')

@section('title', 'Escenarios - ' . $plan->name)

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
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('plans.index') }}" class="ml-1 text-sm font-medium text-gray-500 md:ml-2 hover:text-red-600">Planes</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('plans.show', $plan) }}" class="ml-1 text-sm font-medium text-gray-500 md:ml-2 hover:text-red-600">{{ $plan->name }}</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Escenarios</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent break-words">
                🎯 Escenarios - {{ $plan->name }}
            </h1>
            <p class="text-gray-600 mt-2">Simula diferentes escenarios para este plan estratégico.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('plans.show', $plan) }}" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border border-gray-200 transition-all">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver al Plan
            </a>
            @can('update', $plan)
            <a href="{{ route('scenarios.create', $plan) }}" class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Escenario
            </a>
            @endcan
        </div>
    </div>
</div>

@if($scenarios->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($scenarios as $scenario)
            <x-ui.card class="hover:shadow-xl transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $scenario->name }}</h3>
                        @if($scenario->description)
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($scenario->description, 150) }}</p>
                        @endif
                    </div>
                    @if($scenario->is_applied)
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">✅ Aplicado</span>
                    @endif
                </div>

                <div class="mb-4 space-y-2">
                    @if(isset($scenario->simulation_params['budget_change']))
                        <div class="flex items-center gap-2 text-sm">
                            <span class="text-gray-600">💰 Presupuesto:</span>
                            <span class="font-semibold {{ $scenario->simulation_params['budget_change'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $scenario->simulation_params['budget_change'] > 0 ? '+' : '' }}{{ $scenario->simulation_params['budget_change'] }}%
                            </span>
                        </div>
                    @endif
                    @if(isset($scenario->simulation_params['team_change']))
                        <div class="flex items-center gap-2 text-sm">
                            <span class="text-gray-600">👥 Equipo:</span>
                            <span class="font-semibold {{ $scenario->simulation_params['team_change'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $scenario->simulation_params['team_change'] > 0 ? '+' : '' }}{{ $scenario->simulation_params['team_change'] }} personas
                            </span>
                        </div>
                    @endif
                    @if(isset($scenario->simulation_params['delay_days']))
                        <div class="flex items-center gap-2 text-sm">
                            <span class="text-gray-600">⏱️ Retraso:</span>
                            <span class="font-semibold {{ $scenario->simulation_params['delay_days'] == 0 ? 'text-green-600' : 'text-orange-600' }}">
                                {{ $scenario->simulation_params['delay_days'] }} días
                            </span>
                        </div>
                    @endif
                </div>

                @if($scenario->results && isset($scenario->results['overall_impact_score']))
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Impacto General:</span>
                            <span class="text-lg font-bold {{ $scenario->results['overall_impact_score'] < 5 ? 'text-green-600' : ($scenario->results['overall_impact_score'] < 10 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $scenario->results['overall_impact_score'] }}/20
                            </span>
                        </div>
                    </div>
                @endif

                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="text-xs text-gray-500">
                        Creado por {{ $scenario->creator->name ?? 'N/A' }}<br>
                        {{ $scenario->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('scenarios.show', [$plan, $scenario]) }}" 
                           class="px-4 py-2 text-sm font-semibold text-purple-700 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                            Ver Detalles
                        </a>
                        @can('update', $plan)
                        <form action="{{ route('scenarios.destroy', [$plan, $scenario]) }}" method="POST" class="inline" 
                              onsubmit="return confirm('¿Estás seguro de eliminar este escenario?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-sm font-semibold text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                Eliminar
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            </x-ui.card>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $scenarios->links() }}
    </div>
@else
    <x-ui.card>
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay escenarios creados</h3>
            <p class="text-sm text-gray-600 mb-6">Crea tu primer escenario para simular diferentes situaciones.</p>
            @can('update', $plan)
            <a href="{{ route('scenarios.create', $plan) }}" class="inline-block px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Crear Primer Escenario
            </a>
            @endcan
        </div>
    </x-ui.card>
@endif
@endsection

