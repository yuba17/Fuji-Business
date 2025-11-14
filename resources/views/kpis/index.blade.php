@extends('layouts.dashboard')

@section('title', 'KPIs')

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
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">KPIs</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('header-actions')
@if(auth()->user()->isDirector() || auth()->user()->isManager())
    <x-ui.button href="{{ route('kpis.create') }}" variant="primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Crear KPI
    </x-ui.button>
@endif
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words">
        📈 KPIs
    </h1>
    <p class="text-gray-600 mt-2">Indicadores clave de rendimiento</p>
</div>

<!-- Filtros -->
<div class="mb-6">
    <x-ui.card variant="compact">
        <form method="GET" action="{{ route('kpis.index') }}" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <x-ui.input 
                    type="search" 
                    placeholder="Buscar KPIs..." 
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
                <x-ui.select name="status" label="Estado">
                    <option value="">Todos los estados</option>
                    <option value="green" {{ request('status') == 'green' ? 'selected' : '' }}>✅ Verde</option>
                    <option value="yellow" {{ request('status') == 'yellow' ? 'selected' : '' }}>⚠️ Amarillo</option>
                    <option value="red" {{ request('status') == 'red' ? 'selected' : '' }}>❌ Rojo</option>
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

<!-- Lista de KPIs -->
@if($kpis->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($kpis as $kpi)
            <x-ui.card class="hover:shadow-xl transition-shadow cursor-pointer" onclick="window.location.href='{{ route('kpis.show', $kpi) }}'">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $kpi->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $kpi->plan->name ?? 'Sin plan' }}</p>
                    </div>
                    <x-ui.badge 
                        variant="{{ $kpi->status === 'green' ? 'success' : ($kpi->status === 'yellow' ? 'warning' : 'error') }}">
                        {{ ucfirst($kpi->status) }}
                    </x-ui.badge>
                </div>
                
                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $kpi->description }}</p>
                
                <!-- Progreso -->
                <div class="mb-4">
                    <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                        <span>Progreso</span>
                        <span class="font-bold">{{ $kpi->percentage ? number_format($kpi->percentage, 1) : '0' }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-red-600 to-orange-600 h-2 rounded-full" 
                             style="width: {{ min($kpi->percentage ?? 0, 100) }}%"></div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between text-xs text-gray-500 pt-4 border-t border-gray-200">
                    <div>
                        <span class="font-semibold">{{ $kpi->current_value ?? 0 }}</span>
                        <span class="text-gray-400">/ {{ $kpi->target_value }} {{ $kpi->unit }}</span>
                    </div>
                    <a href="{{ route('kpis.show', $kpi) }}" 
                       class="text-xs font-medium text-red-600 hover:text-red-700">
                        Ver →
                    </a>
                </div>
            </x-ui.card>
        @endforeach
    </div>
    
    <!-- Paginación -->
    <div class="mt-6">
        {{ $kpis->links() }}
    </div>
@else
    <x-ui.card>
        <div class="text-center py-12 text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <p class="text-sm mb-4">No hay KPIs creados aún</p>
            @if(auth()->user()->isDirector() || auth()->user()->isManager())
                <x-ui.button href="{{ route('kpis.create') }}" variant="primary">
                    Crear Primer KPI
                </x-ui.button>
            @endif
        </div>
    </x-ui.card>
@endif
@endsection

