<div x-data="{ 
    showStats: true,
    activeFilters: [],
    removeFilter(type, value) {
        if (type === 'type') this.$wire.set('type', '');
        if (type === 'status') this.$wire.set('status', '');
        if (type === 'criticality') this.$wire.set('criticality', '');
        if (type === 'ownerId') this.$wire.set('ownerId', '');
    }
}" x-cloak>
    {{-- Header con pestañas mejorado --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-2xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent">I+D & Tooling</h3>
                <p class="text-xs text-gray-500 mt-1">Catálogo de herramientas internas y roadmap de investigación</p>
            </div>
            <div>
                @if($viewMode === 'catalog')
                <button wire:click="openToolingModal" 
                        type="button"
                        class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700 transition-all shadow-lg hover:shadow-xl flex items-center gap-2 transform hover:scale-105">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nueva Herramienta
                </button>
                @endif
                @if($viewMode === 'roadmap')
                <button wire:click="openMilestoneModal" 
                        type="button"
                        class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700 transition-all shadow-lg hover:shadow-xl flex items-center gap-2 transform hover:scale-105">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nuevo Hito
                </button>
                @endif
            </div>
        </div>
        
        {{-- Pestañas mejoradas --}}
        <div class="border-b-2 border-gray-200">
            <nav class="-mb-px flex space-x-12">
                <button wire:click="$set('viewMode', 'dashboard')" 
                        class="py-4 px-4 border-b-2 font-semibold text-sm transition-all duration-300
                        @if($viewMode === 'dashboard')
                            border-red-600 text-red-600
                        @else
                            border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300
                        @endif">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span>Dashboard</span>
                    </div>
                </button>
                <button wire:click="$set('viewMode', 'catalog')" 
                        class="py-4 px-4 border-b-2 font-semibold text-sm transition-all duration-300
                        @if($viewMode === 'catalog')
                            border-red-600 text-red-600
                        @else
                            border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300
                        @endif">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span>Catálogo</span>
                    </div>
                </button>
                <button wire:click="$set('viewMode', 'roadmap')" 
                        class="py-4 px-4 border-b-2 font-semibold text-sm transition-all duration-300
                        @if($viewMode === 'roadmap')
                            border-red-600 text-red-600
                        @else
                            border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300
                        @endif">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <span>Roadmap</span>
                    </div>
                </button>
            </nav>
        </div>
    </div>

    {{-- Alertas mejoradas --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" 
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-md">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" 
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-md">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- VISTA: DASHBOARD --}}
    @if($viewMode === 'dashboard' && isset($statistics))
    <div x-show="@js($viewMode) === 'dashboard'"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        {{-- Dashboard de Estadísticas --}}
    <div x-show="showStats"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 transform translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            {{-- Total Herramientas --}}
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <p class="text-blue-100 text-xs font-medium uppercase tracking-wide">Total Herramientas</p>
                        <p class="text-4xl font-bold mt-2">{{ $statistics['total_toolings'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- En Producción --}}
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <p class="text-green-100 text-xs font-medium uppercase tracking-wide">En Producción</p>
                        <p class="text-4xl font-bold mt-2">{{ $statistics['by_status']['produccion'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- En Desarrollo --}}
            <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <p class="text-yellow-100 text-xs font-medium uppercase tracking-wide">En Desarrollo</p>
                        <p class="text-4xl font-bold mt-2">{{ $statistics['by_status']['en_desarrollo'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Hitos Activos --}}
            <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <p class="text-purple-100 text-xs font-medium uppercase tracking-wide">Hitos Activos</p>
                        <p class="text-4xl font-bold mt-2">{{ $statistics['milestones']['en_curso'] }}</p>
                        <p class="text-purple-100 text-xs mt-1">{{ $statistics['milestones']['total'] }} total</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gráficos de Distribución --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            {{-- Gráfico por Estado --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-500">
                <h4 class="text-lg font-bold text-gray-900 mb-4">Distribución por Estado</h4>
                <div class="relative h-64">
                    <canvas id="statusChart" wire:ignore></canvas>
                </div>
            </div>

            {{-- Gráfico por Tipo --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-orange-500">
                <h4 class="text-lg font-bold text-gray-900 mb-4">Distribución por Tipo</h4>
                <div class="relative h-64">
                    <canvas id="typeChart" wire:ignore></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- VISTA: CATÁLOGO MEJORADO --}}
    @if($viewMode === 'catalog')
    <div x-show="@js($viewMode) === 'catalog'"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        {{-- Filtros mejorados con chips --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Búsqueda</label>
                    <input type="text" wire:model.live.debounce.300ms="search" 
                           placeholder="Buscar herramientas..."
                           class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tipo</label>
                    <select wire:model.live="type" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <option value="">Todos</option>
                        <option value="ofensiva">Ofensiva</option>
                        <option value="automatizacion">Automatización</option>
                        <option value="laboratorio">Laboratorio</option>
                        <option value="reporting">Reporting</option>
                        <option value="soporte">Soporte</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Estado</label>
                    <select wire:model.live="status" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <option value="">Todos</option>
                        <option value="idea">Idea</option>
                        <option value="en_evaluacion">En Evaluación</option>
                        <option value="en_desarrollo">En Desarrollo</option>
                        <option value="beta">Beta</option>
                        <option value="produccion">Producción</option>
                        <option value="obsoleta">Obsoleta</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Criticidad</label>
                    <select wire:model.live="criticality" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <option value="">Todas</option>
                        <option value="alta">Alta</option>
                        <option value="media">Media</option>
                        <option value="baja">Baja</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Responsable</label>
                    <select wire:model.live="ownerId" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <option value="">Todos</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Chips de filtros activos --}}
            @if($type || $status || $criticality || $ownerId)
            <div class="flex items-center gap-2 flex-wrap pt-4 border-t border-gray-200">
                <span class="text-xs font-medium text-gray-600">Filtros activos:</span>
                @if($type)
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                    Tipo: {{ ucfirst($type) }}
                    <button wire:click="$set('type', '')" class="hover:text-red-900">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </span>
                @endif
                @if($status)
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                    Estado: {{ ucfirst(str_replace('_', ' ', $status)) }}
                    <button wire:click="$set('status', '')" class="hover:text-blue-900">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </span>
                @endif
                @if($criticality)
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                    Criticidad: {{ ucfirst($criticality) }}
                    <button wire:click="$set('criticality', '')" class="hover:text-yellow-900">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </span>
                @endif
                @if($ownerId)
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">
                    Responsable: {{ $users->find($ownerId)?->name ?? 'N/A' }}
                    <button wire:click="$set('ownerId', '')" class="hover:text-purple-900">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </span>
                @endif
            </div>
            @endif
        </div>

        {{-- Grid de herramientas mejorado --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($toolings as $index => $tooling)
            <div x-data="{ showPreview: false }"
                 style="animation: fadeInUp 0.5s ease {{ $index * 0.05 }}s both;"
                 class="group relative bg-white rounded-2xl border-2 border-gray-200 p-6 hover:border-red-300 hover:shadow-2xl transition-all duration-300 transform hover:scale-105 cursor-pointer"
                 wire:click="openToolingDetailsModal({{ $tooling->id }})"
                 @mouseenter="showPreview = true"
                 @mouseleave="showPreview = false">
                
                {{-- Gradiente de fondo según tipo --}}
                <div class="absolute inset-0 rounded-2xl opacity-0 group-hover:opacity-5 transition-opacity duration-300
                    @if($tooling->type === 'ofensiva') bg-gradient-to-br from-red-500 to-orange-500
                    @elseif($tooling->type === 'automatizacion') bg-gradient-to-br from-blue-500 to-cyan-500
                    @elseif($tooling->type === 'laboratorio') bg-gradient-to-br from-purple-500 to-pink-500
                    @elseif($tooling->type === 'reporting') bg-gradient-to-br from-green-500 to-cyan-500
                    @elseif($tooling->type === 'soporte') bg-gradient-to-br from-yellow-500 to-orange-500
                    @else bg-gradient-to-br from-gray-400 to-gray-600
                    @endif">
                </div>

                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                {{-- Icono según tipo --}}
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center
                                    @if($tooling->type === 'ofensiva') bg-gradient-to-br from-red-500 to-orange-500
                                    @elseif($tooling->type === 'automatizacion') bg-gradient-to-br from-blue-500 to-cyan-500
                                    @elseif($tooling->type === 'laboratorio') bg-gradient-to-br from-purple-500 to-pink-500
                                    @elseif($tooling->type === 'reporting') bg-gradient-to-br from-green-500 to-cyan-500
                                    @elseif($tooling->type === 'soporte') bg-gradient-to-br from-yellow-500 to-orange-500
                                    @else bg-gradient-to-br from-gray-400 to-gray-600
                                    @endif">
                                    @if($tooling->type === 'ofensiva')
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                                    </svg>
                                    @elseif($tooling->type === 'automatizacion')
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                                    </svg>
                                    @else
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                                    </svg>
                                    @endif
                                </div>
                                <h4 class="text-lg font-bold text-gray-900 group-hover:text-red-600 transition-colors">{{ $tooling->name }}</h4>
                            </div>
                            
                            <div class="flex items-center gap-2 flex-wrap mb-3">
                                <span class="px-3 py-1 text-xs font-bold rounded-full
                                    @if($tooling->type === 'ofensiva') bg-red-100 text-red-800
                                    @elseif($tooling->type === 'automatizacion') bg-blue-100 text-blue-800
                                    @elseif($tooling->type === 'laboratorio') bg-purple-100 text-purple-800
                                    @elseif($tooling->type === 'reporting') bg-green-100 text-green-800
                                    @elseif($tooling->type === 'soporte') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $tooling->type_label }}
                                </span>
                                <span class="px-3 py-1 text-xs font-bold rounded-full 
                                    @if($tooling->status === 'produccion') bg-green-100 text-green-800
                                    @elseif($tooling->status === 'en_desarrollo') bg-blue-100 text-blue-800
                                    @elseif($tooling->status === 'beta') bg-yellow-100 text-yellow-800
                                    @elseif($tooling->status === 'obsoleta') bg-gray-100 text-gray-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $tooling->status_label }}
                                </span>
                                @if($tooling->criticality === 'alta')
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 animate-pulse">
                                    🔴 Alta Criticidad
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Acciones rápidas siempre visibles --}}
                        <div class="flex items-center gap-1">
                            <button wire:click.stop="openToolingModal({{ $tooling->id }})" 
                                    class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all transform hover:scale-110"
                                    title="Editar herramienta">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button wire:click.stop="deleteTooling({{ $tooling->id }})" 
                                    onclick="return confirm('¿Estás seguro de eliminar esta herramienta?')"
                                    class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all transform hover:scale-110"
                                    title="Eliminar herramienta">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    @if($tooling->description)
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $tooling->description }}</p>
                    @endif

                    <div class="space-y-2 text-xs text-gray-600">
                        @if($tooling->owner)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="font-medium">{{ $tooling->owner->name }}</span>
                        </div>
                        @endif
                        @if($tooling->started_at)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Inicio: {{ $tooling->started_at->format('d/m/Y') }}</span>
                        </div>
                        @endif
                        @if($tooling->milestones->count() > 0)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            <span class="font-semibold">{{ $tooling->milestones->count() }} hito(s) planificado(s)</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Vista previa rápida en hover --}}
                <div x-show="showPreview"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="absolute inset-x-0 bottom-0 bg-white rounded-b-2xl p-4 shadow-2xl border-t-2 border-gray-200 z-20"
                     style="display: none;">
                    <div class="text-xs text-gray-600 space-y-1">
                        @if($tooling->benefits)
                        <p><span class="font-semibold">Beneficios:</span> {{ Str::limit($tooling->benefits, 100) }}</p>
                        @endif
                        @if($tooling->teamMembers->count() > 0)
                        <p><span class="font-semibold">Equipo:</span> {{ $tooling->teamMembers->pluck('name')->join(', ') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <p class="text-lg font-semibold text-gray-700 mb-2">No hay herramientas registradas</p>
                <p class="text-sm text-gray-500 mb-6">Comienza creando tu primera herramienta de I+D</p>
                <button wire:click="openToolingModal()" 
                        class="px-6 py-3 text-sm font-bold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                    Crear primera herramienta
                </button>
            </div>
            @endforelse
        </div>

        {{-- Paginación mejorada --}}
        @if($toolings->hasPages())
        <div class="mt-8">
            {{ $toolings->links() }}
        </div>
        @endif
    </div>
    @endif

    {{-- VISTA: ROADMAP MEJORADO --}}
    @if($viewMode === 'roadmap')
    <div x-show="@js($viewMode) === 'roadmap'"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        {{-- Filtros mejorados --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Año</label>
                    <input type="number" wire:model.live="roadmapYear" 
                           min="2020" max="2100"
                           class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Trimestre</label>
                    <select wire:model.live="roadmapQuarter" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <option value="">Todos</option>
                        <option value="Q1">Q1</option>
                        <option value="Q2">Q2</option>
                        <option value="Q3">Q3</option>
                        <option value="Q4">Q4</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Estado</label>
                    <select wire:model.live="roadmapStatus" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <option value="">Todos</option>
                        <option value="planificado">Planificado</option>
                        <option value="en_curso">En Curso</option>
                        <option value="completado">Completado</option>
                        <option value="bloqueado">Bloqueado</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Prioridad</label>
                    <select wire:model.live="roadmapPriority" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <option value="">Todas</option>
                        <option value="alta">Alta</option>
                        <option value="media">Media</option>
                        <option value="baja">Baja</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Timeline visual del roadmap --}}
        <div class="space-y-6">
            @forelse($milestonesByQuarter as $quarterData)
            <div x-data="{ expanded: true }"
                 style="animation: slideInLeft 0.5s ease both;"
                 class="relative pl-12">
                {{-- Línea de tiempo vertical mejorada --}}
                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gradient-to-b from-red-500 via-orange-500 to-gray-200"></div>
                
                {{-- Punto en la línea de tiempo --}}
                <div class="absolute left-3 top-6 w-3 h-3 bg-gradient-to-br from-red-600 to-orange-600 rounded-full border-2 border-white shadow-lg"></div>
                
                <div class="bg-white rounded-2xl shadow-lg border-l-4 border-red-500 p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-red-600 to-orange-600 rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-md">
                                {{ $quarterData['quarter'] ?? '?' }}
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">
                                    {{ $quarterData['year'] ?? 'Sin año' }} - {{ $quarterData['quarter'] ?? 'Sin trimestre' }}
                                </h4>
                                <p class="text-sm text-gray-500 mt-1">{{ count($quarterData['milestones']) }} hito(s) planificado(s)</p>
                            </div>
                        </div>
                        <button @click="expanded = !expanded" 
                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                            <svg x-show="expanded" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 rotate-180"
                                 x-transition:enter-end="opacity-100 rotate-0"
                                 class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                            </svg>
                            <svg x-show="!expanded" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -rotate-180"
                                 x-transition:enter-end="opacity-100 rotate-0"
                                 class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                    
                    <div x-show="expanded"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform translate-y-4"
                         class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($quarterData['milestones'] as $milestone)
                        @php
                            $progress = $milestone->checklist_progress ?? 0;
                            $checklist = $milestone->checklist ?? [];
                            $completed = collect($checklist)->where('completed', true)->count();
                            $total = count($checklist);
                        @endphp
                        <div class="group relative bg-white rounded-xl border-2 border-gray-200 p-4 hover:border-red-300 hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02] cursor-pointer"
                             wire:click="openMilestoneDetailsModal({{ $milestone->id }})">
                            {{-- Indicador de prioridad mejorado --}}
                            <div class="absolute top-0 right-0 w-8 h-8 overflow-hidden rounded-tr-xl">
                                <div class="absolute top-0 right-0 w-0 h-0 border-l-[32px] border-l-transparent border-t-[32px] 
                                    @if($milestone->priority === 'alta') border-t-red-500
                                    @elseif($milestone->priority === 'media') border-t-yellow-500
                                    @else border-t-gray-400
                                    @endif">
                                </div>
                            </div>

                            <div class="flex items-start gap-2 mb-2">
                                {{-- Círculo de progreso dinámico --}}
                                <div class="relative w-10 h-10 flex-shrink-0">
                                    @php
                                        $circumference = 2 * 3.14159 * 16; // 2πr (radio más pequeño)
                                        $offset = $circumference * (1 - $progress / 100);
                                        $strokeColor = $progress === 100 ? '#10b981' : ($progress > 0 ? '#f59e0b' : '#e5e7eb');
                                    @endphp
                                    <svg class="transform -rotate-90 w-10 h-10">
                                        <circle cx="20" cy="20" r="16" stroke="#e5e7eb" stroke-width="3" fill="none"/>
                                        <circle cx="20" cy="20" r="16" 
                                                stroke="{{ $strokeColor }}" 
                                                stroke-width="3" 
                                                fill="none"
                                                stroke-dasharray="{{ $circumference }}"
                                                stroke-dashoffset="{{ $offset }}"
                                                stroke-linecap="round"
                                                class="transition-all duration-500"/>
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-[10px] font-bold @if($progress === 100) text-green-600 @elseif($progress > 0) text-yellow-600 @else text-gray-400 @endif">
                                            {{ $progress }}%
                                        </span>
                                    </div>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h5 class="text-sm font-bold text-gray-900 mb-0.5 group-hover:text-red-600 transition-colors line-clamp-2">{{ $milestone->title }}</h5>
                                    <p class="text-[10px] text-gray-500 font-medium truncate">{{ $milestone->tooling->name }}</p>
                                </div>
                                <div class="flex items-center gap-1 ml-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click.stop="openMilestoneModal({{ $milestone->id }})" 
                                            class="p-1 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-all"
                                            title="Editar hito">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button wire:click.stop="deleteMilestone({{ $milestone->id }})" 
                                            onclick="return confirm('¿Eliminar este hito?')"
                                            class="p-1 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-all"
                                            title="Eliminar hito">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-1.5 flex-wrap mb-2">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full 
                                    @if($milestone->priority === 'alta') bg-red-100 text-red-800 border border-red-200
                                    @elseif($milestone->priority === 'media') bg-yellow-100 text-yellow-800 border border-yellow-200
                                    @else bg-gray-100 text-gray-800 border border-gray-200
                                    @endif">
                                    {{ $milestone->priority_label }}
                                </span>
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full 
                                    @if($milestone->status === 'completado') bg-green-100 text-green-800 border border-green-200
                                    @elseif($milestone->status === 'en_curso') bg-blue-100 text-blue-800 border border-blue-200
                                    @elseif($milestone->status === 'bloqueado') bg-red-100 text-red-800 border border-red-200
                                    @else bg-gray-100 text-gray-800 border border-gray-200
                                    @endif">
                                    @if($milestone->status === 'en_curso')
                                    <span class="inline-block w-2 h-2 bg-blue-600 rounded-full mr-1.5 animate-pulse"></span>
                                    @elseif($milestone->status === 'completado')
                                    <span class="inline-block mr-1">✅</span>
                                    @elseif($milestone->status === 'bloqueado')
                                    <span class="inline-block mr-1">🚫</span>
                                    @endif
                                    {{ $milestone->status_label }}
                                </span>
                            </div>

                            @if($milestone->description)
                            <p class="text-[11px] text-gray-600 mb-2 line-clamp-1 leading-relaxed">{{ $milestone->description }}</p>
                            @endif

                            {{-- Checklist compacto --}}
                            @if($total > 0)
                            <div class="mb-2 pt-2 border-t border-gray-100">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[10px] font-medium text-gray-500">Checklist</span>
                                    <span class="text-[10px] text-gray-400">{{ $completed }}/{{ $total }}</span>
                                </div>
                                <div class="space-y-1">
                                    @foreach(array_slice($checklist, 0, 2) as $item)
                                    <div class="flex items-center gap-1.5 text-[10px]">
                                        @if($item['completed'] ?? false)
                                        <svg class="w-3 h-3 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-500 line-through truncate">{{ $item['text'] ?? '' }}</span>
                                        @else
                                        <svg class="w-3 h-3 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-gray-700 truncate">{{ $item['text'] ?? '' }}</span>
                                        @endif
                                    </div>
                                    @endforeach
                                    @if($total > 2)
                                    <p class="text-[10px] text-gray-400 italic">+{{ $total - 2 }} más...</p>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($milestone->assignedTo)
                            <div class="flex items-center gap-1.5 text-[10px] text-gray-500 pt-2 border-t border-gray-100">
                                <svg class="w-3 h-3 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="font-medium truncate">{{ $milestone->assignedTo->name }}</span>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-16 bg-white rounded-2xl border-2 border-gray-200">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <p class="text-lg font-semibold text-gray-700 mb-2">No hay hitos en el roadmap</p>
                <p class="text-sm text-gray-500 mb-6">Comienza planificando los hitos de evolución de tus herramientas</p>
                <button wire:click="openMilestoneModal()" 
                        class="px-6 py-3 text-sm font-bold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                    Crear primer hito
                </button>
            </div>
            @endforelse
        </div>
    </div>
    @endif

    {{-- MODAL: TOOLING (sin cambios, ya está bien) --}}
    @if($showToolingModal)
    <div class="fixed inset-0 z-50 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4" 
         wire:click.self="closeToolingModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <div class="bg-gradient-to-r from-red-600 to-orange-600 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                <h3 class="text-xl font-bold text-white">
                    {{ $toolingId ? 'Editar Herramienta' : 'Nueva Herramienta' }}
                </h3>
                <button wire:click="closeToolingModal" class="text-white/80 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                        <input type="text" wire:model="toolingName" 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        @error('toolingName') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                        <select wire:model="toolingType" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                            <option value="">-- Selecciona --</option>
                            @foreach($this->toolingTypeOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                        <select wire:model="toolingStatus" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                            <option value="">-- Selecciona --</option>
                            @foreach($this->toolingStatusOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Criticidad *</label>
                        <select wire:model="toolingCriticality" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                            <option value="">-- Selecciona --</option>
                            @foreach($this->toolingCriticalityOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Responsable</label>
                        <select wire:model="toolingOwnerId" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                            <option value="">Sin responsable</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contacto de referencia</label>
                        <input type="text" wire:model="toolingContactReference" 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de inicio</label>
                        <input type="date" wire:model="toolingStartedAt" 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Última actualización</label>
                        <input type="date" wire:model="toolingLastUpdatedAt" 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea wire:model="toolingDescription" rows="3" 
                                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all resize-none"></textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Beneficios</label>
                        <textarea wire:model="toolingBenefits" rows="3" 
                                  placeholder="Qué beneficios aporta esta herramienta..."
                                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all resize-none"></textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Equipo implicado</label>
                        <select wire:model="selectedTeamMembers" multiple 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all" size="4">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Mantén presionado Ctrl/Cmd para seleccionar múltiples</p>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl flex items-center justify-end gap-3">
                <button wire:click="closeToolingModal" 
                        class="px-4 py-2.5 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border-2 border-gray-200 transition-all">
                    Cancelar
                </button>
                <button wire:click="saveTooling" 
                        class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700 transition-all shadow-lg hover:shadow-xl">
                    Guardar
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL: DETALLES DE HERRAMIENTA --}}
    @if($showToolingDetailsModal && $selectedToolingForDetails)
    <div class="fixed inset-0 z-50 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4" 
         wire:click.self="closeToolingDetailsModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <div class="bg-gradient-to-r from-red-600 to-orange-600 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        @if($selectedToolingForDetails->type === 'ofensiva')
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                        </svg>
                        @else
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                        </svg>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">{{ $selectedToolingForDetails->name }}</h3>
                        <p class="text-sm text-white/80">{{ $selectedToolingForDetails->type_label }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button wire:click="openToolingModal({{ $selectedToolingForDetails->id }})" 
                            wire:click.stop
                            class="px-4 py-2 text-sm font-semibold bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </button>
                    <button wire:click="closeToolingDetailsModal" class="text-white/80 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                {{-- Información básica --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Estado</label>
                            <span class="inline-block px-3 py-1 text-sm font-bold rounded-full 
                                @if($selectedToolingForDetails->status === 'produccion') bg-green-100 text-green-800
                                @elseif($selectedToolingForDetails->status === 'en_desarrollo') bg-blue-100 text-blue-800
                                @elseif($selectedToolingForDetails->status === 'beta') bg-yellow-100 text-yellow-800
                                @elseif($selectedToolingForDetails->status === 'obsoleta') bg-gray-100 text-gray-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $selectedToolingForDetails->status_label }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Criticidad</label>
                            <span class="inline-block px-3 py-1 text-sm font-bold rounded-full 
                                @if($selectedToolingForDetails->criticality === 'alta') bg-red-100 text-red-800
                                @elseif($selectedToolingForDetails->criticality === 'media') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $selectedToolingForDetails->criticality_label }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        @if($selectedToolingForDetails->started_at)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Fecha de Inicio</label>
                            <p class="text-sm font-medium text-gray-900">{{ $selectedToolingForDetails->started_at->format('d/m/Y') }}</p>
                        </div>
                        @endif
                        
                        @if($selectedToolingForDetails->last_updated_at)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Última Actualización</label>
                            <p class="text-sm font-medium text-gray-900">{{ $selectedToolingForDetails->last_updated_at->format('d/m/Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Descripción --}}
                @if($selectedToolingForDetails->description)
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Descripción</label>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $selectedToolingForDetails->description }}</p>
                </div>
                @endif

                {{-- Beneficios --}}
                @if($selectedToolingForDetails->benefits)
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Beneficios</label>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $selectedToolingForDetails->benefits }}</p>
                </div>
                @endif

                {{-- Responsable y Equipo --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($selectedToolingForDetails->owner)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Responsable</label>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $selectedToolingForDetails->owner->name }}</span>
                        </div>
                    </div>
                    @endif

                    @if($selectedToolingForDetails->contact_reference)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Contacto de Referencia</label>
                        <p class="text-sm font-medium text-gray-900">{{ $selectedToolingForDetails->contact_reference }}</p>
                    </div>
                    @endif
                </div>

                {{-- Equipo Implicado --}}
                @if($selectedToolingForDetails->teamMembers->count() > 0)
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Equipo Implicado</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($selectedToolingForDetails->teamMembers as $member)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-800 text-sm font-medium rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $member->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Hitos de Evolución --}}
                @if($selectedToolingForDetails->milestones->count() > 0)
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-3">Hitos de Evolución ({{ $selectedToolingForDetails->milestones->count() }})</label>
                    <div class="space-y-3">
                        @foreach($selectedToolingForDetails->milestones as $milestone)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1">
                                    <h5 class="text-sm font-bold text-gray-900 mb-1">{{ $milestone->title }}</h5>
                                    @if($milestone->description)
                                    <p class="text-xs text-gray-600 mb-2">{{ $milestone->description }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1">
                                    <button wire:click="openMilestoneModal({{ $milestone->id }})" 
                                            wire:click.stop
                                            class="p-1 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-2 py-0.5 text-xs font-bold rounded-full 
                                    @if($milestone->priority === 'alta') bg-red-100 text-red-800
                                    @elseif($milestone->priority === 'media') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $milestone->priority_label }}
                                </span>
                                <span class="px-2 py-0.5 text-xs font-bold rounded-full 
                                    @if($milestone->status === 'completado') bg-green-100 text-green-800
                                    @elseif($milestone->status === 'en_curso') bg-blue-100 text-blue-800
                                    @elseif($milestone->status === 'bloqueado') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $milestone->status_label }}
                                </span>
                                @if($milestone->target_year && $milestone->target_quarter)
                                <span class="text-xs text-gray-500">
                                    {{ $milestone->target_year }} - {{ $milestone->target_quarter }}
                                </span>
                                @endif
                                @if($milestone->assignedTo)
                                <span class="text-xs text-gray-500">
                                    👤 {{ $milestone->assignedTo->name }}
                                </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl flex items-center justify-between">
                <button wire:click="deleteTooling({{ $selectedToolingForDetails->id }})" 
                        onclick="return confirm('¿Estás seguro de eliminar esta herramienta?')"
                        class="px-4 py-2 text-sm font-semibold text-red-600 bg-white hover:bg-red-50 rounded-xl border-2 border-red-200 transition-all">
                    Eliminar Herramienta
                </button>
                <button wire:click="closeToolingDetailsModal" 
                        class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700 transition-all shadow-lg hover:shadow-xl">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL: MILESTONE (sin cambios, ya está bien) --}}
    @if($showMilestoneModal)
    <div class="fixed inset-0 z-[60] bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4" 
         wire:click.self="closeMilestoneModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <div class="bg-gradient-to-r from-red-600 to-orange-600 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                <h3 class="text-xl font-bold text-white">
                    {{ $milestoneId ? 'Editar Hito' : 'Nuevo Hito' }}
                </h3>
                <button wire:click="closeMilestoneModal" class="text-white/80 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Herramienta *</label>
                    <select wire:model="milestoneToolingId" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <option value="">Seleccionar herramienta</option>
                        @foreach($allToolings as $tooling)
                            <option value="{{ $tooling->id }}">{{ $tooling->name }}</option>
                        @endforeach
                    </select>
                    @error('milestoneToolingId') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                    <input type="text" wire:model="milestoneTitle" 
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                    @error('milestoneTitle') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                        <select wire:model="milestoneType" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                            <option value="">-- Selecciona --</option>
                            @foreach($this->milestoneTypeOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad *</label>
                        <select wire:model="milestonePriority" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                            <option value="">-- Selecciona --</option>
                            @foreach($this->milestonePriorityOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                        <select wire:model="milestoneStatus" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                            <option value="">-- Selecciona --</option>
                            @foreach($this->milestoneStatusOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Año objetivo</label>
                        <input type="number" wire:model="milestoneTargetYear" 
                               min="2020" max="2100"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Trimestre objetivo</label>
                        <select wire:model="milestoneTargetQuarter" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                            <option value="">Sin trimestre</option>
                            <option value="Q1">Q1</option>
                            <option value="Q2">Q2</option>
                            <option value="Q3">Q3</option>
                            <option value="Q4">Q4</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Responsable</label>
                        <select wire:model="milestoneAssignedToId" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                            <option value="">Sin responsable</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de completado</label>
                        <input type="date" wire:model="milestoneCompletedAt" 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea wire:model="milestoneDescription" rows="3" 
                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all resize-none"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                    <textarea wire:model="milestoneNotes" rows="2" 
                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all resize-none"></textarea>
                </div>

                {{-- Checklist --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Checklist</label>
                    <div class="space-y-2 mb-3">
                        @forelse($milestoneChecklist as $index => $item)
                        <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                            <button wire:click="toggleChecklistItem({{ $index }})" 
                                    class="flex-shrink-0 p-1 hover:bg-gray-200 rounded transition-colors">
                                @if($item['completed'] ?? false)
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                @else
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                @endif
                            </button>
                            <input type="text" 
                                   wire:model="milestoneChecklist.{{ $index }}.text"
                                   class="flex-1 px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @if($item['completed'] ?? false) line-through text-gray-500 @endif">
                            <button wire:click="removeChecklistItem({{ $index }})" 
                                    class="flex-shrink-0 p-1 text-red-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500 italic">No hay items en el checklist</p>
                        @endforelse
                    </div>
                    <div class="flex gap-2">
                        <input type="text" 
                               wire:model="newChecklistItem"
                               wire:keydown.enter.prevent="addChecklistItem"
                               x-on:checklist-item-added.window="$el.value = ''"
                               x-ref="checklistInput"
                               placeholder="Agregar nuevo item..."
                               class="flex-1 px-4 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <button wire:click="addChecklistItem" 
                                type="button"
                                x-on:click="$nextTick(() => $refs.checklistInput.value = '')"
                                class="px-4 py-2 text-sm font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                            Agregar
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl flex items-center justify-end gap-3">
                <button wire:click="closeMilestoneModal" 
                        class="px-4 py-2.5 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border-2 border-gray-200 transition-all">
                    Cancelar
                </button>
                <button wire:click="saveMilestone" 
                        class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700 transition-all shadow-lg hover:shadow-xl">
                    Guardar
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL: DETALLES DE HITO --}}
    @if($showMilestoneDetailsModal && $selectedMilestoneForDetails)
    <div class="fixed inset-0 z-50 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4" 
         wire:click.self="closeMilestoneDetailsModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <div class="bg-gradient-to-r from-red-600 to-orange-600 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">{{ $selectedMilestoneForDetails->title }}</h3>
                        <p class="text-sm text-white/80">{{ $selectedMilestoneForDetails->tooling->name }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button wire:click="openMilestoneModal({{ $selectedMilestoneForDetails->id }})" 
                            wire:click.stop
                            class="px-4 py-2 text-sm font-semibold bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </button>
                    <button wire:click="closeMilestoneDetailsModal" class="text-white/80 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                {{-- Información básica --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Estado</label>
                        <span class="inline-block px-3 py-1 text-sm font-bold rounded-full 
                            @if($selectedMilestoneForDetails->status === 'completado') bg-green-100 text-green-800
                            @elseif($selectedMilestoneForDetails->status === 'en_curso') bg-blue-100 text-blue-800
                            @elseif($selectedMilestoneForDetails->status === 'bloqueado') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $selectedMilestoneForDetails->status_label }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Prioridad</label>
                        <span class="inline-block px-3 py-1 text-sm font-bold rounded-full 
                            @if($selectedMilestoneForDetails->priority === 'alta') bg-red-100 text-red-800
                            @elseif($selectedMilestoneForDetails->priority === 'media') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $selectedMilestoneForDetails->priority_label }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Tipo</label>
                        <span class="inline-block px-3 py-1 text-sm font-bold rounded-full bg-indigo-100 text-indigo-800">
                            {{ $selectedMilestoneForDetails->milestone_type_label }}
                        </span>
                    </div>
                </div>

                @if($selectedMilestoneForDetails->description)
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Descripción</label>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $selectedMilestoneForDetails->description }}</p>
                </div>
                @endif

                {{-- Checklist interactivo --}}
                @php
                    $checklist = $selectedMilestoneForDetails->checklist ?? [];
                    $progress = $selectedMilestoneForDetails->checklist_progress ?? 0;
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Checklist</label>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-gray-700">{{ $progress }}%</span>
                            <div class="w-16 h-16 relative">
                                @php
                                    $circumference = 2 * 3.14159 * 28;
                                    $offset = $circumference * (1 - $progress / 100);
                                    $strokeColor = $progress === 100 ? '#10b981' : ($progress > 0 ? '#f59e0b' : '#e5e7eb');
                                @endphp
                                <svg class="transform -rotate-90 w-16 h-16">
                                    <circle cx="32" cy="32" r="28" stroke="#e5e7eb" stroke-width="4" fill="none"/>
                                    <circle cx="32" cy="32" r="28" 
                                            stroke="{{ $strokeColor }}" 
                                            stroke-width="4" 
                                            fill="none"
                                            stroke-dasharray="{{ $circumference }}"
                                            stroke-dashoffset="{{ $offset }}"
                                            stroke-linecap="round"
                                            class="transition-all duration-500"/>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-xs font-bold @if($progress === 100) text-green-600 @elseif($progress > 0) text-yellow-600 @else text-gray-400 @endif">
                                        {{ $progress }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        @forelse($checklist as $index => $item)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <button wire:click="toggleMilestoneChecklistItem({{ $selectedMilestoneForDetails->id }}, {{ $index }})" 
                                    class="flex-shrink-0 p-1 hover:bg-white rounded transition-colors">
                                @if($item['completed'] ?? false)
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                @else
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                @endif
                            </button>
                            <span class="flex-1 text-sm @if($item['completed'] ?? false) line-through text-gray-500 @else text-gray-900 @endif">
                                {{ $item['text'] ?? '' }}
                            </span>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500 italic text-center py-4">No hay items en el checklist</p>
                        @endforelse
                    </div>
                </div>

                {{-- Información adicional --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                    @if($selectedMilestoneForDetails->target_year && $selectedMilestoneForDetails->target_quarter)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Fecha Objetivo</label>
                        <p class="text-sm font-medium text-gray-900">{{ $selectedMilestoneForDetails->target_year }} - {{ $selectedMilestoneForDetails->target_quarter }}</p>
                    </div>
                    @endif

                    @if($selectedMilestoneForDetails->assignedTo)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Responsable</label>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $selectedMilestoneForDetails->assignedTo->name }}</span>
                        </div>
                    </div>
                    @endif

                    @if($selectedMilestoneForDetails->completed_at)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Fecha de Completado</label>
                        <p class="text-sm font-medium text-gray-900">{{ $selectedMilestoneForDetails->completed_at->format('d/m/Y') }}</p>
                    </div>
                    @endif

                    @if($selectedMilestoneForDetails->notes)
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Notas</label>
                        <p class="text-sm text-gray-700">{{ $selectedMilestoneForDetails->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl flex items-center justify-between">
                <button wire:click="deleteMilestone({{ $selectedMilestoneForDetails->id }})" 
                        onclick="return confirm('¿Estás seguro de eliminar este hito?')"
                        class="px-4 py-2 text-sm font-semibold text-red-600 bg-white hover:bg-red-50 rounded-xl border-2 border-red-200 transition-all">
                    Eliminar Hito
                </button>
                <button wire:click="closeMilestoneDetailsModal" 
                        class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700 transition-all shadow-lg hover:shadow-xl">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Scripts para gráficos Chart.js --}}
    @if($viewMode === 'dashboard' && isset($statistics))
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            // Gráfico de Estado (Donut)
            const statusCtx = document.getElementById('statusChart');
            if (statusCtx) {
                const statusChart = new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Idea', 'En Evaluación', 'En Desarrollo', 'Beta', 'Producción', 'Obsoleta'],
                        datasets: [{
                            data: [
                                {{ $statistics['by_status']['idea'] }},
                                {{ $statistics['by_status']['en_evaluacion'] }},
                                {{ $statistics['by_status']['en_desarrollo'] }},
                                {{ $statistics['by_status']['beta'] }},
                                {{ $statistics['by_status']['produccion'] }},
                                {{ $statistics['by_status']['obsoleta'] }}
                            ],
                            backgroundColor: [
                                '#9CA3AF',
                                '#3B82F6',
                                '#F59E0B',
                                '#EAB308',
                                '#10B981',
                                '#6B7280'
                            ],
                            borderWidth: 2,
                            borderColor: '#FFFFFF'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Gráfico de Tipo (Bar)
            const typeCtx = document.getElementById('typeChart');
            if (typeCtx) {
                const typeChart = new Chart(typeCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Ofensiva', 'Automatización', 'Laboratorio', 'Reporting', 'Soporte', 'Otro'],
                        datasets: [{
                            label: 'Herramientas',
                            data: [
                                {{ $statistics['by_type']['ofensiva'] }},
                                {{ $statistics['by_type']['automatizacion'] }},
                                {{ $statistics['by_type']['laboratorio'] }},
                                {{ $statistics['by_type']['reporting'] }},
                                {{ $statistics['by_type']['soporte'] }},
                                {{ $statistics['by_type']['otro'] }}
                            ],
                            backgroundColor: [
                                'rgba(239, 68, 68, 0.8)',
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(139, 92, 246, 0.8)',
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(107, 114, 128, 0.8)'
                            ],
                            borderColor: [
                                'rgb(239, 68, 68)',
                                'rgb(59, 130, 246)',
                                'rgb(139, 92, 246)',
                                'rgb(16, 185, 129)',
                                'rgb(245, 158, 11)',
                                'rgb(107, 114, 128)'
                            ],
                            borderWidth: 2,
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
    @endif

    {{-- Estilos CSS para animaciones --}}
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</div>
