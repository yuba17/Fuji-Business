@extends('layouts.dashboard')

@section('title', $client->name)

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
                <a href="{{ route('clients.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 md:ml-2">Clientes</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $client->name }}</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('header-actions')
@if(auth()->user()->isDirector() || auth()->user()->isManager())
    <x-ui.button href="{{ route('clients.edit', $client) }}" variant="secondary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Editar
    </x-ui.button>
@endif
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words mb-2">
        {{ $client->name }}
    </h1>
    @if($client->sector_economico)
        <p class="text-gray-600">{{ $client->sector_economico }}</p>
    @endif
</div>

<!-- Información del Cliente -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <x-ui.card>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Información de Contacto</h2>
        <dl class="space-y-3">
            @if($client->contacto_principal)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Contacto Principal</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->contacto_principal }}</dd>
                </div>
            @endif
            @if($client->email)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <a href="mailto:{{ $client->email }}" class="text-red-600 hover:text-red-700">{{ $client->email }}</a>
                    </dd>
                </div>
            @endif
            @if($client->telefono)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->telefono }}</dd>
                </div>
            @endif
            @if($client->sitio_web)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Sitio Web</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <a href="{{ $client->sitio_web }}" target="_blank" class="text-red-600 hover:text-red-700">{{ $client->sitio_web }}</a>
                    </dd>
                </div>
            @endif
            @if($client->ubicacion)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Ubicación</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->ubicacion }}</dd>
                </div>
            @endif
            @if($client->tamaño_empresa)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tamaño</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->tamaño_empresa }}</dd>
                </div>
            @endif
        </dl>
    </x-ui.card>
    
    @if($client->notas)
        <x-ui.card>
            <h2 class="text-xl font-bold text-gray-800 mb-4">Notas</h2>
            <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $client->notas }}</p>
        </x-ui.card>
    @endif
</div>

<!-- Proyectos del Cliente -->
<x-ui.card>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-gray-800">Proyectos ({{ $client->projects->count() }})</h2>
        <x-ui.button href="{{ route('projects.create', ['client_id' => $client->id]) }}" variant="primary">
            Crear Proyecto
        </x-ui.button>
    </div>
    @if($client->projects->count() > 0)
        <div class="space-y-3">
            @foreach($client->projects as $project)
                <div class="border-l-4 border-blue-500 pl-4 py-2">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $project->name }}</h3>
                            @if($project->description)
                                <p class="text-sm text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($project->description, 150) }}</p>
                            @endif
                            <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
                                <x-ui.badge variant="{{ $project->status === 'activo' ? 'success' : ($project->status === 'completado' ? 'info' : 'warning') }}">
                                    {{ $project->status_label }}
                                </x-ui.badge>
                                @if($project->presupuesto)
                                    <span>{{ number_format($project->presupuesto, 2) }} {{ $project->moneda }}</span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('projects.show', $project) }}" class="text-xs text-red-600 hover:text-red-700 ml-4">
                            Ver →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-500 text-center py-8">No hay proyectos asociados</p>
    @endif
</x-ui.card>
@endsection

