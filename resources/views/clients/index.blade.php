@extends('layouts.dashboard')

@section('title', 'Clientes')

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
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Clientes</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('header-actions')
@if(auth()->user()->isDirector() || auth()->user()->isManager())
    <x-ui.button href="{{ route('clients.create') }}" variant="primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Crear Cliente
    </x-ui.button>
@endif
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words">
        👥 Clientes
    </h1>
    <p class="text-gray-600 mt-2">Gestión de clientes y empresas</p>
</div>

<!-- Filtros -->
<div class="mb-6">
    <x-ui.card variant="compact">
        <form method="GET" action="{{ route('clients.index') }}" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <x-ui.input 
                    type="search" 
                    placeholder="Buscar clientes..." 
                    name="search"
                    value="{{ request('search') }}" />
            </div>
            <div class="w-full sm:w-auto">
                <x-ui.select name="sector_economico" label="Sector">
                    <option value="">Todos los sectores</option>
                    <option value="Tecnología" {{ request('sector_economico') == 'Tecnología' ? 'selected' : '' }}>Tecnología</option>
                    <option value="Finanzas" {{ request('sector_economico') == 'Finanzas' ? 'selected' : '' }}>Finanzas</option>
                    <option value="Salud" {{ request('sector_economico') == 'Salud' ? 'selected' : '' }}>Salud</option>
                    <option value="Retail" {{ request('sector_economico') == 'Retail' ? 'selected' : '' }}>Retail</option>
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

<!-- Lista de Clientes -->
@if($clients->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($clients as $client)
            <x-ui.card class="hover:shadow-xl transition-shadow cursor-pointer" onclick="window.location.href='{{ route('clients.show', $client) }}'">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $client->name }}</h3>
                        @if($client->sector_economico)
                            <p class="text-xs text-gray-500">{{ $client->sector_economico }}</p>
                        @endif
                    </div>
                    @if($client->is_active)
                        <x-ui.badge variant="success">Activo</x-ui.badge>
                    @endif
                </div>
                
                <div class="space-y-2 text-sm text-gray-600 mb-4">
                    @if($client->contacto_principal)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>{{ $client->contacto_principal }}</span>
                        </div>
                    @endif
                    @if($client->email)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $client->email }}</span>
                        </div>
                    @endif
                    @if($client->ubicacion)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $client->ubicacion }}</span>
                        </div>
                    @endif
                </div>
                
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="text-xs text-gray-500">
                        {{ $client->projects->count() }} proyecto(s)
                    </div>
                    <a href="{{ route('clients.show', $client) }}" 
                       class="text-xs font-medium text-red-600 hover:text-red-700">
                        Ver →
                    </a>
                </div>
            </x-ui.card>
        @endforeach
    </div>
    
    <!-- Paginación -->
    <div class="mt-6">
        {{ $clients->links() }}
    </div>
@else
    <x-ui.card>
        <div class="text-center py-12 text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <p class="text-sm mb-4">No hay clientes registrados aún</p>
            @if(auth()->user()->isDirector() || auth()->user()->isManager())
                <x-ui.button href="{{ route('clients.create') }}" variant="primary">
                    Crear Primer Cliente
                </x-ui.button>
            @endif
        </div>
    </x-ui.card>
@endif
@endsection

