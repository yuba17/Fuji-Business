@extends('layouts.dashboard')

@section('title', 'Crear Plan')

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
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Crear</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div x-data="planCreator()" x-cloak>
<!-- Header Limpio y Moderno -->
<div class="mb-8">
    <div class="bg-gradient-to-r from-red-500 via-orange-500 to-red-600 rounded-2xl shadow-lg p-8 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-white/10 via-transparent to-transparent"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/30">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold mb-2">Crear Nuevo Plan</h1>
                        <p class="text-red-50 text-sm">Crea un nuevo plan estratégico, comercial o de desarrollo interno</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md p-6">
    <form action="{{ route('plans.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.input 
                    label="Nombre del Plan" 
                    name="name" 
                    value="{{ old('name') }}"
                    required 
                    placeholder="Ej: Plan Comercial 2025" />
            </div>
            
            <div>
                <x-ui.select 
                    label="Tipo de Plan" 
                    name="plan_type_id" 
                    x-model="selectedPlanType"
                    @change="updateTemplatePreview()"
                    required>
                    <option value="">-- Selecciona un tipo --</option>
                    @foreach($planTypes as $type)
                        <option value="{{ $type->id }}" 
                                data-sections="{{ json_encode($type->template_sections ?? []) }}"
                                {{ old('plan_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </x-ui.select>
            </div>
        </div>
        
        <!-- Preview de Secciones del Template -->
        <div x-show="selectedPlanType && templateSections.length > 0" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             class="bg-blue-50 border-l-4 border-blue-500 rounded-r-lg p-4">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-900 mb-2">Secciones que se crearán automáticamente:</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <template x-for="(section, index) in templateSections" :key="index">
                            <div class="flex items-center gap-2 text-sm">
                                <span class="w-6 h-6 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center text-xs font-bold" x-text="index + 1"></span>
                                <span class="text-gray-700 font-medium" x-text="section.title"></span>
                                <span x-show="section.is_required" class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-semibold rounded">Requerida</span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        
        <div>
            <x-ui.textarea 
                label="Descripción" 
                name="description" 
                rows="4"
                placeholder="Describe el objetivo y alcance del plan..."
                required>{{ old('description') }}</x-ui.textarea>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.select 
                    label="Área" 
                    name="area_id" 
                    required>
                    <option value="">-- Selecciona un área --</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                            {{ $area->name }}
                        </option>
                    @endforeach
                </x-ui.select>
            </div>
            
            <div>
                <x-ui.select 
                    label="Estado" 
                    name="status">
                    <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Borrador</option>
                    <option value="internal_review" {{ old('status') == 'internal_review' ? 'selected' : '' }}>En Revisión Interna</option>
                    <option value="director_review" {{ old('status') == 'director_review' ? 'selected' : '' }}>En Revisión Dirección</option>
                </x-ui.select>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-ui.input 
                    label="Fecha de Inicio" 
                    name="start_date" 
                    type="date"
                    value="{{ old('start_date') }}" />
            </div>
            
            <div>
                <x-ui.input 
                    label="Fecha Objetivo" 
                    name="target_date" 
                    type="date"
                    value="{{ old('target_date') }}" />
            </div>
        </div>
        
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
            <a href="{{ route('plans.index') }}" class="px-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all">
                Cancelar
            </a>
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-lg hover:from-red-700 hover:to-orange-700 transition-all shadow-sm">
                Crear Plan
            </button>
        </div>
    </form>
</div>
</div>

<script>
function planCreator() {
    return {
        selectedPlanType: '{{ old('plan_type_id', '') }}',
        templateSections: [],
        
        init() {
            if (this.selectedPlanType) {
                this.updateTemplatePreview();
            }
        },
        
        updateTemplatePreview() {
            const select = document.querySelector('select[name="plan_type_id"]');
            const selectedOption = select.options[select.selectedIndex];
            
            if (selectedOption && selectedOption.value) {
                const sectionsJson = selectedOption.getAttribute('data-sections');
                this.templateSections = sectionsJson ? JSON.parse(sectionsJson) : [];
            } else {
                this.templateSections = [];
            }
        }
    }
}
</script>
@endsection
