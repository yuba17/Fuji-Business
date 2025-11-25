@extends('layouts.dashboard')

@section('title', 'Editar Hito')

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
                <a href="{{ route('plans.show', $plan) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 md:ml-2">{{ $plan->name }}</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Editar Hito</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words">
        ✏️ Editar Hito: {{ $milestone->name }}
    </h1>
    <p class="text-gray-600 mt-2">Modifica los detalles del hito</p>
</div>

<x-ui.card>
    <form action="{{ route('plans.milestones.update', [$plan, $milestone]) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <x-ui.input 
                label="Nombre del Hito" 
                name="name" 
                value="{{ old('name', $milestone->name) }}"
                required 
                placeholder="Ej: Lanzamiento de funcionalidad X" />
        </div>
        
        <div>
            <x-ui.textarea 
                label="Descripción" 
                name="description" 
                rows="4"
                placeholder="Describe el hito en detalle..."
                >{{ old('description', $milestone->description) }}</x-ui.textarea>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <x-ui.input 
                    type="date"
                    label="Fecha de Inicio" 
                    name="start_date" 
                    value="{{ old('start_date', $milestone->start_date?->format('Y-m-d')) }}" />
            </div>
            
            <div>
                <x-ui.input 
                    type="date"
                    label="Fecha Objetivo" 
                    name="target_date" 
                    value="{{ old('target_date', $milestone->target_date->format('Y-m-d')) }}"
                    required />
            </div>
            
            <div>
                <x-ui.input 
                    type="date"
                    label="Fecha de Fin" 
                    name="end_date" 
                    value="{{ old('end_date', $milestone->end_date?->format('Y-m-d')) }}" />
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.select 
                    label="Estado" 
                    name="status"
                    required>
                    <option value="not_started" {{ old('status', $milestone->status) == 'not_started' ? 'selected' : '' }}>No Iniciado</option>
                    <option value="in_progress" {{ old('status', $milestone->status) == 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                    <option value="completed" {{ old('status', $milestone->status) == 'completed' ? 'selected' : '' }}>Completado</option>
                    <option value="delayed" {{ old('status', $milestone->status) == 'delayed' ? 'selected' : '' }}>Retrasado</option>
                    <option value="cancelled" {{ old('status', $milestone->status) == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                </x-ui.select>
            </div>
            
            <div>
                <x-ui.select 
                    label="Responsable" 
                    name="responsible_id">
                    <option value="">-- Sin responsable --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('responsible_id', $milestone->responsible_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </x-ui.select>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.input 
                    type="number"
                    label="Progreso (%)" 
                    name="progress_percentage" 
                    value="{{ old('progress_percentage', $milestone->progress_percentage) }}"
                    min="0"
                    max="100" />
            </div>
            
            <div>
                <x-ui.input 
                    type="number"
                    label="Orden" 
                    name="order" 
                    value="{{ old('order', $milestone->order) }}"
                    min="0"
                    placeholder="Orden de visualización" />
            </div>
        </div>
        
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
            <a href="{{ route('plans.roadmap', $plan) }}" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border border-gray-200">
                Cancelar
            </a>
            <button type="submit" class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700">
                Actualizar Hito
            </button>
        </div>
    </form>
</x-ui.card>
@endsection



