@extends('layouts.dashboard')

@section('title', 'Versión ' . $version->version_number)

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
                <a href="{{ route('plans.versions', $plan) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 md:ml-2">Versiones</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Versión {{ $version->version_number }}</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('header-actions')
<a href="{{ route('plans.versions', $plan) }}" class="inline-block">
    <x-ui.button variant="secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Volver a Versiones
    </x-ui.button>
</a>
@endsection

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words">
                Versión {{ $version->version_number }}
            </h1>
            @if($version->change_summary)
                <p class="text-gray-600 mt-2">{{ $version->change_summary }}</p>
            @endif
        </div>
        <div class="flex items-center gap-4 text-sm text-gray-500">
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
</div>

<!-- Información del Plan -->
<x-ui.card class="mb-6">
    <h2 class="text-xl font-semibold mb-4">Información del Plan</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="text-sm font-medium text-gray-500">Nombre</label>
            <p class="text-gray-900">{{ $snapshot['plan']['name'] }}</p>
        </div>
        <div>
            <label class="text-sm font-medium text-gray-500">Estado</label>
            <p class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $snapshot['plan']['status'])) }}</p>
        </div>
        <div>
            <label class="text-sm font-medium text-gray-500">Fecha de Inicio</label>
            <p class="text-gray-900">{{ $snapshot['plan']['start_date'] ? \Carbon\Carbon::parse($snapshot['plan']['start_date'])->format('d/m/Y') : 'N/A' }}</p>
        </div>
        <div>
            <label class="text-sm font-medium text-gray-500">Fecha Objetivo</label>
            <p class="text-gray-900">{{ $snapshot['plan']['target_date'] ? \Carbon\Carbon::parse($snapshot['plan']['target_date'])->format('d/m/Y') : 'N/A' }}</p>
        </div>
    </div>
    @if($snapshot['plan']['description'])
        <div class="mt-4">
            <label class="text-sm font-medium text-gray-500">Descripción</label>
            <p class="text-gray-900">{{ $snapshot['plan']['description'] }}</p>
        </div>
    @endif
</x-ui.card>

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <x-ui.card variant="compact">
        <div class="text-center">
            <div class="text-2xl font-bold text-gray-900">{{ count($snapshot['sections'] ?? []) }}</div>
            <div class="text-sm text-gray-500">Secciones</div>
        </div>
    </x-ui.card>
    <x-ui.card variant="compact">
        <div class="text-center">
            <div class="text-2xl font-bold text-gray-900">{{ count($snapshot['kpis'] ?? []) }}</div>
            <div class="text-sm text-gray-500">KPIs</div>
        </div>
    </x-ui.card>
    <x-ui.card variant="compact">
        <div class="text-center">
            <div class="text-2xl font-bold text-gray-900">{{ count($snapshot['milestones'] ?? []) }}</div>
            <div class="text-sm text-gray-500">Hitos</div>
        </div>
    </x-ui.card>
    <x-ui.card variant="compact">
        <div class="text-center">
            <div class="text-2xl font-bold text-gray-900">{{ count($snapshot['tasks'] ?? []) }}</div>
            <div class="text-sm text-gray-500">Tareas</div>
        </div>
    </x-ui.card>
</div>

@can('update', $plan)
    @if($version->version_number != $plan->version)
        <div class="mb-6">
            <form action="{{ route('plans.versions.restore', [$plan, $version]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de restaurar esta versión? Esto sobrescribirá la versión actual.');">
                @csrf
                <x-ui.button type="submit" variant="warning">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Restaurar esta Versión
                </x-ui.button>
            </form>
        </div>
    @endif
@endcan
@endsection

