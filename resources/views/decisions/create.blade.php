@extends('layouts.dashboard')

@section('title', 'Crear Decisión')

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
                <a href="{{ route('decisions.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 md:ml-2">Decisiones</a>
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
        ➕ Crear Nueva Decisión
    </h1>
    <p class="text-gray-600 mt-2">Registra una nueva decisión estratégica u operativa</p>
</div>

<x-ui.card>
    <form action="{{ route('decisions.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <x-ui.input 
                label="Título de la Decisión" 
                name="title" 
                value="{{ old('title') }}"
                required 
                placeholder="Ej: Aprobación de nuevo servicio X" />
        </div>
        
        <div>
            <x-ui.textarea 
                label="Descripción" 
                name="description" 
                rows="4"
                placeholder="Describe la decisión..."
                >{{ old('description') }}</x-ui.textarea>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.input 
                    label="Fecha de Decisión" 
                    name="decision_date" 
                    type="date"
                    value="{{ old('decision_date', now()->format('Y-m-d')) }}" />
            </div>
            
            <div>
                <x-ui.select 
                    label="Tipo de Impacto" 
                    name="impact_type">
                    <option value="">-- Selecciona --</option>
                    <option value="strategic" {{ old('impact_type') == 'strategic' ? 'selected' : '' }}>Estratégico</option>
                    <option value="operational" {{ old('impact_type') == 'operational' ? 'selected' : '' }}>Operacional</option>
                    <option value="financial" {{ old('impact_type') == 'financial' ? 'selected' : '' }}>Financiero</option>
                    <option value="technical" {{ old('impact_type') == 'technical' ? 'selected' : '' }}>Técnico</option>
                    <option value="organizational" {{ old('impact_type') == 'organizational' ? 'selected' : '' }}>Organizativo</option>
                </x-ui.select>
            </div>
        </div>
        
        <div>
            <x-ui.select 
                label="Estado" 
                name="status">
                <option value="proposed" {{ old('status', 'proposed') == 'proposed' ? 'selected' : '' }}>Propuesta</option>
                <option value="discussion" {{ old('status') == 'discussion' ? 'selected' : '' }}>En Discusión</option>
                <option value="pending_approval" {{ old('status') == 'pending_approval' ? 'selected' : '' }}>Pendiente de Aprobación</option>
                <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Aprobada</option>
                <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rechazada</option>
            </x-ui.select>
        </div>
        
        <div>
            <x-ui.textarea 
                label="Alternativas Consideradas" 
                name="alternatives_considered" 
                rows="3"
                placeholder="Describe las alternativas que se consideraron..."
                >{{ old('alternatives_considered') }}</x-ui.textarea>
        </div>
        
        <div>
            <x-ui.textarea 
                label="Razonamiento" 
                name="rationale" 
                rows="3"
                placeholder="Explica el razonamiento detrás de esta decisión..."
                >{{ old('rationale') }}</x-ui.textarea>
        </div>
        
        <div>
            <x-ui.textarea 
                label="Impacto Esperado" 
                name="expected_impact" 
                rows="3"
                placeholder="Describe el impacto esperado de esta decisión..."
                >{{ old('expected_impact') }}</x-ui.textarea>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Planes Relacionados</label>
            <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-300 rounded-xl p-4">
                @foreach($plans as $plan)
                    <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                        <input type="checkbox" 
                               name="plan_ids[]" 
                               value="{{ $plan->id }}"
                               {{ (old('plan_ids') && in_array($plan->id, old('plan_ids'))) || ($planId && $planId == $plan->id) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <span class="text-sm text-gray-700">{{ $plan->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
            <x-ui.button href="{{ route('decisions.index') }}" variant="secondary" type="button">
                Cancelar
            </x-ui.button>
            <x-ui.button variant="primary" type="submit">
                Crear Decisión
            </x-ui.button>
        </div>
    </form>
</x-ui.card>
@endsection

