@extends('layouts.dashboard')

@section('title', 'Comparar Versiones')

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
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Comparar</span>
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
    <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words">
        🔍 Comparar Versiones
    </h1>
    <p class="text-gray-600 mt-2">Comparación entre versión {{ $version1->version_number }} y versión {{ $version2->version_number }}</p>
</div>

<!-- Información de las Versiones -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <x-ui.card>
        <h3 class="text-lg font-semibold mb-4">Versión {{ $version1->version_number }}</h3>
        <div class="space-y-2 text-sm">
            <div>
                <span class="text-gray-500">Creada por:</span>
                <span class="text-gray-900">{{ $version1->creator->name }}</span>
            </div>
            <div>
                <span class="text-gray-500">Fecha:</span>
                <span class="text-gray-900">{{ $version1->created_at->format('d/m/Y H:i') }}</span>
            </div>
            @if($version1->change_summary)
                <div>
                    <span class="text-gray-500">Resumen:</span>
                    <p class="text-gray-900">{{ $version1->change_summary }}</p>
                </div>
            @endif
        </div>
    </x-ui.card>
    
    <x-ui.card>
        <h3 class="text-lg font-semibold mb-4">Versión {{ $version2->version_number }}</h3>
        <div class="space-y-2 text-sm">
            <div>
                <span class="text-gray-500">Creada por:</span>
                <span class="text-gray-900">{{ $version2->creator->name }}</span>
            </div>
            <div>
                <span class="text-gray-500">Fecha:</span>
                <span class="text-gray-900">{{ $version2->created_at->format('d/m/Y H:i') }}</span>
            </div>
            @if($version2->change_summary)
                <div>
                    <span class="text-gray-500">Resumen:</span>
                    <p class="text-gray-900">{{ $version2->change_summary }}</p>
                </div>
            @endif
        </div>
    </x-ui.card>
</div>

<!-- Cambios en el Plan -->
@if(isset($differences['plan']) && count($differences['plan']) > 0)
    <x-ui.card class="mb-6">
        <h2 class="text-xl font-semibold mb-4">Cambios en el Plan</h2>
        <div class="space-y-3">
            @foreach($differences['plan'] as $field => $change)
                <div class="border-l-4 border-yellow-400 pl-4 py-2">
                    <div class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $field)) }}</div>
                    <div class="text-sm text-gray-600">
                        <span class="line-through text-red-600">{{ $change['old'] ?? 'N/A' }}</span>
                        <span class="mx-2">→</span>
                        <span class="text-green-600 font-medium">{{ $change['new'] ?? 'N/A' }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </x-ui.card>
@endif

<!-- Cambios en Secciones -->
@if(isset($differences['sections']))
    <x-ui.card class="mb-6">
        <h2 class="text-xl font-semibold mb-4">Cambios en Secciones</h2>
        @if(count($differences['sections']['added']) > 0)
            <div class="mb-4">
                <h3 class="font-medium text-green-600 mb-2">Agregadas ({{ count($differences['sections']['added']) }})</h3>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($differences['sections']['added'] as $section)
                        <li>{{ $section['title'] }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(count($differences['sections']['removed']) > 0)
            <div class="mb-4">
                <h3 class="font-medium text-red-600 mb-2">Eliminadas ({{ count($differences['sections']['removed']) }})</h3>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($differences['sections']['removed'] as $section)
                        <li>{{ $section['title'] }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(count($differences['sections']['modified']) > 0)
            <div>
                <h3 class="font-medium text-yellow-600 mb-2">Modificadas ({{ count($differences['sections']['modified']) }})</h3>
                <div class="space-y-3">
                    @foreach($differences['sections']['modified'] as $section)
                        <div class="border-l-4 border-yellow-400 pl-4">
                            <div class="font-medium">{{ $section['id'] }}</div>
                            @foreach($section['changes'] as $field => $change)
                                <div class="text-sm text-gray-600">
                                    {{ ucfirst($field) }}: 
                                    <span class="line-through text-red-600">{{ $change['old'] }}</span>
                                    <span class="mx-1">→</span>
                                    <span class="text-green-600">{{ $change['new'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        @if(count($differences['sections']['added']) == 0 && count($differences['sections']['removed']) == 0 && count($differences['sections']['modified']) == 0)
            <p class="text-gray-500">No hay cambios en las secciones</p>
        @endif
    </x-ui.card>
@endif

<!-- Cambios en KPIs -->
@if(isset($differences['kpis']))
    <x-ui.card class="mb-6">
        <h2 class="text-xl font-semibold mb-4">Cambios en KPIs</h2>
        <div class="grid grid-cols-3 gap-4 text-center">
            <div>
                <div class="text-2xl font-bold text-green-600">{{ count($differences['kpis']['added'] ?? []) }}</div>
                <div class="text-sm text-gray-500">Agregados</div>
            </div>
            <div>
                <div class="text-2xl font-bold text-red-600">{{ count($differences['kpis']['removed'] ?? []) }}</div>
                <div class="text-sm text-gray-500">Eliminados</div>
            </div>
            <div>
                <div class="text-2xl font-bold text-yellow-600">{{ count($differences['kpis']['modified'] ?? []) }}</div>
                <div class="text-sm text-gray-500">Modificados</div>
            </div>
        </div>
    </x-ui.card>
@endif

<!-- Cambios en Hitos -->
@if(isset($differences['milestones']))
    <x-ui.card class="mb-6">
        <h2 class="text-xl font-semibold mb-4">Cambios en Hitos</h2>
        <div class="grid grid-cols-3 gap-4 text-center">
            <div>
                <div class="text-2xl font-bold text-green-600">{{ count($differences['milestones']['added'] ?? []) }}</div>
                <div class="text-sm text-gray-500">Agregados</div>
            </div>
            <div>
                <div class="text-2xl font-bold text-red-600">{{ count($differences['milestones']['removed'] ?? []) }}</div>
                <div class="text-sm text-gray-500">Eliminados</div>
            </div>
            <div>
                <div class="text-2xl font-bold text-yellow-600">{{ count($differences['milestones']['modified'] ?? []) }}</div>
                <div class="text-sm text-gray-500">Modificados</div>
            </div>
        </div>
    </x-ui.card>
@endif

@if((!isset($differences['plan']) || count($differences['plan']) == 0) && 
    (!isset($differences['sections']) || (count($differences['sections']['added']) == 0 && count($differences['sections']['removed']) == 0 && count($differences['sections']['modified']) == 0)) &&
    (!isset($differences['kpis']) || (count($differences['kpis']['added']) == 0 && count($differences['kpis']['removed']) == 0 && count($differences['kpis']['modified']) == 0)) &&
    (!isset($differences['milestones']) || (count($differences['milestones']['added']) == 0 && count($differences['milestones']['removed']) == 0 && count($differences['milestones']['modified']) == 0)))
    <x-ui.card>
        <div class="text-center py-8">
            <p class="text-gray-500">No se encontraron diferencias entre estas versiones</p>
        </div>
    </x-ui.card>
@endif
@endsection

