@extends('layouts.dashboard')

@section('title', 'Versiones del Plan')

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
                <a href="{{ route('plans.show', $plan) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 md:ml-2">{{ $plan->name }}</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Versiones</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('header-actions')
@can('update', $plan)
    <form action="{{ route('plans.versions.store', $plan) }}" method="POST" class="inline">
        @csrf
        <x-ui.button type="submit" variant="primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Crear Nueva Versión
        </x-ui.button>
    </form>
@endcan
<a href="{{ route('plans.show', $plan) }}" class="inline-block">
    <x-ui.button variant="secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Volver al Plan
    </x-ui.button>
</a>
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words">
        📋 Versiones del Plan: {{ $plan->name }}
    </h1>
    <p class="text-gray-600 mt-2">Historial de versiones y cambios del plan</p>
</div>

@if($versions->isEmpty())
    <x-ui.card>
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay versiones</h3>
            <p class="mt-1 text-sm text-gray-500">Aún no se han creado versiones de este plan.</p>
            @can('update', $plan)
                <div class="mt-6">
                    <form action="{{ route('plans.versions.store', $plan) }}" method="POST" class="inline">
                        @csrf
                        <x-ui.button type="submit" variant="primary">
                            Crear Primera Versión
                        </x-ui.button>
                    </form>
                </div>
            @endcan
        </div>
    </x-ui.card>
@else
    <div class="space-y-4">
        @foreach($versions as $version)
            <x-ui.card>
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Versión {{ $version->version_number }}
                            </h3>
                            @if($version->version_number == $plan->version)
                                <x-ui.badge variant="success">Versión Actual</x-ui.badge>
                            @endif
                        </div>
                        
                        @if($version->change_summary)
                            <p class="text-gray-600 mb-3">{{ $version->change_summary }}</p>
                        @endif
                        
                        <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ $version->creator->name }}
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $version->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-2 ml-4">
                        <a href="{{ route('plans.versions.show', [$plan, $version]) }}" class="inline-block">
                            <x-ui.button variant="secondary" size="sm">
                                Ver
                            </x-ui.button>
                        </a>
                        @if($versions->count() > 1 && !$loop->first)
                            <a href="{{ route('plans.versions.compare', [$plan, $version, $versions[$loop->index - 1]]) }}" class="inline-block">
                                <x-ui.button variant="secondary" size="sm">
                                    Comparar
                                </x-ui.button>
                            </a>
                        @endif
                        @can('update', $plan)
                            @if($version->version_number != $plan->version)
                                <form action="{{ route('plans.versions.restore', [$plan, $version]) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de restaurar esta versión? Esto sobrescribirá la versión actual.');">
                                    @csrf
                                    <x-ui.button type="submit" variant="warning" size="sm">
                                        Restaurar
                                    </x-ui.button>
                                </form>
                            @endif
                        @endcan
                    </div>
                </div>
            </x-ui.card>
        @endforeach
    </div>
    
    <div class="mt-6">
        {{ $versions->links() }}
    </div>
@endif
@endsection

