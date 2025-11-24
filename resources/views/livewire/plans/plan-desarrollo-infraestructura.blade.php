<div x-data="{ viewMode: 'inventory' }">
    {{-- Header con acciones --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-bold text-gray-900">Inventario de Infraestructura</h3>
            <p class="text-xs text-gray-500">Gestiona los recursos técnicos y tecnológicos del área</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 bg-white rounded-xl border border-gray-200 p-1">
                <button @click="viewMode = 'inventory'" 
                        :class="viewMode === 'inventory' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-50'"
                        class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all">
                    Inventario
                </button>
                <button @click="viewMode = 'capacity'" 
                        :class="viewMode === 'capacity' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-50'"
                        class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all">
                    Capacidad
                </button>
                <button @click="viewMode = 'roadmap'" 
                        :class="viewMode === 'roadmap' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-50'"
                        class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all">
                    Roadmap
                </button>
                <button @click="viewMode = 'costs'" 
                        :class="viewMode === 'costs' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-50'"
                        class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all">
                    Costes
                </button>
                <button @click="viewMode = 'analysis'" 
                        :class="viewMode === 'analysis' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-50'"
                        class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all">
                    Análisis
                </button>
            </div>
            <button wire:click="openInfrastructureModal()" 
                    class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-blue-600 to-cyan-500 text-white rounded-xl hover:from-blue-700 hover:to-cyan-600 transition-all shadow-lg hover:shadow-xl flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva Infraestructura
            </button>
        </div>
    </div>

    {{-- Alertas --}}
    @if($stats['critical_without_owner'] > 0 || $stats['high_utilization'] > 0 || $stats['without_owner'] > 0 || $stats['expiring_licenses'] > 0)
        <div class="mb-6 space-y-2">
            @if($stats['expiring_licenses'] > 0)
                <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-red-800">⏰ Licencias Caducando</p>
                            <p class="text-xs text-red-700 mt-1">
                                {{ $stats['expiring_licenses'] }} licencia(s) {{ $stats['expired_licenses'] > 0 ? 'caducada(s) o ' : '' }}próxima(s) a caducar. 
                                <button wire:click="$set('showExpiringLicenses', true)" class="underline font-semibold">Ver detalles</button>
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            @if($stats['critical_without_owner'] > 0)
                <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-red-800">⚠️ Recursos Críticos Sin Propietario</p>
                            <p class="text-xs text-red-700 mt-1">{{ $stats['critical_without_owner'] }} recurso(s) crítico(s) no tienen propietario asignado. Asigna un responsable para mejorar la gestión.</p>
                        </div>
                    </div>
                </div>
            @endif
            @if($stats['high_utilization'] > 0)
                <div class="p-4 bg-orange-50 border-l-4 border-orange-400 rounded-r-lg">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-orange-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-orange-800">⚠️ Alta Utilización</p>
                            <p class="text-xs text-orange-700 mt-1">{{ $stats['high_utilization'] }} recurso(s) tienen una utilización superior al 80%. Considera escalar o optimizar.</p>
                        </div>
                    </div>
                </div>
            @endif
            @if($stats['without_owner'] > 0 && !$stats['critical_without_owner'])
                <div class="p-4 bg-amber-50 border-l-4 border-amber-400 rounded-r-lg">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-amber-800">ℹ️ Recursos Sin Propietario</p>
                            <p class="text-xs text-amber-700 mt-1">{{ $stats['without_owner'] }} recurso(s) no tienen propietario asignado. Asigna responsables para mejorar la trazabilidad.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- Estadísticas rápidas --}}
    <div class="mb-6 overflow-x-auto pb-2">
        <div class="flex gap-3 min-w-max">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200 min-w-[220px] flex-shrink-0">
            <p class="text-xs font-medium text-blue-700 uppercase tracking-wide">Total de unidades</p>
            <p class="mt-2 text-3xl font-bold text-blue-900">{{ $stats['total'] }}</p>
            <p class="mt-1 text-xs text-blue-800">{{ $stats['total_records'] }} registros</p>
            <p class="mt-1 text-[11px] text-blue-700">Disponibles: <span class="font-semibold">{{ $stats['available_units'] }}</span> · En uso: <span class="font-semibold">{{ $stats['in_use_units'] }}</span> · Reservadas: <span class="font-semibold">{{ $stats['reserved_units'] }}</span></p>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200 min-w-[180px] flex-shrink-0">
            <p class="text-xs font-medium text-purple-700 uppercase tracking-wide">Licencias</p>
            <p class="mt-2 text-3xl font-bold text-purple-900">{{ $stats['licenses'] }}</p>
            <p class="mt-1 text-xs text-purple-800">Unidades de software</p>
        </div>
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200 min-w-[180px] flex-shrink-0">
            <p class="text-xs font-medium text-gray-700 uppercase tracking-wide">Hardware</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['hardware'] }}</p>
            <p class="mt-1 text-xs text-gray-800">Unidades físicas</p>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200 min-w-[180px] flex-shrink-0">
            <p class="text-xs font-medium text-green-700 uppercase tracking-wide">Activos</p>
            <p class="mt-2 text-3xl font-bold text-green-900">{{ $stats['active'] }}</p>
            <p class="mt-1 text-xs text-green-800">En operación</p>
        </div>
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 border border-orange-200 min-w-[180px] flex-shrink-0">
            <p class="text-xs font-medium text-orange-700 uppercase tracking-wide">Planificados</p>
            <p class="mt-2 text-3xl font-bold text-orange-900">{{ $stats['planned'] }}</p>
            <p class="mt-1 text-xs text-orange-800">Unidades en roadmap</p>
        </div>
        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 border border-red-200 min-w-[180px] flex-shrink-0">
            <p class="text-xs font-medium text-red-700 uppercase tracking-wide">Críticos</p>
            <p class="mt-2 text-3xl font-bold text-red-900">{{ $stats['critical'] }}</p>
            <p class="mt-1 text-xs text-red-800">Unidades críticas</p>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200 min-w-[180px] flex-shrink-0">
            <p class="text-xs font-medium text-purple-700 uppercase tracking-wide">Coste Mensual</p>
            <p class="mt-2 text-3xl font-bold text-purple-900">€{{ number_format($stats['total_monthly_cost'], 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-purple-800">Total mensual</p>
        </div>
        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-4 border border-indigo-200 min-w-[180px] flex-shrink-0">
            <p class="text-xs font-medium text-indigo-700 uppercase tracking-wide">Utilización</p>
            <p class="mt-2 text-3xl font-bold text-indigo-900">{{ number_format($stats['avg_utilization'], 0) }}%</p>
            <p class="mt-1 text-xs text-indigo-800">Promedio</p>
        </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="mb-6" x-data="{ filtersOpen: false }" x-cloak>
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-2xl px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-inner border border-gray-200">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6a2 2 0 012-2h6a2 2 0 012 2v13M9 19H7a2 2 0 01-2-2v-5h4m0-6H5m4 0V4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">Filtros avanzados</p>
                    <p class="text-xs text-gray-500">Refina la vista por clase, estado, propietario y más.</p>
                </div>
            </div>
            <button @click="filtersOpen = !filtersOpen"
                    class="px-4 py-2 text-sm font-semibold rounded-xl border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 flex items-center gap-2 transition-all">
                <svg class="w-4 h-4" :class="filtersOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
                <span x-text="filtersOpen ? 'Ocultar filtros' : 'Mostrar filtros'"></span>
            </button>
        </div>
        <div x-show="filtersOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="bg-white rounded-2xl shadow-md p-5 border border-gray-200 mt-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Buscar</label>
                <input type="text" wire:model.live.debounce.300ms="search" 
                       placeholder="Nombre o descripción..."
                       class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Clase</label>
                <select wire:model.live="filterInfrastructureClass" 
                        class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <option value="">Todas</option>
                    @foreach($attributeOptions['class'] ?? [] as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Adquisición</label>
                <select wire:model.live="acquisitionStatus" 
                        class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <option value="">Todas</option>
                    @foreach($attributeOptions['acquisition_status'] ?? [] as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Tipo</label>
                <select wire:model.live="type" 
                        class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <option value="">Todos</option>
                    @foreach($types as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Categoría</label>
                <select wire:model.live="category" 
                        class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <option value="">Todas</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Proveedor</label>
                <select wire:model.live="provider" 
                        class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <option value="">Todos</option>
                    @foreach($providers as $prov)
                        <option value="{{ $prov }}">{{ $prov }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Estado</label>
                <select wire:model.live="status" 
                        class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <option value="">Todos</option>
                    @foreach($attributeOptions['operational_status'] ?? [] as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Propietario</label>
                <select wire:model.live="ownerId" 
                        class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <option value="">Todos</option>
                    @foreach($areaUsers as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2 lg:col-span-1">
                <label class="block text-xs font-medium text-gray-700 mb-2">Filtros rápidos</label>
                <div class="flex flex-wrap gap-2">
                    <label class="flex items-center gap-1.5 cursor-pointer whitespace-nowrap">
                        <input type="checkbox" wire:model.live="showCriticalOnly" 
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-xs font-medium text-gray-700">Críticos</span>
                    </label>
                    <label class="flex items-center gap-1.5 cursor-pointer whitespace-nowrap">
                        <input type="checkbox" wire:model.live="showAlertsOnly" 
                               class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                        <span class="text-xs font-medium text-gray-700">Alertas</span>
                    </label>
                    <label class="flex items-center gap-1.5 cursor-pointer whitespace-nowrap">
                        <input type="checkbox" wire:model.live="showExpiringLicenses" 
                               class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <span class="text-xs font-medium text-gray-700">Caducando</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    {{-- Mensajes de éxito --}}
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-400 rounded-r-lg">
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Alertas de infraestructura --}}
    @if($stats['critical_without_owner'] > 0 || $stats['high_utilization'] > 0 || $stats['without_owner'] > 0)
        <div class="mb-6 space-y-2">
            @if($stats['critical_without_owner'] > 0)
                <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-red-800">⚠️ Recursos Críticos Sin Propietario</p>
                            <p class="text-xs text-red-700 mt-1">{{ $stats['critical_without_owner'] }} recurso(s) crítico(s) no tienen propietario asignado. Asigna un responsable para mejorar la gestión.</p>
                        </div>
                    </div>
                </div>
            @endif
            @if($stats['high_utilization'] > 0)
                <div class="p-4 bg-orange-50 border-l-4 border-orange-400 rounded-r-lg">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-orange-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-orange-800">⚠️ Alta Utilización Detectada</p>
                            <p class="text-xs text-orange-700 mt-1">{{ $stats['high_utilization'] }} recurso(s) tienen una utilización superior al 80%. Considera escalar o optimizar.</p>
                        </div>
                    </div>
                </div>
            @endif
            @if($stats['without_owner'] > 0 && !$stats['critical_without_owner'])
                <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-yellow-800">ℹ️ Recursos Sin Propietario</p>
                            <p class="text-xs text-yellow-700 mt-1">{{ $stats['without_owner'] }} recurso(s) no tienen propietario asignado. Asigna responsables para mejorar la trazabilidad.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- Vista: Inventario --}}
    <div x-show="viewMode === 'inventory'" x-transition>
        @if($infrastructures->count() > 0)
            <div class="space-y-6">
                {{-- Sección de Licencias --}}
                @if($licenses && $licenses->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-md border border-purple-200 overflow-hidden">
                        <div class="px-4 py-3 border-b border-purple-200 bg-gradient-to-r from-purple-50 to-purple-100">
                            <h3 class="text-base font-bold text-purple-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                Licencias (Software)
                                <span class="text-xs font-normal text-purple-600">({{ $licenses->count() }})</span>
                            </h3>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($licenses as $infra)
                                    @include('livewire.plans.partials.infrastructure-card', ['infra' => $infra])
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Sección de Hardware --}}
                @if($hardware && $hardware->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                                </svg>
                                Hardware (Físico)
                                <span class="text-xs font-normal text-gray-500">({{ $hardware->count() }})</span>
                            </h3>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($hardware as $infra)
                                    @include('livewire.plans.partials.infrastructure-card', ['infra' => $infra])
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center py-10 bg-white rounded-xl shadow-md border border-gray-200">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                </svg>
                <p class="text-sm text-gray-500 mb-4">No hay infraestructura registrada. Crea el primer recurso para comenzar.</p>
                <button wire:click="openInfrastructureModal()" 
                        class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-blue-600 to-cyan-500 text-white rounded-xl hover:from-blue-700 hover:to-cyan-600 transition-all">
                    Crear Primera Infraestructura
                </button>
            </div>
        @endif
    </div>

    {{-- Vista: Capacidad --}}
    <div x-show="viewMode === 'capacity'" x-transition style="display: none;">
        @if($infrastructures->whereNotNull('utilization_percent')->count() > 0)
            <div class="space-y-4">
                @foreach($infrastructures->whereNotNull('utilization_percent') as $infra)
                    <div class="bg-white rounded-xl shadow-md p-4 border border-gray-200">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">{{ $infra->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $infra->type }} - {{ $infra->category }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">Utilización</p>
                                <p class="text-lg font-bold {{ $infra->utilization_percent > 80 ? 'text-red-600' : ($infra->utilization_percent > 60 ? 'text-orange-600' : 'text-green-600') }}">
                                    {{ $infra->utilization_percent }}%
                                </p>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                            <div class="h-3 rounded-full transition-all {{ $infra->utilization_percent > 80 ? 'bg-red-500' : ($infra->utilization_percent > 60 ? 'bg-orange-500' : 'bg-green-500') }}" 
                                 style="width: {{ min(100, $infra->utilization_percent) }}%"></div>
                        </div>
                        @if($infra->capacity)
                            <p class="text-xs text-gray-500">Capacidad: {{ $infra->capacity }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-10 bg-white rounded-xl shadow-md border border-gray-200">
                <p class="text-sm text-gray-500">No hay infraestructura con datos de utilización.</p>
            </div>
        @endif
    </div>

    {{-- Vista: Roadmap --}}
    <div x-show="viewMode === 'roadmap'" x-transition style="display: none;">
        @if($roadmap->count() > 0)
            <div class="space-y-4">
                @php
                    $groupedByMonth = $roadmap->groupBy(function($item) {
                        return $item->roadmap_date->format('Y-m');
                    });
                @endphp
                @foreach($groupedByMonth as $month => $items)
                    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-orange-100">
                            <h3 class="text-sm font-bold text-gray-900">
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->locale('es')->translatedFormat('F Y') }}
                            </h3>
                        </div>
                        <div class="p-4 space-y-3">
                            @foreach($items as $infra)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h4 class="text-sm font-bold text-gray-900">{{ $infra->name }}</h4>
                                            @if($infra->is_critical)
                                                <span class="px-2 py-0.5 bg-red-100 text-red-800 text-[10px] font-bold rounded-full">🔴 Crítica</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500">{{ $infra->type }} - {{ $infra->category }}</p>
                                        @if($infra->description)
                                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($infra->description, 100) }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right ml-4">
                                        <p class="text-xs text-gray-500">Fecha Planificada</p>
                                        <p class="text-sm font-bold text-orange-600">{{ $infra->roadmap_date->format('d/m/Y') }}</p>
                                        @if($infra->roadmap_date->isPast())
                                            <span class="px-2 py-0.5 bg-red-100 text-red-800 text-[10px] font-bold rounded-full mt-1 inline-block">⚠️ Atrasado</span>
                                        @elseif($infra->roadmap_date->isToday())
                                            <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 text-[10px] font-bold rounded-full mt-1 inline-block">📅 Hoy</span>
                                        @elseif($infra->roadmap_date->diffInDays(now()) <= 30)
                                            <span class="px-2 py-0.5 bg-orange-100 text-orange-800 text-[10px] font-bold rounded-full mt-1 inline-block">🔜 Próximo</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-10 bg-white rounded-xl shadow-md border border-gray-200">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm text-gray-500">No hay infraestructura planificada en el roadmap.</p>
            </div>
        @endif
    </div>

    {{-- Vista: Costes --}}
    <div x-show="viewMode === 'costs'" x-transition style="display: none;">
        @if($infrastructures->where(fn($i) => $i->cost_monthly || $i->cost_yearly)->count() > 0)
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Recurso</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase">Coste Mensual</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase">Coste Anual</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase">Categoría</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($infrastructures->where(fn($i) => $i->cost_monthly || $i->cost_yearly) as $infra)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <p class="text-sm font-semibold text-gray-900">{{ $infra->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $infra->type }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-sm font-semibold text-gray-900">
                                            {{ $infra->cost_monthly ? '€' . number_format($infra->cost_monthly, 2, ',', '.') : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-sm font-semibold text-gray-900">
                                            {{ $infra->cost_yearly ? '€' . number_format($infra->cost_yearly, 2, ',', '.') : ($infra->cost_monthly ? '€' . number_format($infra->cost_monthly * 12, 2, ',', '.') : '-') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">{{ $infra->category }}</span>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="bg-gray-50 font-bold">
                                <td class="px-4 py-3">Total</td>
                                <td class="px-4 py-3 text-center">€{{ number_format($stats['total_monthly_cost'], 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center">€{{ number_format($stats['total_yearly_cost'], 2, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="text-center py-10 bg-white rounded-xl shadow-md border border-gray-200">
                <p class="text-sm text-gray-500">No hay infraestructura con costes registrados.</p>
            </div>
        @endif
    </div>

    {{-- Vista: Análisis --}}
    <div x-show="viewMode === 'analysis'" x-transition style="display: none;">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Costes por Categoría --}}
            @if($costsByCategory->count() > 0)
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Costes por Categoría
                    </h3>
                    <div class="space-y-3">
                        @foreach($costsByCategory as $category => $data)
                            @php
                                $maxYearly = $costsByCategory->max('yearly');
                                $percentage = $maxYearly > 0 ? ($data['yearly'] / $maxYearly) * 100 : 0;
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-semibold text-gray-700">{{ $category }}</span>
                                    <span class="text-xs font-bold text-gray-900">€{{ number_format($data['yearly'], 2, ',', '.') }}/año</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-2 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                                </div>
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-[10px] text-gray-500">{{ $data['count'] }} recurso(s)</span>
                                    <span class="text-[10px] text-gray-500">€{{ number_format($data['monthly'], 2, ',', '.') }}/mes</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Costes por Proveedor --}}
            @if($costsByProvider->count() > 0)
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Costes por Proveedor
                    </h3>
                    <div class="space-y-3">
                        @foreach($costsByProvider as $provider => $data)
                            @php
                                $maxYearly = $costsByProvider->max('yearly');
                                $percentage = $maxYearly > 0 ? ($data['yearly'] / $maxYearly) * 100 : 0;
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-semibold text-gray-700">{{ $provider }}</span>
                                    <span class="text-xs font-bold text-gray-900">€{{ number_format($data['yearly'], 2, ',', '.') }}/año</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-purple-500 to-indigo-500 h-2 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                                </div>
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-[10px] text-gray-500">{{ $data['count'] }} recurso(s)</span>
                                    <span class="text-[10px] text-gray-500">€{{ number_format($data['monthly'], 2, ',', '.') }}/mes</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Distribución por Tipo --}}
            @if($byType->count() > 0)
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Distribución por Tipo
                    </h3>
                    <div class="space-y-2">
                        @foreach($byType as $type => $items)
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                <span class="text-xs font-medium text-gray-700">{{ $type }}</span>
                                <span class="text-xs font-bold text-gray-900">{{ $items->count() }} recurso(s)</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Resumen de Salud --}}
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Resumen de Salud
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                        <span class="text-xs font-medium text-green-800">Recursos Activos</span>
                        <span class="text-sm font-bold text-green-900">{{ $stats['active'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-200">
                        <span class="text-xs font-medium text-red-800">Críticos Sin Propietario</span>
                        <span class="text-sm font-bold text-red-900">{{ $stats['critical_without_owner'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg border border-orange-200">
                        <span class="text-xs font-medium text-orange-800">Alta Utilización (>80%)</span>
                        <span class="text-sm font-bold text-orange-900">{{ $stats['high_utilization'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                        <span class="text-xs font-medium text-yellow-800">Sin Propietario</span>
                        <span class="text-sm font-bold text-yellow-900">{{ $stats['without_owner'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Crear/Editar Infraestructura --}}
    @if($showInfrastructureModal)
        <div class="fixed inset-0 z-50 bg-gray-900/40 flex items-center justify-center p-4" 
             x-show="true"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">
            <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto"
                 x-show="true"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="bg-gradient-to-r from-blue-600 to-cyan-500 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                    <h3 class="text-xl font-bold text-white">
                        {{ $infrastructureId ? 'Editar Infraestructura' : 'Nueva Infraestructura' }}
                    </h3>
                    <button wire:click="closeInfrastructureModal" class="text-white/80 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                                <input type="text" wire:model="infrastructureName" 
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                @error('infrastructureName') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Modo de seguimiento *</label>
                                <select wire:model="trackingMode"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="individual">Individual (1 registro = 1 unidad)</option>
                                    <option value="group">Grupo (inventario con múltiples unidades)</option>
                                </select>
                                @error('trackingMode') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                <p class="text-xs text-gray-500 mt-1">
                                    Individual es ideal para recursos únicos (servidores, licencias nominativas). Grupo permite registrar cantidades y disponibilidad sin crear un registro por unidad.
                                </p>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                                <textarea wire:model="infrastructureDescription" rows="3"
                                          class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Clase *</label>
                                <select wire:model="infrastructureClass" 
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="">Selecciona una clase...</option>
                                    @foreach($attributeOptions['class'] ?? [] as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('infrastructureClass') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Estado de Adquisición *</label>
                                <select wire:model="infrastructureAcquisitionStatus" 
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="">Selecciona un estado...</option>
                                    @foreach($attributeOptions['acquisition_status'] ?? [] as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('infrastructureAcquisitionStatus') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                                <select wire:model="infrastructureType" 
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="">Selecciona un tipo...</option>
                                    @foreach($attributeOptions['type'] ?? [] as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('infrastructureType') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
                                <select wire:model="infrastructureCategory" 
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="">Selecciona una categoría...</option>
                                    @foreach($attributeOptions['category'] ?? [] as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('infrastructureCategory') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            @if($trackingMode === 'group')
                                <div class="col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-3 bg-white rounded-xl border border-gray-200 p-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Unidades totales *</label>
                                        <input type="number" min="1" wire:model="quantityTotal"
                                               class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                                        @error('quantityTotal') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">En uso</label>
                                        <input type="number" min="0" wire:model="quantityInUse"
                                               class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                                        @error('quantityInUse') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Reservadas</label>
                                        <input type="number" min="0" wire:model="quantityReserved"
                                               class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                                        @error('quantityReserved') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="sm:col-span-3">
                                        <p class="text-xs text-gray-500">
                                            Disponibles: <span class="font-semibold text-gray-900">
                                                {{ max(0, ($quantityTotal ?? 0) - ($quantityInUse ?? 0) - ($quantityReserved ?? 0)) }}
                                            </span> unidades
                                        </p>
                                    </div>
                                </div>
                            @endif
                            @if($infrastructureClass === 'license')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Caducidad *</label>
                                    <input type="date" wire:model="infrastructureExpiresAt" 
                                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    @error('infrastructureExpiresAt') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Días de Aviso</label>
                                    <input type="number" wire:model="infrastructureRenewalReminderDays" min="1" max="365" 
                                           placeholder="30"
                                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <p class="text-xs text-gray-500 mt-1">Días antes de caducar para recibir aviso</p>
                                </div>
                            @endif
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Estado Operativo *</label>
                                <select wire:model="infrastructureStatus" 
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="">Selecciona un estado...</option>
                                    @foreach($attributeOptions['operational_status'] ?? [] as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('infrastructureStatus') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Propietario</label>
                                <select wire:model="infrastructureOwnerId" 
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="">Sin asignar</option>
                                    @foreach($areaUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Proveedor</label>
                                <select wire:model="infrastructureProvider" 
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="">Selecciona un proveedor...</option>
                                    @foreach($attributeOptions['provider'] ?? [] as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ubicación</label>
                                <input type="text" wire:model="infrastructureLocation" 
                                       placeholder="Ej: eu-west-1, Madrid..."
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Capacidad</label>
                                <input type="text" wire:model="infrastructureCapacity" 
                                       placeholder="Ej: 100GB, 10TB, 50 usuarios..."
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Utilización (%)</label>
                                <input type="number" wire:model="infrastructureUtilizationPercent" min="0" max="100" 
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Coste Mensual (€)</label>
                                <input type="number" wire:model="infrastructureCostMonthly" step="0.01" min="0" 
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Coste Anual (€)</label>
                                <input type="number" wire:model="infrastructureCostYearly" step="0.01" min="0" 
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Roadmap</label>
                                <input type="date" wire:model="infrastructureRoadmapDate" 
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                            <div class="col-span-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model="infrastructureIsCritical" 
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="text-sm font-medium text-gray-700">Infraestructura crítica</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl flex items-center justify-end gap-3">
                    <button wire:click="closeInfrastructureModal" 
                            class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border border-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="saveInfrastructure" 
                            class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-blue-600 to-cyan-500 text-white rounded-xl hover:from-blue-700 hover:to-cyan-600">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
