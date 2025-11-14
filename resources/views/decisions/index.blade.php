@extends('layouts.dashboard')

@section('title', 'Decisiones')

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
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Decisiones</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('header-actions')
@if(auth()->user()->isDirector())
    <x-ui.button href="{{ route('decisions.create') }}" variant="primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Crear Decisión
    </x-ui.button>
@endif
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words">
        📋 Decisiones
    </h1>
    <p class="text-gray-600 mt-2">Registro de decisiones estratégicas y operativas</p>
</div>

<!-- Filtros -->
<div class="mb-6">
    <x-ui.card variant="compact">
        <form method="GET" action="{{ route('decisions.index') }}" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <x-ui.input 
                    type="search" 
                    placeholder="Buscar decisiones..." 
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
                    <option value="proposed" {{ request('status') == 'proposed' ? 'selected' : '' }}>Propuesta</option>
                    <option value="discussion" {{ request('status') == 'discussion' ? 'selected' : '' }}>En Discusión</option>
                    <option value="pending_approval" {{ request('status') == 'pending_approval' ? 'selected' : '' }}>Pendiente de Aprobación</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprobada</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rechazada</option>
                    <option value="implemented" {{ request('status') == 'implemented' ? 'selected' : '' }}>Implementada</option>
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

<!-- Lista de Decisiones -->
@if($decisions->count() > 0)
    <div class="space-y-4">
        @foreach($decisions as $decision)
            <x-ui.card class="hover:shadow-lg transition-shadow cursor-pointer" onclick="window.location.href='{{ route('decisions.show', $decision) }}'">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-lg font-bold text-gray-900">{{ $decision->title }}</h3>
                            <x-ui.badge 
                                variant="{{ $decision->status === 'approved' ? 'success' : ($decision->status === 'rejected' ? 'error' : ($decision->status === 'implemented' ? 'info' : 'warning')) }}">
                                {{ $decision->status_label }}
                            </x-ui.badge>
                        </div>
                        
                        @if($decision->description)
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $decision->description }}</p>
                        @endif
                        
                        <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500">
                            @if($decision->proponent)
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ $decision->proponent->name }}
                                </div>
                            @endif
                            @if($decision->decision_date)
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $decision->decision_date->format('d/m/Y') }}
                                </div>
                            @endif
                            @if($decision->plans->count() > 0)
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ $decision->plans->count() }} plan(es)
                                </div>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('decisions.show', $decision) }}" 
                       class="ml-4 text-sm font-medium text-red-600 hover:text-red-700">
                        Ver →
                    </a>
                </div>
            </x-ui.card>
        @endforeach
    </div>
    
    <!-- Paginación -->
    <div class="mt-6">
        {{ $decisions->links() }}
    </div>
@else
    <x-ui.card>
        <div class="text-center py-12 text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-sm mb-4">No hay decisiones registradas aún</p>
            @if(auth()->user()->isDirector())
                <x-ui.button href="{{ route('decisions.create') }}" variant="primary">
                    Crear Primera Decisión
                </x-ui.button>
            @endif
        </div>
    </x-ui.card>
@endif
@endsection

