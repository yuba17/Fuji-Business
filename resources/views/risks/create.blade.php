@extends('layouts.dashboard')

@section('title', 'Crear Riesgo')

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
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Crear</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words">
        ➕ Crear Nuevo Riesgo
    </h1>
    <p class="text-gray-600 mt-2">Identifica y registra un nuevo riesgo</p>
</div>

<x-ui.card>
    <form action="{{ route('risks.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <x-ui.input 
                label="Nombre del Riesgo" 
                name="name" 
                value="{{ old('name') }}"
                required 
                placeholder="Ej: Retraso en entrega de proyecto" />
        </div>
        
        <div>
            <x-ui.textarea 
                label="Descripción" 
                name="description" 
                rows="4"
                placeholder="Describe el riesgo en detalle..."
                >{{ old('description') }}</x-ui.textarea>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.select 
                    label="Plan" 
                    name="plan_id" 
                    required>
                    <option value="">-- Selecciona un plan --</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ (old('plan_id', $planId) == $plan->id) ? 'selected' : '' }}>
                            {{ $plan->name }}
                        </option>
                    @endforeach
                </x-ui.select>
            </div>
            
            <div>
                <x-ui.select 
                    label="Área" 
                    name="area_id">
                    <option value="">-- Selecciona un área --</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                            {{ $area->name }}
                        </option>
                    @endforeach
                </x-ui.select>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.select 
                    label="Probabilidad (1-5)" 
                    name="probability" 
                    required>
                    <option value="">-- Selecciona --</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ old('probability') == $i ? 'selected' : '' }}>
                            {{ $i }} - {{ $i == 1 ? 'Muy Baja' : ($i == 2 ? 'Baja' : ($i == 3 ? 'Media' : ($i == 4 ? 'Alta' : 'Muy Alta'))) }}
                        </option>
                    @endfor
                </x-ui.select>
            </div>
            
            <div>
                <x-ui.select 
                    label="Impacto (1-5)" 
                    name="impact" 
                    required>
                    <option value="">-- Selecciona --</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ old('impact') == $i ? 'selected' : '' }}>
                            {{ $i }} - {{ $i == 1 ? 'Muy Bajo' : ($i == 2 ? 'Bajo' : ($i == 3 ? 'Medio' : ($i == 4 ? 'Alto' : 'Muy Alto'))) }}
                        </option>
                    @endfor
                </x-ui.select>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.select 
                    label="Estrategia" 
                    name="strategy">
                    <option value="">-- Selecciona --</option>
                    <option value="avoid" {{ old('strategy') == 'avoid' ? 'selected' : '' }}>Evitar</option>
                    <option value="mitigate" {{ old('strategy') == 'mitigate' ? 'selected' : '' }}>Mitigar</option>
                    <option value="transfer" {{ old('strategy') == 'transfer' ? 'selected' : '' }}>Transferir</option>
                    <option value="accept" {{ old('strategy') == 'accept' ? 'selected' : '' }}>Aceptar</option>
                </x-ui.select>
            </div>
            
            <div>
                <x-ui.select 
                    label="Propietario" 
                    name="owner_id">
                    <option value="">-- Sin asignar --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('owner_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </x-ui.select>
            </div>
        </div>
        
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
            <x-ui.button href="{{ route('risks.index') }}" variant="secondary" type="button">
                Cancelar
            </x-ui.button>
            <x-ui.button variant="primary" type="submit">
                Crear Riesgo
            </x-ui.button>
        </div>
    </form>
</x-ui.card>
@endsection

