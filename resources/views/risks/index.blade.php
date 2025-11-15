@extends('layouts.dashboard')

@section('title', 'Riesgos')

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
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Riesgos</span>
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
            Matriz
        </x-ui.button>
    </a>
    @if(auth()->user()->isDirector() || auth()->user()->isManager())
        <x-ui.button href="{{ route('risks.create') }}" variant="primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Crear Riesgo
        </x-ui.button>
    @endif
</div>
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words">
        ⚠️ Riesgos
    </h1>
    <p class="text-gray-600 mt-2">Gestión de riesgos y mitigaciones</p>
</div>

<!-- Filtros -->
<div class="mb-6">
    <x-ui.card variant="compact">
        <form method="GET" action="{{ route('risks.index') }}" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <x-ui.input 
                    type="search" 
                    placeholder="Buscar riesgos..." 
                    name="search"
                    value="{{ request('search') }}" />
            </div>
            <div class="w-full sm:w-auto">
                <x-ui.select name="plan_id" label="Plan">
                    <option value="">Todos los planes</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }}
                        </option>
                    @endforeach
                </x-ui.select>
            </div>
            <div class="w-full sm:w-auto">
                <x-ui.select name="category" label="Categoría">
                    <option value="">Todas las categorías</option>
                    <option value="critico" {{ request('category') == 'critico' ? 'selected' : '' }}>🔴 Crítico</option>
                    <option value="alto" {{ request('category') == 'alto' ? 'selected' : '' }}>🟠 Alto</option>
                    <option value="medio" {{ request('category') == 'medio' ? 'selected' : '' }}>🟡 Medio</option>
                    <option value="bajo" {{ request('category') == 'bajo' ? 'selected' : '' }}>🟢 Bajo</option>
                </x-ui.select>
            </div>
            <div class="w-full sm:w-auto">
                <x-ui.button variant="secondary" type="submit">
                    Filtrar
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>
</div>

<!-- Lista de Riesgos -->
@if($risks->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($risks as $risk)
            @php
                $borderColor = match($risk->category) {
                    'critico' => 'border-red-500',
                    'alto' => 'border-orange-500',
                    'medio' => 'border-yellow-500',
                    'bajo' => 'border-green-500',
                    default => 'border-gray-500',
                };
            @endphp
            <x-ui.card class="hover:shadow-xl transition-shadow cursor-pointer border-l-4 {{ $borderColor }}" onclick="window.location.href='{{ route('risks.show', $risk) }}'">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $risk->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $risk->plan->name ?? 'Sin plan' }}</p>
                    </div>
                    <x-ui.badge 
                        variant="{{ $risk->category === 'critico' ? 'error' : ($risk->category === 'alto' ? 'warning' : ($risk->category === 'medio' ? 'info' : 'success')) }}">
                        {{ ucfirst($risk->category) }}
                    </x-ui.badge>
                </div>
                
                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $risk->description }}</p>
                
                <div class="flex items-center justify-between text-xs text-gray-500 pt-4 border-t border-gray-200">
                    <div class="flex items-center gap-4">
                        <span>Probabilidad: {{ $risk->probability }}/5</span>
                        <span>Impacto: {{ $risk->impact }}/5</span>
                        <span class="font-semibold">Nivel: {{ $risk->risk_level }}/25</span>
                    </div>
                    <a href="{{ route('risks.show', $risk) }}" 
                       class="text-xs font-medium text-red-600 hover:text-red-700">
                        Ver →
                    </a>
                </div>
            </x-ui.card>
        @endforeach
    </div>
    
    <!-- Paginación -->
    <div class="mt-6">
        {{ $risks->links() }}
    </div>
@else
    <x-ui.card>
        <div class="text-center py-12 text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p class="text-sm mb-4">No hay riesgos identificados aún</p>
            @if(auth()->user()->isDirector() || auth()->user()->isManager())
                <x-ui.button href="{{ route('risks.create') }}" variant="primary">
                    Crear Primer Riesgo
                </x-ui.button>
            @endif
        </div>
    </x-ui.card>
@endif
@endsection

