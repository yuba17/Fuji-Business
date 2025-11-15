@extends('layouts.dashboard')

@section('title', 'Panel Corporativo de Riesgos')

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
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Panel Corporativo</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('header-actions')
<div class="flex gap-2">
    <a href="{{ route('risks.matrix') }}" class="inline-block">
        <x-ui.button variant="secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Matriz de Riesgos
        </x-ui.button>
    </a>
</div>
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words">
        🏢 Panel Corporativo de Riesgos
    </h1>
    <p class="text-gray-600 mt-2">Vista consolidada de todos los riesgos de la organización</p>
</div>

@php
    $riskService = new \App\Services\RiskCalculationService();
    $distribution = $riskService->getRiskDistribution();
    $byStrategy = $riskService->getRisksByStrategy();
    $criticalRisks = $riskService->getCriticalRisks();
    $totalCost = $riskService->calculateTotalMitigationCost();
@endphp

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <x-ui.card variant="gradient" border-color="red">
        <div class="text-center">
            <p class="text-red-100 text-xs font-medium uppercase tracking-wide">Críticos</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ $distribution['critico'] }}</p>
        </div>
    </x-ui.card>
    
    <x-ui.card variant="gradient" border-color="orange">
        <div class="text-center">
            <p class="text-orange-100 text-xs font-medium uppercase tracking-wide">Altos</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ $distribution['alto'] }}</p>
        </div>
    </x-ui.card>
    
    <x-ui.card variant="gradient" border-color="blue">
        <div class="text-center">
            <p class="text-blue-100 text-xs font-medium uppercase tracking-wide">Total</p>
            <p class="text-3xl font-bold mt-1 text-white">{{ $distribution['total'] }}</p>
        </div>
    </x-ui.card>
    
    <x-ui.card variant="gradient" border-color="green">
        <div class="text-center">
            <p class="text-green-100 text-xs font-medium uppercase tracking-wide">Costo Mitigación</p>
            <p class="text-2xl font-bold mt-1 text-white">${{ number_format($totalCost, 2) }}</p>
        </div>
    </x-ui.card>
</div>

<!-- Distribución por Categoría -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <x-ui.card>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Distribución por Categoría</h2>
        <div class="space-y-3">
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Crítico</span>
                    <span class="font-semibold text-red-600">{{ $distribution['critico'] }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-red-600 h-3 rounded-full" style="width: {{ $distribution['total'] > 0 ? ($distribution['critico'] / $distribution['total'] * 100) : 0 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Alto</span>
                    <span class="font-semibold text-orange-600">{{ $distribution['alto'] }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-orange-500 h-3 rounded-full" style="width: {{ $distribution['total'] > 0 ? ($distribution['alto'] / $distribution['total'] * 100) : 0 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Medio</span>
                    <span class="font-semibold text-yellow-600">{{ $distribution['medio'] }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-yellow-400 h-3 rounded-full" style="width: {{ $distribution['total'] > 0 ? ($distribution['medio'] / $distribution['total'] * 100) : 0 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Bajo</span>
                    <span class="font-semibold text-green-600">{{ $distribution['bajo'] }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-400 h-3 rounded-full" style="width: {{ $distribution['total'] > 0 ? ($distribution['bajo'] / $distribution['total'] * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </x-ui.card>
    
    <x-ui.card>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Distribución por Estrategia</h2>
        <div class="space-y-3">
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Mitigar</span>
                    <span class="font-semibold text-gray-900">{{ $byStrategy['mitigate'] }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-blue-500 h-3 rounded-full" style="width: {{ array_sum($byStrategy) > 0 ? ($byStrategy['mitigate'] / array_sum($byStrategy) * 100) : 0 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Evitar</span>
                    <span class="font-semibold text-gray-900">{{ $byStrategy['avoid'] }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-red-500 h-3 rounded-full" style="width: {{ array_sum($byStrategy) > 0 ? ($byStrategy['avoid'] / array_sum($byStrategy) * 100) : 0 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Transferir</span>
                    <span class="font-semibold text-gray-900">{{ $byStrategy['transfer'] }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-yellow-500 h-3 rounded-full" style="width: {{ array_sum($byStrategy) > 0 ? ($byStrategy['transfer'] / array_sum($byStrategy) * 100) : 0 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Aceptar</span>
                    <span class="font-semibold text-gray-900">{{ $byStrategy['accept'] }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-500 h-3 rounded-full" style="width: {{ array_sum($byStrategy) > 0 ? ($byStrategy['accept'] / array_sum($byStrategy) * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </x-ui.card>
</div>

<!-- Riesgos Críticos -->
<x-ui.card>
    <h2 class="text-xl font-bold text-gray-800 mb-4">Riesgos Críticos y de Alto Nivel</h2>
    @if($criticalRisks->count() > 0)
        <div class="space-y-4">
            @foreach($criticalRisks as $risk)
                <div class="border-l-4 border-{{ $risk->category_color }}-500 pl-4 py-3 bg-gray-50 rounded-r-lg">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900">
                                <a href="{{ route('risks.show', $risk) }}" class="hover:text-red-600 transition-colors">
                                    {{ $risk->name }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $risk->description }}</p>
                            <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                <span>Probabilidad: {{ $risk->probability }}/5</span>
                                <span>Impacto: {{ $risk->impact }}/5</span>
                                <span>Nivel: {{ $risk->risk_level }}</span>
                                @if($risk->plan)
                                    <span>Plan: {{ $risk->plan->name }}</span>
                                @endif
                            </div>
                        </div>
                        <x-ui.badge 
                            variant="{{ $risk->category === 'critico' ? 'error' : 'warning' }}"
                        >
                            {{ ucfirst($risk->category) }}
                        </x-ui.badge>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-500 text-center py-8">No hay riesgos críticos o de alto nivel</p>
    @endif
</x-ui.card>
@endsection


