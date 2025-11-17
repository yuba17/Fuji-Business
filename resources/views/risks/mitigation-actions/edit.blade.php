@extends('layouts.dashboard')

@section('title', 'Editar Acción de Mitigación')

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
                <a href="{{ route('risks.show', $risk) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 md:ml-2">{{ $risk->name }}</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Editar Acción</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words">
        ✏️ Editar Acción de Mitigación
    </h1>
    <p class="text-gray-600 mt-2">Modifica la acción de mitigación</p>
</div>

<x-ui.card>
    <form action="{{ route('risks.mitigation-actions.update', [$risk, $mitigationAction]) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <x-ui.input 
                label="Acción" 
                name="action" 
                value="{{ old('action', $mitigationAction->action) }}"
                required 
                placeholder="Ej: Implementar sistema de backup automático" />
        </div>
        
        <div>
            <x-ui.textarea 
                label="Descripción" 
                name="description" 
                rows="4"
                placeholder="Describe la acción en detalle..."
                >{{ old('description', $mitigationAction->description) }}</x-ui.textarea>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.select 
                    label="Estado" 
                    name="status"
                    required>
                    <option value="pending" {{ old('status', $mitigationAction->status) == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="in_progress" {{ old('status', $mitigationAction->status) == 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                    <option value="completed" {{ old('status', $mitigationAction->status) == 'completed' ? 'selected' : '' }}>Completada</option>
                    <option value="cancelled" {{ old('status', $mitigationAction->status) == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                </x-ui.select>
            </div>
            
            <div>
                <x-ui.select 
                    label="Responsable" 
                    name="responsible_id">
                    <option value="">-- Sin responsable --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('responsible_id', $mitigationAction->responsible_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </x-ui.select>
            </div>
        </div>
        
        <div>
            <x-ui.input 
                type="date"
                label="Fecha Objetivo" 
                name="target_date" 
                value="{{ old('target_date', $mitigationAction->target_date?->format('Y-m-d')) }}" />
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <x-ui.input 
                    type="number"
                    step="0.01"
                    label="Costo Estimado" 
                    name="cost" 
                    value="{{ old('cost', $mitigationAction->cost) }}"
                    min="0"
                    placeholder="0.00" />
            </div>
            
            <div>
                <x-ui.input 
                    type="number"
                    label="Reducción Probabilidad" 
                    name="expected_probability_reduction" 
                    value="{{ old('expected_probability_reduction', $mitigationAction->expected_probability_reduction) }}"
                    min="0"
                    max="5"
                    placeholder="0-5" />
            </div>
            
            <div>
                <x-ui.input 
                    type="number"
                    label="Reducción Impacto" 
                    name="expected_impact_reduction" 
                    value="{{ old('expected_impact_reduction', $mitigationAction->expected_impact_reduction) }}"
                    min="0"
                    max="5"
                    placeholder="0-5" />
            </div>
        </div>
        
        <div>
            <x-ui.textarea 
                label="Notas" 
                name="notes" 
                rows="3"
                placeholder="Notas adicionales..."
                >{{ old('notes', $mitigationAction->notes) }}</x-ui.textarea>
        </div>
        
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
            <a href="{{ route('risks.show', $risk) }}" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border border-gray-200">
                Cancelar
            </a>
            <button type="submit" class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700">
                Actualizar Acción
            </button>
        </div>
    </form>
</x-ui.card>
@endsection



