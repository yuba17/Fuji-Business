@extends('layouts.dashboard')

@section('title', 'Editar Tarea: ' . $task->title)

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
                <a href="{{ route('tasks.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 md:ml-2">Tareas</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('tasks.show', $task) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 md:ml-2">{{ $task->title }}</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Editar</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent break-words">
        ✏️ Editar Tarea
    </h1>
    <p class="text-gray-600 mt-2">Modifica la información de la tarea</p>
</div>

<x-ui.card>
    <form action="{{ route('tasks.update', $task) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <x-ui.input 
                label="Título de la Tarea" 
                name="title" 
                value="{{ old('title', $task->title) }}"
                required 
                placeholder="Ej: Implementar funcionalidad X" />
        </div>
        
        <div>
            <x-ui.textarea 
                label="Descripción" 
                name="description" 
                rows="4"
                placeholder="Describe la tarea en detalle..."
                >{{ old('description', $task->description) }}</x-ui.textarea>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.select 
                    label="Plan" 
                    name="plan_id" 
                    required>
                    <option value="">-- Selecciona un plan --</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id', $task->plan_id) == $plan->id ? 'selected' : '' }}>
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
                        <option value="{{ $area->id }}" {{ old('area_id', $task->area_id) == $area->id ? 'selected' : '' }}>
                            {{ $area->name }}
                        </option>
                    @endforeach
                </x-ui.select>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.select 
                    label="Asignado a" 
                    name="assigned_to">
                    <option value="">-- Sin asignar --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </x-ui.select>
            </div>
            
            <div>
                <x-ui.input 
                    label="Fecha de Vencimiento" 
                    name="due_date" 
                    type="date"
                    value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}" />
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.select 
                    label="Estado" 
                    name="status">
                    <option value="todo" {{ old('status', $task->status) == 'todo' ? 'selected' : '' }}>📋 Por Hacer</option>
                    <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>🔄 En Progreso</option>
                    <option value="blocked" {{ old('status', $task->status) == 'blocked' ? 'selected' : '' }}>🚫 Bloqueada</option>
                    <option value="review" {{ old('status', $task->status) == 'review' ? 'selected' : '' }}>👀 En Revisión</option>
                    <option value="done" {{ old('status', $task->status) == 'done' ? 'selected' : '' }}>✅ Completada</option>
                </x-ui.select>
            </div>
            
            <div>
                <x-ui.select 
                    label="Prioridad" 
                    name="priority">
                    <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>🟢 Baja</option>
                    <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>🟡 Media</option>
                    <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>🟠 Alta</option>
                    <option value="critical" {{ old('priority', $task->priority) == 'critical' ? 'selected' : '' }}>🔴 Crítica</option>
                </x-ui.select>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.input 
                    label="Horas Estimadas" 
                    name="estimated_hours" 
                    type="number"
                    min="0"
                    step="0.5"
                    value="{{ old('estimated_hours', $task->estimated_hours) }}"
                    placeholder="8" />
            </div>
            
            <div>
                <x-ui.input 
                    label="Horas Reales" 
                    name="actual_hours" 
                    type="number"
                    min="0"
                    step="0.5"
                    value="{{ old('actual_hours', $task->actual_hours) }}"
                    placeholder="10" />
            </div>
        </div>
        
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
            <x-ui.button href="{{ route('tasks.show', $task) }}" variant="secondary" type="button">
                Cancelar
            </x-ui.button>
            <x-ui.button variant="primary" type="submit">
                Guardar Cambios
            </x-ui.button>
        </div>
    </form>
</x-ui.card>
@endsection

