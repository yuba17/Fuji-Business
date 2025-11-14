@extends('layouts.dashboard')

@section('title', 'Crear Cliente')

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
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Crear</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words">
        ➕ Crear Nuevo Cliente
    </h1>
    <p class="text-gray-600 mt-2">Registra un nuevo cliente o empresa</p>
</div>

<x-ui.card>
    <form action="{{ route('clients.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <x-ui.input 
                label="Nombre del Cliente" 
                name="name" 
                value="{{ old('name') }}"
                required 
                placeholder="Ej: Empresa XYZ S.L." />
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.input 
                    label="Sector Económico" 
                    name="sector_economico" 
                    value="{{ old('sector_economico') }}"
                    placeholder="Ej: Tecnología, Finanzas..." />
            </div>
            
            <div>
                <x-ui.input 
                    label="Tamaño de Empresa" 
                    name="tamaño_empresa" 
                    value="{{ old('tamaño_empresa') }}"
                    placeholder="Ej: Pequeña, Mediana, Grande" />
            </div>
        </div>
        
        <div>
            <x-ui.input 
                label="Ubicación" 
                name="ubicacion" 
                value="{{ old('ubicacion') }}"
                placeholder="Ciudad, País" />
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.input 
                    label="Contacto Principal" 
                    name="contacto_principal" 
                    value="{{ old('contacto_principal') }}"
                    placeholder="Nombre del contacto" />
            </div>
            
            <div>
                <x-ui.input 
                    label="Email" 
                    name="email" 
                    type="email"
                    value="{{ old('email') }}"
                    placeholder="contacto@empresa.com" />
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.input 
                    label="Teléfono" 
                    name="telefono" 
                    value="{{ old('telefono') }}"
                    placeholder="+34 123 456 789" />
            </div>
            
            <div>
                <x-ui.input 
                    label="Sitio Web" 
                    name="sitio_web" 
                    type="url"
                    value="{{ old('sitio_web') }}"
                    placeholder="https://www.empresa.com" />
            </div>
        </div>
        
        <div>
            <x-ui.textarea 
                label="Notas" 
                name="notas" 
                rows="4"
                placeholder="Información adicional sobre el cliente..."
                >{{ old('notas') }}</x-ui.textarea>
        </div>
        
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
            <x-ui.button href="{{ route('clients.index') }}" variant="secondary" type="button">
                Cancelar
            </x-ui.button>
            <x-ui.button variant="primary" type="submit">
                Crear Cliente
            </x-ui.button>
        </div>
    </form>
</x-ui.card>
@endsection

