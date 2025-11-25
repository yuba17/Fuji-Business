<div x-data="{ viewMode: 'inventory', selectedUserId: null, filtersOpen: false }" x-cloak>
    {{-- Mensaje informativo sobre sincronización --}}
    <div class="mb-4 p-3 bg-indigo-50 border-l-4 border-indigo-400 rounded-r-lg">
        <div class="flex items-start gap-2">
            <svg class="w-5 h-5 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-xs font-semibold text-indigo-900">Sincronización automática con perfiles</p>
                <p class="text-xs text-indigo-700 mt-0.5">Los datos de certificaciones se sincronizan automáticamente desde los perfiles de los usuarios. Puedes editar desde aquí o desde el perfil del usuario.</p>
            </div>
        </div>
    </div>

    {{-- Header con acciones --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-bold text-gray-900">Gestión de Certificaciones</h3>
            <p class="text-xs text-gray-500">Gestiona certificaciones, planes por rol y roadmap personalizado</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2">
                <button @click="viewMode = 'inventory'" 
                        :class="viewMode === 'inventory' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                        class="px-3 py-2 text-xs font-semibold rounded-lg border border-gray-200 transition-all">
                    Inventario
                </button>
                <button @click="viewMode = 'matrix'" 
                        :class="viewMode === 'matrix' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                        class="px-3 py-2 text-xs font-semibold rounded-lg border border-gray-200 transition-all">
                    Matriz
                </button>
                <button @click="viewMode = 'roadmap'" 
                        :class="viewMode === 'roadmap' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                        class="px-3 py-2 text-xs font-semibold rounded-lg border border-gray-200 transition-all">
                    Roadmap
                </button>
                <button @click="viewMode = 'plans'" 
                        :class="viewMode === 'plans' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                        class="px-3 py-2 text-xs font-semibold rounded-lg border border-gray-200 transition-all">
                    Planes
                </button>
            </div>
            <button wire:click="openCertificationModal()" 
                    class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-purple-600 to-indigo-500 text-white rounded-xl hover:from-purple-700 hover:to-indigo-600 transition-all shadow-lg hover:shadow-xl flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva Certificación
            </button>
        </div>
    </div>

    {{-- Estadísticas rápidas --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
            <p class="text-xs font-medium text-blue-700 uppercase tracking-wide">Total</p>
            <p class="mt-2 text-3xl font-bold text-blue-900">{{ $stats['total_certifications'] }}</p>
            <p class="mt-1 text-xs text-blue-800">Certificaciones</p>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
            <p class="text-xs font-medium text-green-700 uppercase tracking-wide">Activas</p>
            <p class="mt-2 text-3xl font-bold text-green-900">{{ $stats['total_user_certifications'] }}</p>
            <p class="mt-1 text-xs text-green-800">En el equipo</p>
        </div>
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 border border-orange-200">
            <p class="text-xs font-medium text-orange-700 uppercase tracking-wide">Por Vencer</p>
            <p class="mt-2 text-3xl font-bold text-orange-900">{{ $stats['expiring_soon'] }}</p>
            <p class="mt-1 text-xs text-orange-800">Próximos 30 días</p>
        </div>
        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 border border-red-200">
            <p class="text-xs font-medium text-red-700 uppercase tracking-wide">Vencidas</p>
            <p class="mt-2 text-3xl font-bold text-red-900">{{ $stats['expired'] }}</p>
            <p class="mt-1 text-xs text-red-800">Requieren renovación</p>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
            <p class="text-xs font-medium text-purple-700 uppercase tracking-wide">Planificadas</p>
            <p class="mt-2 text-3xl font-bold text-purple-900">{{ $stats['planned'] }}</p>
            <p class="mt-1 text-xs text-purple-800">En roadmap</p>
        </div>
    </div>

    {{-- Filtros Colapsables --}}
    <div class="bg-white rounded-xl shadow-md border border-gray-200 mb-6 overflow-hidden">
        {{-- Header del filtro --}}
        <button @click="filtersOpen = !filtersOpen" 
                class="w-full px-4 py-3 flex items-center justify-between hover:bg-gray-50 transition-colors">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <h4 class="text-sm font-bold text-gray-900">Filtros de Búsqueda</h4>
            </div>
            <svg class="w-5 h-5 text-gray-600 transition-transform duration-200" 
                 :class="{ 'rotate-180': filtersOpen }"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        
        {{-- Contenido colapsable --}}
        <div x-show="filtersOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 max-h-0"
             x-transition:enter-end="opacity-100 max-h-[1000px]"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 max-h-[1000px]"
             x-transition:leave-end="opacity-0 max-h-0"
             class="overflow-hidden">
            <div class="px-4 pb-4 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 pt-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Buscar</label>
                        <input type="text" wire:model.live.debounce.300ms="search" 
                               placeholder="Nombre, código o proveedor..."
                               class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Categoría</label>
                        <select wire:model.live="category" 
                                class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                            <option value="">Todas</option>
                            @foreach($categoryOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Proveedor</label>
                        <select wire:model.live="provider" 
                                class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                            <option value="">Todos</option>
                            @foreach($providerOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nivel</label>
                        <select wire:model.live="level" 
                                class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                            <option value="">Todos</option>
                            @foreach($levelOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Estado</label>
                        <select wire:model.live="status" 
                                class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                            <option value="">Todos</option>
                            <option value="active">Activas</option>
                            <option value="expired">Vencidas</option>
                            <option value="planned">Planificadas</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model.live="showCriticalOnly" 
                                   class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <span class="text-xs font-medium text-gray-700">Solo críticas</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model.live="showExpiringSoon" 
                                   class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                            <span class="text-xs font-medium text-gray-700">Por vencer</span>
                        </label>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-between">
                    <p class="text-[11px] text-gray-500">
                        Configura proveedores, categorías y niveles desde <span class="font-semibold">/admin → Certificaciones → Atributos</span>.
                    </p>
                    <button wire:click="refreshAttributeOptions" 
                            class="px-4 py-2 text-sm font-bold bg-gradient-to-r from-purple-600 to-indigo-500 text-white rounded-lg hover:from-purple-700 hover:to-indigo-600 transition-all shadow-md hover:shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Actualizar Opciones
                    </button>
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

    {{-- Vista: Inventario --}}
    <div x-show="viewMode === 'inventory'" x-transition>
        @if($certifications->count() > 0)
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase">Logo</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Certificación</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Proveedor</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase">Nivel</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase">Validez</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase">Coste</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase">Puntos</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($certifications as $cert)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center">
                                            @if($cert->image_url)
                                                <div class="w-16 h-16 rounded-lg border-2 border-gray-200 bg-white p-2 shadow-sm hover:shadow-md transition-shadow flex items-center justify-center overflow-hidden">
                                                    <img src="{{ $cert->image_url }}" 
                                                         alt="{{ $cert->name }}" 
                                                         class="w-full h-full object-contain">
                                                </div>
                                            @else
                                                <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-blue-100 to-cyan-100 border-2 border-blue-200 flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            @if($cert->is_critical)
                                                <span class="px-2 py-0.5 bg-red-100 text-red-800 text-[10px] font-bold rounded-full">🔴</span>
                                            @endif
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $cert->name }}</p>
                                                @if($cert->code)
                                                    <div class="flex items-center gap-2 mt-0.5">
                                                        <p class="text-xs text-gray-500">{{ $cert->code }}</p>
                                                        @if($cert->official_url)
                                                            <a href="{{ $cert->official_url }}" target="_blank" rel="noopener"
                                                               class="text-gray-400 hover:text-gray-600 transition-colors"
                                                               title="Página oficial">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                                </svg>
                                                            </a>
                                                        @endif
                                                    </div>
                                                @elseif($cert->official_url)
                                                    <a href="{{ $cert->official_url }}" target="_blank" rel="noopener"
                                                       class="text-gray-400 hover:text-gray-600 transition-colors inline-block mt-0.5"
                                                       title="Página oficial">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                        </svg>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-gray-700">{{ $attributeOptions['provider'][$cert->provider] ?? $cert->provider }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($cert->level)
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                                {{ $attributeOptions['level'][$cert->level] ?? $cert->level }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($cert->validity_months)
                                            <span class="text-sm text-gray-700">{{ $cert->validity_months }} meses</span>
                                        @else
                                            <span class="text-xs text-gray-400">Permanente</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($cert->cost)
                                            <span class="text-sm font-semibold text-gray-900">€{{ number_format($cert->cost, 2, ',', '.') }}</span>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-bold rounded-full">
                                            {{ $cert->points_reward }} pts
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <button wire:click="openCertificationModal({{ $cert->id }})" 
                                                    class="p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors"
                                                    title="Editar certificación">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <button wire:click="deleteCertification({{ $cert->id }})" 
                                                    wire:confirm="¿Estás seguro de que quieres eliminar esta certificación?"
                                                    class="p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Eliminar certificación">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-xl shadow-md border border-gray-200">
                <p class="text-sm text-gray-500">No hay certificaciones registradas.</p>
            </div>
        @endif
    </div>

    {{-- Vista: Matriz de Certificaciones del Equipo --}}
    <div x-show="viewMode === 'matrix'" x-transition style="display: none;">
        @if($matrixData && $matrixData->count() > 0)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-600 to-orange-600">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Matriz de Certificaciones del Equipo
                        <span class="text-sm font-normal text-orange-100">({{ $matrixData->count() }} certificaciones activas)</span>
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Certificación</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Proveedor</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wide">Personas</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Usuarios con esta certificación</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($matrixData as $certData)
                                <tr class="hover:bg-gray-50 transition-colors cursor-pointer" 
                                    wire:click="openMatrixDetailModal({{ $certData['certification']->id }})">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1">
                                                <div class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                                    {{ $certData['certification']->name }}
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </div>
                                                @if($certData['certification']->is_critical)
                                                    <span class="inline-flex items-center px-2 py-0.5 mt-1 bg-gradient-to-r from-red-600 to-orange-600 text-white text-[10px] font-bold rounded-full">
                                                        🔴 Crítica
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700">
                                            {{ $attributeOptions['provider'][$certData['certification']->provider] ?? $certData['certification']->provider }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-green-500 to-green-600 text-white text-sm font-bold shadow-md">
                                            {{ $certData['users_count'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($certData['users'] as $userData)
                                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg hover:from-blue-100 hover:to-indigo-100 transition-all cursor-pointer group"
                                                     title="Obtenida: {{ $userData['obtained_at'] ? $userData['obtained_at']->format('d/m/Y') : 'N/A' }}{{ $userData['expires_at'] ? ' | Expira: ' . $userData['expires_at']->format('d/m/Y') : '' }}">
                                                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                                        {{ strtoupper(substr($userData['user']->name, 0, 1)) }}
                                                    </div>
                                                    <span class="text-xs font-semibold text-gray-900 group-hover:text-blue-700">
                                                        {{ $userData['user']->name }}
                                                    </span>
                                                    @if($userData['expires_at'] && $userData['expires_at']->isPast())
                                                        <span class="px-1.5 py-0.5 bg-red-100 text-red-700 text-[10px] font-bold rounded">Exp</span>
                                                    @elseif($userData['expires_at'] && $userData['expires_at']->diffInDays(now()) <= 30)
                                                        <span class="px-1.5 py-0.5 bg-yellow-100 text-yellow-700 text-[10px] font-bold rounded">Exp</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-xl shadow-md border border-gray-200">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm font-semibold text-gray-700 mb-1">No hay certificaciones activas en el equipo</p>
                <p class="text-xs text-gray-500">Las certificaciones aparecerán aquí cuando algún miembro del equipo las obtenga.</p>
            </div>
        @endif

        {{-- Modal: Detalles de Certificación en Matriz --}}
        <div x-show="@entangle('showMatrixDetailModal')" 
             x-cloak
             class="fixed inset-0 z-50 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;">
            @php
                $certDetail = $selectedMatrixCertification ? $matrixData->firstWhere('certification.id', $selectedMatrixCertification) : null;
            @endphp
            <div x-show="@entangle('showMatrixDetailModal') && @js($certDetail !== null)"
                 class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.away="window.livewire.find('{{ $this->getId() }}').call('closeMatrixDetailModal')">
                        @if($certDetail)
                        {{-- Header --}}
                        <div class="bg-gradient-to-r from-red-600 to-orange-600 px-6 py-4 flex items-center justify-between rounded-t-2xl sticky top-0 z-10">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-white mb-1">{{ $certDetail['certification']?->name ?? 'Certificación' }}</h3>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-xs font-semibold rounded-full">
                                        {{ $attributeOptions['provider'][$certDetail['certification']->provider] ?? $certDetail['certification']->provider }}
                                    </span>
                                    @if($certDetail['certification']->is_critical)
                                        <span class="px-3 py-1 bg-red-700/80 backdrop-blur-sm text-white text-xs font-bold rounded-full">
                                            🔴 Crítica
                                        </span>
                                    @endif
                                    <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-xs font-semibold rounded-full">
                                        {{ $certDetail['users_count'] }} {{ $certDetail['users_count'] === 1 ? 'persona' : 'personas' }}
                                    </span>
                                </div>
                            </div>
                            <button wire:click="closeMatrixDetailModal" 
                                    class="text-white/80 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors ml-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Contenido --}}
                        <div class="p-6 bg-white">
                            {{-- Información de la certificación --}}
                            @if($certDetail['certification']->image_url)
                                <div class="flex justify-center mb-6">
                                    <img src="{{ $certDetail['certification']->image_url }}" 
                                         alt="{{ $certDetail['certification']->name }}" 
                                         class="w-32 h-32 object-contain rounded-xl border-2 border-gray-200 bg-white p-3 shadow-md">
                                </div>
                            @endif

                            @if($certDetail['certification']->description)
                                <div class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                    <p class="text-sm text-gray-700">{{ $certDetail['certification']->description }}</p>
                                </div>
                            @endif

                            {{-- Búsqueda y Filtros --}}
                            <div class="mb-6 space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex-1">
                                        <input type="text" 
                                               wire:model.live.debounce.300ms="matrixDetailSearch"
                                               placeholder="Buscar por nombre..."
                                               class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all bg-white">
                                    </div>
                                </div>
                                
                                {{-- Filtros por estado --}}
                                <div class="flex items-center gap-2 flex-wrap">
                                    <button wire:click="$set('matrixDetailFilter', 'all')"
                                            class="px-4 py-2 text-xs font-semibold rounded-lg transition-all
                                                @if($matrixDetailFilter === 'all') bg-gradient-to-r from-purple-600 to-indigo-500 text-white shadow-md
                                                @else bg-gray-100 text-gray-700 hover:bg-gray-200
                                                @endif">
                                        Todas ({{ $certDetail['users_count'] }})
                                    </button>
                                    @php
                                        $expiredCount = collect($certDetail['users'])->filter(function($u) {
                                            return $u['expires_at'] && $u['expires_at']->isPast();
                                        })->count();
                                        $expiringSoonCount = collect($certDetail['users'])->filter(function($u) {
                                            $exp = $u['expires_at'];
                                            return $exp && !$exp->isPast() && $exp->diffInDays(now()) <= 30;
                                        })->count();
                                        $validCount = collect($certDetail['users'])->filter(function($u) {
                                            $exp = $u['expires_at'];
                                            return $exp && !$exp->isPast() && $exp->diffInDays(now()) > 30;
                                        })->count();
                                        $permanentCount = collect($certDetail['users'])->filter(function($u) {
                                            return !$u['expires_at'];
                                        })->count();
                                    @endphp
                                    @if($expiredCount > 0)
                                        <button wire:click="$set('matrixDetailFilter', 'expired')"
                                                class="px-4 py-2 text-xs font-semibold rounded-lg transition-all
                                                    @if($matrixDetailFilter === 'expired') bg-red-600 text-white shadow-md
                                                    @else bg-red-50 text-red-700 hover:bg-red-100 border border-red-200
                                                    @endif">
                                            ❌ Vencidas ({{ $expiredCount }})
                                        </button>
                                    @endif
                                    @if($expiringSoonCount > 0)
                                        <button wire:click="$set('matrixDetailFilter', 'expiring_soon')"
                                                class="px-4 py-2 text-xs font-semibold rounded-lg transition-all
                                                    @if($matrixDetailFilter === 'expiring_soon') bg-gradient-to-r from-yellow-500 to-amber-600 text-white shadow-md
                                                    @else bg-yellow-50 text-yellow-700 hover:bg-yellow-100 border border-yellow-200
                                                    @endif">
                                            ⚠️ Por vencer ({{ $expiringSoonCount }})
                                        </button>
                                    @endif
                                    @if($validCount > 0)
                                        <button wire:click="$set('matrixDetailFilter', 'valid')"
                                                class="px-4 py-2 text-xs font-semibold rounded-lg transition-all
                                                    @if($matrixDetailFilter === 'valid') bg-green-600 text-white shadow-md
                                                    @else bg-green-50 text-green-700 hover:bg-green-100 border border-green-200
                                                    @endif">
                                            ✅ Válidas ({{ $validCount }})
                                        </button>
                                    @endif
                                    @if($permanentCount > 0)
                                        <button wire:click="$set('matrixDetailFilter', 'permanent')"
                                                class="px-4 py-2 text-xs font-semibold rounded-lg transition-all
                                                    @if($matrixDetailFilter === 'permanent') bg-blue-600 text-white shadow-md
                                                    @else bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200
                                                    @endif">
                                            🔒 Permanentes ({{ $permanentCount }})
                                        </button>
                                    @endif
                                </div>
                            </div>

                            {{-- Lista de usuarios filtrada y ordenada --}}
                            @php
                                $filteredUsers = collect($certDetail['users'])->filter(function($userData) {
                                    // Filtro por búsqueda
                                    if ($this->matrixDetailSearch) {
                                        $search = strtolower($this->matrixDetailSearch);
                                        if (strpos(strtolower($userData['user']->name), $search) === false) {
                                            return false;
                                        }
                                    }
                                    
                                    // Filtro por estado
                                    $expiresAt = $userData['expires_at'];
                                    $isExpired = $expiresAt && $expiresAt->isPast();
                                    $expiresSoon = $expiresAt && !$isExpired && $expiresAt->diffInDays(now()) <= 30;
                                    $isValid = $expiresAt && !$isExpired && $expiresAt->diffInDays(now()) > 30;
                                    $isPermanent = !$expiresAt;
                                    
                                    switch ($this->matrixDetailFilter) {
                                        case 'expired':
                                            return $isExpired;
                                        case 'expiring_soon':
                                            return $expiresSoon;
                                        case 'valid':
                                            return $isValid;
                                        case 'permanent':
                                            return $isPermanent;
                                        default:
                                            return true;
                                    }
                                })->sortBy(function($userData) {
                                    // Ordenar por urgencia: vencidas primero, luego por vencer, luego válidas, luego permanentes
                                    $expiresAt = $userData['expires_at'];
                                    if (!$expiresAt) return 4; // Permanentes al final
                                    if ($expiresAt->isPast()) return 1; // Vencidas primero
                                    $daysLeft = $expiresAt->diffInDays(now());
                                    if ($daysLeft <= 30) return 2; // Por vencer segundo
                                    return 3; // Válidas tercero
                                })->values();
                            @endphp
                            
                            @if($filteredUsers->count() > 0)
                                <div class="space-y-3">
                                    <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        Personas con esta certificación
                                        <span class="text-sm font-normal text-gray-500">({{ $filteredUsers->count() }} de {{ $certDetail['users_count'] }})</span>
                                    </h4>
                                    
                                    @foreach($filteredUsers as $userData)
                                    @php
                                        $expiresAt = $userData['expires_at'];
                                        $isExpired = $expiresAt && $expiresAt->isPast();
                                        $expiresSoon = $expiresAt && !$isExpired && $expiresAt->diffInDays(now()) <= 30;
                                        $expiresLater = $expiresAt && !$isExpired && $expiresAt->diffInDays(now()) > 30;
                                    @endphp
                                    <div class="border-2 rounded-xl p-4 hover:shadow-lg transition-all
                                        @if($isExpired) border-red-300 bg-red-50
                                        @elseif($expiresSoon) border-yellow-300 bg-yellow-50
                                        @else border-gray-200 bg-white
                                        @endif">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex items-start gap-3 flex-1">
                                                {{-- Avatar --}}
                                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-lg font-bold shadow-md flex-shrink-0">
                                                    {{ strtoupper(substr($userData['user']->name, 0, 1)) }}
                                                </div>
                                                
                                                {{-- Información del usuario --}}
                                                <div class="flex-1 min-w-0">
                                                    <h5 class="text-base font-bold text-gray-900 mb-1">{{ $userData['user']->name }}</h5>
                                                    @if($userData['user']->internalRole)
                                                        <span class="inline-block px-2 py-0.5 bg-blue-100 text-blue-800 text-[10px] font-medium rounded-full mb-2">
                                                            {{ $userData['user']->internalRole->name }}
                                                        </span>
                                                    @endif
                                                    
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
                                                        {{-- Fecha de obtención --}}
                                                        <div class="flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Obtenida</p>
                                                                <p class="text-sm font-semibold text-gray-900">
                                                                    {{ $userData['obtained_at'] ? $userData['obtained_at']->format('d/m/Y') : 'No especificada' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        
                                                        {{-- Fecha de caducidad --}}
                                                        <div class="flex items-start gap-2">
                                                            @if($expiresAt)
                                                                @if($isExpired)
                                                                    <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                    </svg>
                                                                @elseif($expiresSoon)
                                                                    <svg class="w-4 h-4 text-yellow-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                                    </svg>
                                                                @else
                                                                    <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                    </svg>
                                                                @endif
                                                            @else
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                </svg>
                                                            @endif
                                                            <div>
                                                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Caduca</p>
                                                                <p class="text-sm font-semibold 
                                                                    @if($isExpired) text-red-600
                                                                    @elseif($expiresSoon) text-yellow-600
                                                                    @elseif($expiresAt) text-green-600
                                                                    @else text-gray-600
                                                                    @endif">
                                                                    @if($expiresAt)
                                                                        {{ $expiresAt->format('d/m/Y') }}
                                                                        @if($isExpired)
                                                                            <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-700 text-[10px] font-bold rounded">Vencida</span>
                                                                        @elseif($expiresSoon)
                                                                            <span class="ml-2 px-2 py-0.5 bg-yellow-100 text-yellow-700 text-[10px] font-bold rounded">
                                                                                En {{ $expiresAt->diffInDays(now()) }} días
                                                                            </span>
                                                                        @else
                                                                            <span class="ml-2 px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded">
                                                                                Válida ({{ $expiresAt->diffInDays(now()) }} días restantes)
                                                                            </span>
                                                                        @endif
                                                                    @else
                                                                        <span class="text-gray-500">Permanente</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    {{-- Número de certificado --}}
                                                    @if($userData['certificate_number'])
                                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Número de Certificado</p>
                                                            <p class="text-sm font-mono text-gray-900 bg-gray-50 px-2 py-1 rounded border border-gray-200 inline-block">
                                                                {{ $userData['certificate_number'] }}
                                                            </p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm font-semibold text-gray-700 mb-1">No se encontraron resultados</p>
                                    <p class="text-xs text-gray-500">Intenta ajustar los filtros o la búsqueda</p>
                                </div>
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl flex items-center justify-end">
                            <button wire:click="closeMatrixDetailModal" 
                                    class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700 transition-all shadow-lg">
                                Cerrar
                            </button>
                        </div>
                    </div>
                        @endif
                </div>
        </div>
    </div>

    {{-- Vista: Roadmap Personalizado con Timeline Visual Interactivo --}}
    <div x-data="{ 
        selectedItem: null,
        openModal(item) {
            this.selectedItem = item;
        },
        closeModal() {
            this.selectedItem = null;
        }
    }" x-show="viewMode === 'roadmap'" x-transition style="display: none;">
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200 mb-6">
            <div class="flex items-center gap-4 flex-wrap">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-700 mb-2">Seleccionar Usuario</label>
                    <select wire:model.live="selectedUserId" 
                            class="w-full px-4 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                        <option value="">-- Selecciona un usuario --</option>
                        @foreach($teamUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                @if($selectedUserId)
                    <div class="flex items-center gap-2">
                        <button wire:click="openUserCertificationModal({{ $selectedUserId }}, null, null)" 
                                type="button"
                                class="px-4 py-2 text-sm font-semibold bg-gradient-to-r from-purple-600 to-indigo-500 text-white rounded-lg hover:from-purple-700 hover:to-indigo-600 transition-all flex items-center gap-2 shadow-lg hover:shadow-xl">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Añadir Certificación Individual
                        </button>
                    </div>
                @endif
            </div>
            
            {{-- Información de planes asignados --}}
            @if($selectedUserId && isset($selectedUserPlans) && $selectedUserPlans->count() > 0)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-xs font-semibold text-gray-700 uppercase tracking-wide mb-3">Planes de Certificación Asignados</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($selectedUserPlans as $userPlan)
                            <div class="px-3 py-2 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-blue-900">{{ $userPlan->plan->name }}</span>
                                    <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full
                                        @if($userPlan->status === 'in_progress') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($userPlan->status) }}
                                    </span>
                                    @if($userPlan->progress_percentage > 0)
                                        <span class="text-[10px] font-semibold text-gray-600">{{ number_format($userPlan->progress_percentage, 0) }}%</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        @if($selectedUserId && $roadmapTimeline && $roadmapTimeline->count() > 0)
            @php
                $itemCounter = 0;
            @endphp
            <div class="relative py-16">
                {{-- Timeline Container --}}
                <div class="relative max-w-7xl mx-auto px-6 md:px-12">
                    {{-- Línea vertical del timeline centrada --}}
                    <div class="hidden md:block absolute left-1/2 transform -translate-x-1/2 top-0 bottom-0 w-1 bg-gradient-to-b from-blue-400 via-cyan-400 to-blue-400 z-0 shadow-lg"></div>
                    <div class="block md:hidden absolute left-12 top-0 bottom-0 w-1 bg-gradient-to-b from-blue-400 via-cyan-400 to-blue-400 z-0"></div>
                    
                    {{-- Timeline principal --}}
                    <ol class="relative ml-4 md:ml-0">
                        @foreach($roadmapTimeline as $monthIndex => $monthData)
                            {{-- Header del mes --}}
                            <li class="mb-8 md:mb-12 relative">
                                {{-- Badge del mes --}}
                                <div class="ml-8 md:ml-0 md:absolute md:left-1/2 md:translate-x-6 md:-translate-y-1/2 md:top-0 z-20">
                                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-xl shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-sm font-bold uppercase tracking-wide">{{ $monthData['month_label'] }}</span>
                                    </div>
                                </div>
                                <div class="h-12 md:h-0"></div>
                            </li>

                            {{-- Certificaciones del mes --}}
                            @foreach($monthData['items'] as $itemIndex => $item)
                                @php
                                    $isLeft = ($itemCounter % 2 === 0);
                                    $currentCounter = $itemCounter;
                                    $itemCounter++;
                                @endphp
                                <li class="mb-12 ml-8 md:ml-0 relative" 
                                    x-data="{ 
                                        item: @js($item),
                                        expanded: false,
                                        toggle() {
                                            this.expanded = !this.expanded;
                                        }
                                    }"
                                    {{-- Punto del timeline --}}
                                    <span class="absolute flex items-center justify-center w-8 h-8 
                                        @if($item['status'] === 'active') bg-gradient-to-br from-green-500 to-emerald-600
                                        @elseif($item['status'] === 'in_progress') bg-gradient-to-br from-amber-500 to-yellow-600
                                        @else bg-gradient-to-br from-blue-500 to-cyan-500
                                        @endif
                                        rounded-full -left-[15px] md:left-1/2 md:-translate-x-1/2 md:top-0 border-4 border-white ring-4 ring-blue-50 cursor-pointer transition-all duration-300 hover:scale-110 hover:shadow-lg z-30 shadow-md"
                                        @click="toggle()">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                    
                                    {{-- Contenedor de tarjeta colapsable --}}
                                    <div class="md:flex md:items-start md:justify-center md:gap-8
                                        @if($isLeft) md:flex-row-reverse
                                        @endif">
                                        {{-- Tarjeta --}}
                                        <div class="w-full md:w-6/12 
                                            @if($isLeft) md:pr-8
                                            @else md:pl-8
                                            @endif">
                                            {{-- Estado colapsado: solo imagen --}}
                                            <div x-show="!expanded" 
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 scale-95"
                                                 x-transition:enter-end="opacity-100 scale-100"
                                                 x-transition:leave="transition ease-in duration-150"
                                                 x-transition:leave-start="opacity-100 scale-100"
                                                 x-transition:leave-end="opacity-0 scale-95"
                                                 class="bg-white rounded-2xl shadow-lg p-6 border-2 border-gray-200 hover:border-blue-300 hover:shadow-xl transition-all duration-300 cursor-pointer"
                                                 @click="toggle()">
                                                @if($item['image_url'] ?? null)
                                                    <div class="flex justify-center">
                                                        <img src="{{ $item['image_url'] }}" 
                                                             alt="{{ $item['name'] }}" 
                                                             class="w-32 h-32 object-contain rounded-xl border-2 border-gray-200 bg-white p-3 shadow-md">
                                                    </div>
                                                @else
                                                    <div class="flex justify-center">
                                                        <div class="w-32 h-32 rounded-xl bg-gradient-to-br from-blue-100 to-cyan-100 border-2 border-blue-200 flex items-center justify-center">
                                                            <svg class="w-16 h-16 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            {{-- Estado expandido: imagen izquierda, datos derecha --}}
                                            <div x-show="expanded" 
                                                 x-transition:enter="transition ease-out duration-300"
                                                 x-transition:enter-start="opacity-0 scale-95"
                                                 x-transition:enter-end="opacity-100 scale-100"
                                                 x-transition:leave="transition ease-in duration-200"
                                                 x-transition:leave-start="opacity-100 scale-100"
                                                 x-transition:leave-end="opacity-0 scale-95"
                                                 class="bg-white rounded-2xl shadow-xl p-6 border-2 border-blue-200">
                                                <div class="flex items-start gap-6">
                                                    {{-- Imagen a la izquierda --}}
                                                    <div class="flex-shrink-0">
                                                        @if($item['image_url'] ?? null)
                                                            <img src="{{ $item['image_url'] }}" 
                                                                 alt="{{ $item['name'] }}" 
                                                                 class="w-32 h-32 object-contain rounded-xl border-2 border-gray-200 bg-white p-3 shadow-md">
                                                        @else
                                                            <div class="w-32 h-32 rounded-xl bg-gradient-to-br from-blue-100 to-cyan-100 border-2 border-blue-200 flex items-center justify-center">
                                                                <svg class="w-16 h-16 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    {{-- Datos a la derecha --}}
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-start justify-between mb-4">
                                                            <div class="flex-1">
                                                                <h4 class="text-xl font-bold text-gray-900 mb-2 leading-tight">
                                                                    {{ $item['name'] }}
                                                                </h4>
                                                                <div class="flex items-center gap-2 flex-wrap">
                                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 
                                                                        @if($item['status'] === 'active') bg-green-100 text-green-800 border border-green-200
                                                                        @elseif($item['status'] === 'in_progress') bg-amber-100 text-amber-800 border border-amber-200
                                                                        @else bg-blue-100 text-blue-800 border border-blue-200
                                                                        @endif
                                                                        text-xs font-bold rounded-full">
                                                                        @if($item['status'] === 'active') 
                                                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                                            Activa
                                                                        @elseif($item['status'] === 'in_progress') 
                                                                            <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                                            En Progreso
                                                                        @else 
                                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                                            Planificada
                                                                        @endif
                                                                    </span>
                                                                    @if(isset($item['is_from_plan']) && $item['is_from_plan'])
                                                                        <span class="px-3 py-1.5 bg-gradient-to-r from-purple-500 to-indigo-500 text-white text-xs font-bold rounded-full shadow-sm flex items-center gap-1">
                                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                                            </svg>
                                                                            Del Plan
                                                                        </span>
                                                                    @else
                                                                        <span class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-bold rounded-full border border-gray-300">
                                                                            Individual
                                                                        </span>
                                                                    @endif
                                                                    @if($item['is_critical'])
                                                                        <span class="px-3 py-1.5 bg-gradient-to-r from-red-500 to-orange-500 text-white text-xs font-bold rounded-full shadow-sm">Crítica</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <button @click="toggle()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        
                                                        {{-- Información detallada --}}
                                                        <div class="space-y-3">
                                                            <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-gray-50 to-blue-50 rounded-lg border border-gray-200">
                                                                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br 
                                                                    @if($item['status'] === 'active') from-green-500 to-emerald-600
                                                                    @elseif($item['status'] === 'in_progress') from-amber-500 to-yellow-600
                                                                    @else from-blue-500 to-cyan-500
                                                                    @endif
                                                                    flex items-center justify-center shadow-sm">
                                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                                    </svg>
                                                                </div>
                                                                <div class="flex-1 min-w-0">
                                                                    <p class="text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Proveedor</p>
                                                                    <p class="text-sm font-bold text-gray-900">
                                                                        {{ $attributeOptions['provider'][$item['provider']] ?? $item['provider'] }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-gray-50 to-blue-50 rounded-lg border border-gray-200">
                                                                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-sm">
                                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                    </svg>
                                                                </div>
                                                                <div class="flex-1 min-w-0">
                                                                    <p class="text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Fecha Planificada</p>
                                                                    <p class="text-sm font-bold text-blue-600">
                                                                        {{ $item['date_formatted'] ?? $item['date'] }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            
                                                            @if(isset($item['is_from_plan']) && $item['is_from_plan'] && isset($item['plan_name']))
                                                                <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg border border-purple-200">
                                                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center shadow-sm">
                                                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                                        </svg>
                                                                    </div>
                                                                    <div class="flex-1 min-w-0">
                                                                        <p class="text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Plan de Certificación</p>
                                                                        <p class="text-sm font-bold text-purple-700">
                                                                            {{ $item['plan_name'] }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        
                                                        {{-- Botones de acción --}}
                                                        <div class="flex items-center justify-end gap-2 mt-4 pt-4 border-t border-gray-200">
                                                            @if($item['user_certification_id'])
                                                                <button wire:click="openUserCertificationModal({{ $selectedUserId }}, {{ $item['user_certification_id'] }})" 
                                                                        class="px-4 py-2 text-xs font-semibold bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all flex items-center gap-2">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                                    </svg>
                                                                    Editar
                                                                </button>
                                                            @else
                                                                <button wire:click="openUserCertificationModal({{ $selectedUserId }}, null, {{ $item['certification']->id }})" 
                                                                        class="px-4 py-2 text-xs font-semibold bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all flex items-center gap-2">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                                    </svg>
                                                                    Crear Certificación
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        @elseif($selectedUserId)
            <div class="text-center py-12 bg-white rounded-xl shadow-md border border-gray-200">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-sm text-gray-500 mb-2">Este usuario no tiene certificaciones planificadas.</p>
                <button wire:click="openUserCertificationModal({{ $selectedUserId }})" 
                        class="mt-4 px-4 py-2 text-sm font-semibold bg-gradient-to-r from-purple-600 to-indigo-500 text-white rounded-lg hover:from-purple-700 hover:to-indigo-600">
                    Añadir Certificación al Roadmap
                </button>
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-xl shadow-md border border-gray-200">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-sm text-gray-500">Selecciona un usuario para ver su roadmap personalizado de certificaciones.</p>
            </div>
        @endif
        
        {{-- Modal de Detalles --}}
        <div x-show="selectedItem" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4"
             @click.self="closeModal()"
             style="display: none;"
             x-cloak>
            <div x-show="selectedItem"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
                 @click.stop>
                        {{-- Header del modal --}}
                        <div class="sticky top-0 z-10 rounded-t-2xl p-6"
                             :class="{
                                 'bg-gradient-to-r from-green-500 to-emerald-500': selectedItem?.status === 'active',
                                 'bg-gradient-to-r from-amber-500 to-yellow-500': selectedItem?.status === 'in_progress',
                                 'bg-gradient-to-r from-blue-500 to-cyan-500': selectedItem?.status !== 'active' && selectedItem?.status !== 'in_progress'
                             }">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-2xl font-bold text-white mb-2" x-text="selectedItem?.name"></h3>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-sm font-semibold rounded-full" x-text="
                                            selectedItem?.status === 'active' ? '✅ Activa' :
                                            selectedItem?.status === 'in_progress' ? '⏳ En Progreso' : '📅 Planificada'
                                        "></span>
                                        <span x-show="selectedItem?.is_critical" 
                                              class="px-3 py-1 bg-blue-600/80 backdrop-blur-sm text-white text-sm font-bold rounded-full">🔴 Crítica</span>
                                    </div>
                                </div>
                                <button @click="closeModal()" class="text-white/80 hover:text-white hover:bg-white/10 p-2 rounded-lg transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        {{-- Contenido del modal --}}
                        <div class="p-6 space-y-6">
                            <div x-show="selectedItem?.image_url" class="flex justify-center">
                                <img :src="selectedItem?.image_url" 
                                     :alt="selectedItem?.name" 
                                     class="w-32 h-32 object-contain rounded-xl border-2 border-gray-200 bg-white p-2 shadow-md">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                                    <p class="text-xs font-medium text-blue-600 uppercase tracking-wide mb-1">Proveedor</p>
                                    <p class="text-base font-bold text-gray-900" x-text="selectedItem?.provider_label || selectedItem?.provider"></p>
                                </div>
                                
                                <div class="p-4 bg-cyan-50 rounded-xl border border-cyan-200">
                                    <p class="text-xs font-medium text-cyan-600 uppercase tracking-wide mb-1">Fecha Planificada</p>
                                    <p class="text-base font-bold text-cyan-600" x-text="selectedItem?.date_formatted || 'Sin fecha'"></p>
                                </div>
                                
                                <div x-show="selectedItem?.priority > 0" class="p-4 bg-amber-50 rounded-xl border border-amber-200">
                                    <p class="text-xs font-medium text-amber-600 uppercase tracking-wide mb-1">Prioridad</p>
                                    <p class="text-base font-bold text-amber-600" x-text="'Nivel ' + (selectedItem?.priority || 0)"></p>
                                </div>
                            </div>
                            
                            <div x-show="selectedItem?.notes" class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <p class="text-xs font-medium text-gray-600 uppercase tracking-wide mb-2">Notas</p>
                                <p class="text-sm text-gray-700 whitespace-pre-wrap" x-text="selectedItem?.notes || ''"></p>
                            </div>
                        </div>
                        
                        {{-- Footer del modal --}}
                        <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 rounded-b-2xl px-6 py-4 flex items-center justify-end gap-3">
                            <button @click="closeModal()" 
                                    class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 transition-colors">
                                Cerrar
                            </button>
                            <button @click="closeModal(); $wire.openUserCertificationModal({{ $selectedUserId }}, selectedItem?.id)" 
                                    class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-purple-600 to-indigo-500 text-white rounded-lg hover:from-purple-700 hover:to-indigo-600 transition-all shadow-lg hover:shadow-xl">
                                Editar Certificación
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Vista: Planes de Certificación --}}
    <div x-show="viewMode === 'plans'" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         style="display: none;" 
         x-cloak
         x-data="{ 
             planViewMode: 'grouped', // grid, list, grouped
             expandedGroups: [],
             toggleGroup(groupKey) {
                 if (this.expandedGroups.includes(groupKey)) {
                     this.expandedGroups = this.expandedGroups.filter(key => key !== groupKey);
                 } else {
                     this.expandedGroups.push(groupKey);
                 }
             },
             isGroupExpanded(groupKey) {
                 return this.expandedGroups.includes(groupKey);
             }
         }">
        
        {{-- Header con acciones y filtros --}}
        <div class="mb-6 space-y-4">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-orange-500 flex items-center justify-center text-white text-2xl shadow-lg">
                            📋
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent">
                                Planes de Certificación
                            </h3>
                            <p class="text-sm text-gray-600 mt-0.5">
                                Define y gestiona planes personalizados por línea de servicio y rol interno
                                @if(isset($certificationPlans) && $certificationPlans->count() > 0)
                                    <span class="ml-2 px-2.5 py-0.5 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                        {{ $certificationPlans->count() }} {{ $certificationPlans->count() === 1 ? 'plan' : 'planes' }}
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <button wire:click="openPlanModal()" 
                        type="button"
                        class="w-full lg:w-auto px-6 py-3 text-sm font-bold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nuevo Plan
                </button>
            </div>

            {{-- Filtros y búsqueda --}}
            <div class="bg-white rounded-xl shadow-md p-4 border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Búsqueda --}}
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wide">Buscar</label>
                        <input type="text" 
                               wire:model.live.debounce.300ms="planSearch"
                               placeholder="Buscar por nombre, línea de servicio, rol..."
                               class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
                    </div>

                    {{-- Filtro por línea de servicio --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wide">Línea de Servicio</label>
                        <select wire:model.live="planFilterServiceLine" 
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
                            <option value="">Todas</option>
                            @foreach($serviceLines as $serviceLine)
                                <option value="{{ $serviceLine->id }}">{{ $serviceLine->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filtro por rol interno --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wide">Rol Interno</label>
                        <select wire:model.live="planFilterInternalRole" 
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
                            <option value="">Todos</option>
                            @foreach($internalRoles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Filtro de estado y vista --}}
                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                    <div class="flex items-center gap-3">
                        <label class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Estado:</label>
                        <div class="flex items-center gap-2">
                            <button wire:click="$set('planFilterStatus', 'all')"
                                    :class="$wire.planFilterStatus === 'all' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                    class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all">
                                Todos
                            </button>
                            <button wire:click="$set('planFilterStatus', 'active')"
                                    :class="$wire.planFilterStatus === 'active' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                    class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all">
                                Activos
                            </button>
                            <button wire:click="$set('planFilterStatus', 'inactive')"
                                    :class="$wire.planFilterStatus === 'inactive' ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                    class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all">
                                Inactivos
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Vista:</label>
                        <button @click="planViewMode = 'grouped'"
                                :class="planViewMode === 'grouped' ? 'bg-gradient-to-r from-red-600 to-orange-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all transform hover:scale-105"
                                title="Vista agrupada">
                            📊 Agrupada
                        </button>
                        <button @click="planViewMode = 'grid'"
                                :class="planViewMode === 'grid' ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                class="p-2 rounded-lg transition-all transform hover:scale-105"
                                title="Vista de cuadrícula">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </button>
                        <button @click="planViewMode = 'list'"
                                :class="planViewMode === 'list' ? 'bg-gradient-to-r from-purple-600 to-purple-700 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                class="p-2 rounded-lg transition-all transform hover:scale-105"
                                title="Vista de lista">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($certificationPlans) && $certificationPlans->count() > 0)
            @php
                // Agrupar planes por línea de servicio para la vista agrupada
                $groupedPlans = $certificationPlans->groupBy('service_line_id');
            @endphp

            {{-- Vista Agrupada por Línea de Servicio (DEFAULT) --}}
            <div x-show="planViewMode === 'grouped'" 
                 class="space-y-6"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0">
                @foreach($groupedPlans as $serviceLineId => $plans)
                    @php
                        $serviceLine = $plans->first()->serviceLine;
                    @endphp
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition-all duration-300">
                        {{-- Header del grupo --}}
                        <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-4 cursor-pointer"
                             @click="toggleGroup('service-{{ $serviceLineId }}')">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-lg bg-white/20 flex items-center justify-center text-white font-bold text-xl">
                                        {{ strtoupper(substr($serviceLine->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white">{{ $serviceLine->name }}</h3>
                                        <p class="text-sm text-blue-100 mt-0.5">{{ $plans->count() }} {{ $plans->count() === 1 ? 'plan' : 'planes' }} de certificación</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="text-right bg-white/20 rounded-lg px-4 py-2">
                                        <p class="text-2xl font-bold text-white">{{ $plans->sum(fn($p) => $p->certifications->count()) }}</p>
                                        <p class="text-xs text-blue-100 uppercase tracking-wide font-semibold">certificaciones</p>
                                    </div>
                                    <svg class="w-6 h-6 text-white transition-transform duration-300" 
                                         :class="isGroupExpanded('service-{{ $serviceLineId }}') ? 'rotate-180' : ''"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Contenido del grupo --}}
                        <div x-show="isGroupExpanded('service-{{ $serviceLineId }}')" 
                             x-collapse
                             class="p-6 bg-gray-50">
                            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
                                @foreach($plans as $plan)
                                    <div wire:click="viewPlanDetail({{ $plan->id }})" 
                                         class="group bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 border border-gray-200 hover:border-red-300 overflow-hidden cursor-pointer">
                                        <div class="p-5">
                                            {{-- Header del plan --}}
                                            <div class="flex items-start justify-between mb-4">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <h4 class="text-base font-bold text-gray-900 group-hover:text-red-700 transition-colors">{{ $plan->name }}</h4>
                                                        @if($plan->is_active)
                                                            <span class="relative flex h-2.5 w-2.5">
                                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <span class="inline-block px-2.5 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">
                                                        {{ $plan->internalRole->name }}
                                                    </span>
                                                </div>
                                                <button wire:click.stop="openPlanModal({{ $plan->id }})" 
                                                        class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-all"
                                                        title="Editar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                            </div>

                                            {{-- Estadísticas --}}
                                            <div class="grid grid-cols-2 gap-3 mb-4">
                                                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-3 text-white">
                                                    <p class="text-[10px] font-bold uppercase tracking-wider opacity-90">Certificaciones</p>
                                                    <p class="text-2xl font-bold mt-1">{{ $plan->certifications->count() }}</p>
                                                </div>
                                                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-3 text-white">
                                                    <p class="text-[10px] font-bold uppercase tracking-wider opacity-90">Usuarios</p>
                                                    <p class="text-2xl font-bold mt-1">{{ $plan->userPlans->count() }}</p>
                                                </div>
                                            </div>

                                            {{-- Preview de certificaciones --}}
                                            @if($plan->certifications->count() > 0)
                                                <div class="space-y-2">
                                                    @foreach($plan->certifications->take(2)->sortBy('order') as $planCert)
                                                        <div class="flex items-center gap-2 p-2 bg-gray-50 rounded border border-gray-200">
                                                            <span class="text-xs font-bold text-gray-500">#{{ $planCert->order }}</span>
                                                            <span class="text-xs font-semibold text-gray-700 flex-1 truncate">{{ $planCert->certification->name }}</span>
                                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded
                                                                @if($planCert->priority <= 2) bg-red-100 text-red-800
                                                                @elseif($planCert->priority == 3) bg-yellow-100 text-yellow-800
                                                                @else bg-gray-100 text-gray-800
                                                                @endif">
                                                                {{ $planCert->priority_label }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                    @if($plan->certifications->count() > 2)
                                                        <p class="text-xs text-center text-gray-500 font-semibold">+{{ $plan->certifications->count() - 2 }} más</p>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Vista de cuadrícula --}}
            <div x-show="planViewMode === 'grid'" 
                 class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100">
                @foreach($certificationPlans as $plan)
                    <div wire:click="viewPlanDetail({{ $plan->id }})" 
                         class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border-2 border-transparent hover:border-red-400 transform hover:-translate-y-2 hover:scale-[1.02] overflow-hidden cursor-pointer">
                        {{-- Borde superior con gradiente --}}
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-red-600 via-orange-500 to-yellow-500"></div>
                        
                        <div class="relative p-6">
                            {{-- Header con icono --}}
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3 flex-1">
                                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-red-500 to-orange-500 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                        📋
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h4 class="text-lg font-bold text-gray-900 group-hover:text-red-700 transition-colors">{{ $plan->name }}</h4>
                                            @if($plan->is_active)
                                                <span class="relative flex h-3 w-3">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex flex-wrap items-center gap-2 mt-2">
                                            <span class="px-3 py-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-bold rounded-full shadow-md">
                                                {{ $plan->serviceLine->name }}
                                            </span>
                                            <span class="px-3 py-1 bg-gradient-to-r from-purple-500 to-purple-600 text-white text-xs font-bold rounded-full shadow-md">
                                                {{ $plan->internalRole->name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <button wire:click.stop="openPlanModal({{ $plan->id }})" 
                                        class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                        title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Estadísticas --}}
                            <div class="grid grid-cols-2 gap-3 mt-4">
                                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 text-white shadow-lg">
                                    <p class="text-[10px] font-bold uppercase tracking-wider opacity-90">Certificaciones</p>
                                    <p class="text-3xl font-bold mt-1">{{ $plan->certifications->count() }}</p>
                                </div>
                                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-4 text-white shadow-lg">
                                    <p class="text-[10px] font-bold uppercase tracking-wider opacity-90">Usuarios</p>
                                    <p class="text-3xl font-bold mt-1">{{ $plan->userPlans->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Vista de lista --}}
            <div x-show="planViewMode === 'list'" 
                 class="space-y-4"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-4"
                 x-transition:enter-end="opacity-100 transform translate-x-0">
                @foreach($certificationPlans as $plan)
                    <div wire:click="viewPlanDetail({{ $plan->id }})" 
                         class="group relative bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 border-l-4 
                                @if($plan->is_active) border-red-500 @else border-gray-400 @endif
                                transform hover:-translate-x-1 cursor-pointer">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-6 flex-1">
                                    <div class="flex-shrink-0">
                                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-red-500 to-orange-500 flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                                            📋
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h4 class="text-lg font-bold text-gray-900 group-hover:text-red-700 transition-colors">{{ $plan->name }}</h4>
                                            @if($plan->is_active)
                                                <span class="relative flex h-3 w-3">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="px-3 py-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-bold rounded-full shadow-md">
                                                {{ $plan->serviceLine->name }}
                                            </span>
                                            <span class="px-3 py-1 bg-gradient-to-r from-purple-500 to-purple-600 text-white text-xs font-bold rounded-full shadow-md">
                                                {{ $plan->internalRole->name }}
                                            </span>
                                            <span class="px-3 py-1 bg-gradient-to-r from-green-500 to-green-600 text-white text-xs font-bold rounded-full shadow-md">
                                                {{ $plan->certifications->count() }} certs
                                            </span>
                                            <span class="px-3 py-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white text-xs font-bold rounded-full shadow-md">
                                                {{ $plan->userPlans->count() }} usuarios
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <button wire:click.stop="openPlanModal({{ $plan->id }})" 
                                        class="px-4 py-2 text-sm font-semibold bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all shadow-md ml-4">
                                    Editar
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Estado vacío mejorado --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-gray-50 via-white to-gray-50 rounded-2xl shadow-xl border-2 border-dashed border-gray-300">
                <div class="absolute inset-0 bg-gradient-to-r from-red-500/5 via-orange-500/5 to-yellow-500/5"></div>
                <div class="relative text-center py-16 px-6">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-red-500 to-orange-500 mb-6 shadow-2xl transform hover:scale-110 transition-transform duration-300">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent mb-2">
                        ¡Crea tu primer plan de certificación!
                    </h3>
                    <p class="text-sm text-gray-600 mb-1">Define planes personalizados por línea de servicio y rol interno</p>
                    <p class="text-xs text-gray-500 mb-8">Los planes se asignarán automáticamente a los usuarios correspondientes</p>
                    <button wire:click="openPlanModal()" 
                            class="px-8 py-4 text-base font-bold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700 transition-all shadow-2xl hover:shadow-red-500/50 flex items-center gap-3 mx-auto transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Crear Primer Plan
                    </button>
                </div>
            </div>
        @endif
    </div>

    {{-- Modal: Crear/Editar Certificación --}}
    <div x-show="@entangle('showCertificationModal')" 
         x-cloak
         class="fixed inset-0 z-50 bg-gray-900/40 flex items-center justify-center p-4" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
             x-show="@entangle('showCertificationModal')"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
                <div class="bg-gradient-to-r from-purple-600 to-indigo-500 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                    <h3 class="text-xl font-bold text-white">
                        {{ $certificationId ? 'Editar Certificación' : 'Nueva Certificación' }}
                    </h3>
                    <button wire:click="closeCertificationModal" class="text-white/80 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                            <input type="text" wire:model="certificationName" 
                                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            @error('certificationName') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Código</label>
                            <input type="text" wire:model="certificationCode" 
                                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            @error('certificationCode') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Proveedor *</label>
                            <select wire:model="certificationProvider" 
                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">-- Selecciona --</option>
                                @foreach($attributeOptions['provider'] ?? [] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('certificationProvider') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                            <select wire:model="certificationCategory" 
                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">-- Selecciona --</option>
                                @foreach($attributeOptions['category'] ?? [] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nivel</label>
                            <select wire:model="certificationLevel" 
                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">-- Selecciona --</option>
                                @foreach($attributeOptions['level'] ?? [] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL Oficial</label>
                            <input type="url" wire:model="certificationUrl" 
                                   placeholder="https://..."
                                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            @error('certificationUrl') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Imagen/Logo de la Certificación</label>
                            @if($certificationImageUrl)
                                <div class="mb-2">
                                    <img src="{{ $certificationImageUrl }}" alt="Imagen actual" class="w-24 h-24 object-contain rounded-lg border-2 border-gray-200">
                                </div>
                            @endif
                            <input type="file" wire:model="certificationImage" accept="image/*"
                                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            @error('certificationImage') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">Tamaño máximo: 2MB. Formatos: JPG, PNG, GIF</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Validez (meses)</label>
                            <input type="number" wire:model="certificationValidityMonths" 
                                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Coste (€)</label>
                            <input type="number" step="0.01" wire:model="certificationCost" 
                                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Puntos de Gamificación</label>
                            <input type="number" wire:model="certificationPointsReward" 
                                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dificultad (1-10)</label>
                            <input type="number" min="1" max="10" wire:model="certificationDifficultyScore" 
                                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Valor (1-10)</label>
                            <input type="number" min="1" max="10" wire:model="certificationValueScore" 
                                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea wire:model="certificationDescription" rows="3"
                                  class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"></textarea>
                    </div>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="certificationIsCritical" 
                                   class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <span class="text-sm font-medium text-gray-700">Certificación Crítica</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="certificationIsInternal" 
                                   class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <span class="text-sm font-medium text-gray-700">Certificación Interna</span>
                        </label>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl flex items-center justify-end gap-3">
                    <button wire:click="closeCertificationModal" 
                            class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border border-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="saveCertification" 
                            class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-purple-600 to-indigo-500 text-white rounded-xl hover:from-purple-700 hover:to-indigo-600">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Cropper de Imagen de Certificación --}}
    <div x-show="@entangle('showCertificationCropper')" 
         x-data="certificationCropperModal()" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="closeModal()"
         class="fixed inset-0 z-[100] bg-gray-900/80 backdrop-blur-sm flex items-center justify-center p-4"
         style="display: none; position: fixed; z-index: 9999;"
         wire:ignore>
        <div x-show="@entangle('showCertificationCropper') && @js($certificationImage !== null)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.away="closeModal()"
             class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto relative z-[101]"
             style="position: relative; z-index: 10000;">
                
                {{-- Header del Modal --}}
                <div class="bg-gradient-to-r from-purple-600 to-indigo-500 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                    <h3 class="text-xl font-bold text-white">Recorta el logo de la certificación</h3>
                    <button @click="closeModal()" 
                            class="text-white/80 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Contenido del Modal --}}
                @if($certificationImage)
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-4">Ajusta el recuadro para encuadrar el logo perfectamente. Puedes hacer zoom y mover la imagen.</p>
                    
                    <div class="mb-6" style="max-height: 500px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                        <img id="cropper-certification-image" 
                             src="{{ $certificationImage->temporaryUrl() }}" 
                             alt="Imagen a recortar"
                             style="max-width: 100%; max-height: 500px; display: block;">
                    </div>
                </div>
                @endif

                {{-- Footer del Modal --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl flex items-center justify-end gap-3">
                    <button @click="closeModal()" 
                            wire:click="cancelCertificationCrop"
                            class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border border-gray-200">
                        Cancelar
                    </button>
                    <button @click="cropAndSave()" 
                            class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-purple-600 to-indigo-500 text-white rounded-xl hover:from-purple-700 hover:to-indigo-600 transition-all shadow-lg">
                        Guardar logo
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Asignar Certificación a Usuario --}}
    <div x-show="@entangle('showUserCertificationModal')" 
         x-cloak
         class="fixed inset-0 z-50 bg-gray-900/40 flex items-center justify-center p-4" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
             x-show="@entangle('showUserCertificationModal')"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
                <div class="bg-gradient-to-r from-purple-600 to-indigo-500 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                    <h3 class="text-xl font-bold text-white">
                        {{ $userCertificationId ? 'Editar Certificación de Usuario' : 'Asignar Certificación' }}
                    </h3>
                    <button wire:click="closeUserCertificationModal" class="text-white/80 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Usuario *</label>
                            <select wire:model="userCertificationUserId" 
                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">-- Selecciona --</option>
                                @foreach($teamUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('userCertificationUserId') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Certificación *</label>
                            <select wire:model="userCertificationCertificationId" 
                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">-- Selecciona --</option>
                                @foreach($certifications as $cert)
                                    <option value="{{ $cert->id }}">{{ $cert->name }}</option>
                                @endforeach
                            </select>
                            @error('userCertificationCertificationId') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                            <select wire:model="userCertificationStatus" 
                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="active">Activa</option>
                                <option value="expired">Vencida</option>
                                <option value="revoked">Revocada</option>
                                <option value="in_progress">En Progreso</option>
                                <option value="planned">Planificada</option>
                            </select>
                            @error('userCertificationStatus') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad (0-5)</label>
                            <input type="number" min="0" max="5" wire:model="userCertificationPriority" 
                                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Obtención</label>
                            <input type="date" wire:model="userCertificationObtainedAt" 
                                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Vencimiento</label>
                            <input type="date" wire:model="userCertificationExpiresAt" 
                                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Planificada</label>
                            <input type="date" wire:model="userCertificationPlannedDate" 
                                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Número de Certificado</label>
                            <input type="text" wire:model="userCertificationCertificateNumber" 
                                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                        <textarea wire:model="userCertificationNotes" rows="3"
                                  class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl flex items-center justify-end gap-3">
                    <button wire:click="closeUserCertificationModal" 
                            class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border border-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="saveUserCertification" 
                            class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-purple-600 to-indigo-500 text-white rounded-xl hover:from-purple-700 hover:to-indigo-600">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Crear/Editar Plan de Certificación --}}
    <div x-show="@entangle('showPlanModal')" 
         x-cloak
         class="fixed inset-0 z-50 bg-gray-900/40 flex items-center justify-center p-4" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto"
             x-show="@entangle('showPlanModal')"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
                <div class="bg-gradient-to-r from-red-600 to-orange-600 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                    <h3 class="text-xl font-bold text-white">
                        {{ $planId ? 'Editar Plan de Certificación' : 'Nuevo Plan de Certificación' }}
                    </h3>
                    <button wire:click="closePlanModal" class="text-white/80 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Línea de Servicio *</label>
                            <select wire:model.live="planServiceLineId" 
                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">-- Selecciona --</option>
                                @foreach($serviceLines as $serviceLine)
                                    <option value="{{ $serviceLine->id }}">{{ $serviceLine->name }}</option>
                                @endforeach
                            </select>
                            @error('planServiceLineId') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rol Interno *</label>
                            <select wire:model="planInternalRoleId" 
                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    @if(!$planServiceLineId) disabled @endif>
                                <option value="">-- Selecciona primero una línea de servicio --</option>
                                @if($planServiceLineId && $internalRoles->count() > 0)
                                    @foreach($internalRoles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                @elseif($planServiceLineId && $internalRoles->count() === 0)
                                    <option value="" disabled>No hay roles internos para esta línea de servicio</option>
                                @endif
                            </select>
                            @error('planInternalRoleId') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                            @if($planServiceLineId && $internalRoles->count() === 0)
                                <p class="text-xs text-amber-600 mt-1">⚠️ No hay roles internos definidos para el área de esta línea de servicio</p>
                            @endif
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Plan</label>
                            <input type="text" wire:model="planName" 
                                   placeholder="Se generará automáticamente si se deja vacío"
                                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            @error('planName') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                            <textarea wire:model="planDescription" rows="3"
                                      class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"></textarea>
                        </div>
                        <div>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" wire:model="planIsActive" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                <span class="text-sm font-medium text-gray-700">Plan activo</span>
                            </label>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-bold text-gray-900">Certificaciones del Plan</h4>
                            <button wire:click="addCertificationToPlan" 
                                    class="px-4 py-2 text-sm font-semibold bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Añadir Certificación
                            </button>
                        </div>

                        @if(count($planCertifications) > 0)
                            <div class="space-y-4">
                                @foreach($planCertifications as $index => $planCert)
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Certificación *</label>
                                                <select wire:model="planCertifications.{{ $index }}.certification_id" 
                                                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                                    <option value="">-- Selecciona --</option>
                                                    @foreach($certifications as $cert)
                                                        <option value="{{ $cert->id }}">{{ $cert->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error("planCertifications.{$index}.certification_id") <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad *</label>
                                                <select wire:model="planCertifications.{{ $index }}.priority" 
                                                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                                    <option value="1">1 - Crítica</option>
                                                    <option value="2">2 - Alta</option>
                                                    <option value="3">3 - Media</option>
                                                    <option value="4">4 - Baja</option>
                                                    <option value="5">5 - Opcional</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Orden *</label>
                                                <input type="number" wire:model="planCertifications.{{ $index }}.order" min="1"
                                                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Meses objetivo</label>
                                                <input type="number" wire:model="planCertifications.{{ $index }}.target_months" min="0"
                                                       placeholder="Meses desde inicio del plan"
                                                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha objetivo</label>
                                                <input type="date" wire:model="planCertifications.{{ $index }}.target_date"
                                                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <label class="flex items-center gap-2">
                                                    <input type="checkbox" wire:model="planCertifications.{{ $index }}.is_flexible" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                                    <span class="text-sm text-gray-700">Fecha flexible</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mt-3 flex justify-end">
                                            <button wire:click="removeCertificationFromPlan({{ $index }})" 
                                                    class="px-3 py-1.5 text-sm font-semibold text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                                Eliminar
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 text-center py-4">No hay certificaciones añadidas al plan. Haz clic en "Añadir Certificación" para comenzar.</p>
                        @endif
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl flex items-center justify-end gap-3">
                    <button wire:click="closePlanModal" 
                            class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border border-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="savePlan" 
                            class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700 transition-all shadow-lg">
                        {{ $planId ? 'Actualizar Plan' : 'Crear Plan' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Detalles del Plan --}}
    <div x-show="@entangle('showPlanDetailModal')" 
         x-cloak
         class="fixed inset-0 z-50 bg-gray-900/40 flex items-center justify-center p-4" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto"
             x-show="@entangle('showPlanDetailModal') && @js($selectedPlan !== null)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <div class="bg-gradient-to-r from-red-600 to-orange-600 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                <h3 class="text-xl font-bold text-white">{{ $selectedPlan?->name ?? 'Plan de Certificación' }}</h3>
                <button wire:click="closePlanDetailModal" class="text-white/80 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            @if($selectedPlan)
            <div class="p-6 space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Línea de Servicio</p>
                                <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedPlan->serviceLine?->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Rol Interno</p>
                                <p class="text-sm font-bold text-gray-900 mt-1">{{ $selectedPlan->internalRole?->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @if($selectedPlan->description)
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Descripción</p>
                                <p class="text-sm text-gray-700">{{ $selectedPlan->description }}</p>
                            </div>
                        @endif

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Certificaciones ({{ $selectedPlan->certifications->count() }})</p>
                            <div class="space-y-3">
                                @foreach($selectedPlan->certifications->sortBy('order') as $planCert)
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3">
                                                    <span class="text-xs font-bold text-gray-500">#{{ $planCert->order }}</span>
                                                    <h5 class="text-sm font-bold text-gray-900">{{ $planCert->certification->name }}</h5>
                                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full
                                                        @if($planCert->priority <= 2) bg-red-100 text-red-800
                                                        @elseif($planCert->priority == 3) bg-yellow-100 text-yellow-800
                                                        @else bg-gray-100 text-gray-800
                                                        @endif">
                                                        {{ $planCert->priority_label }}
                                                    </span>
                                                </div>
                                                @if($planCert->target_months || $planCert->target_date)
                                                    <p class="text-xs text-gray-600 mt-2">
                                                        @if($planCert->target_months)
                                                            Objetivo: {{ $planCert->target_months }} meses desde inicio
                                                        @elseif($planCert->target_date)
                                                            Objetivo: {{ $planCert->target_date->format('d/m/Y') }}
                                                        @endif
                                                        @if($planCert->is_flexible)
                                                            <span class="text-gray-500">(flexible)</span>
                                                        @endif
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Usuarios con este plan ({{ $selectedPlan->userPlans->count() }})</p>
                            @if($selectedPlan->userPlans->count() > 0)
                                <div class="space-y-4">
                                    @foreach($selectedPlan->userPlans as $userPlan)
                                        @php
                                            // Obtener certificaciones activas del usuario
                                            $userActiveCertIds = $userPlan->user->userCertifications
                                                ->where('status', 'active')
                                                ->pluck('certification_id')
                                                ->toArray();
                                        @endphp
                                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden"
                                             x-data="{ expanded: false }">
                                            {{-- Header del usuario --}}
                                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200 cursor-pointer hover:bg-gray-200 transition-colors"
                                                 @click="expanded = !expanded">
                                                <div class="flex items-center gap-3 flex-1">
                                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm">
                                                        {{ substr($userPlan->user->name, 0, 1) }}
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-sm font-bold text-gray-900">{{ $userPlan->user->name }}</span>
                                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                                @if($userPlan->status === 'completed') bg-green-100 text-green-800
                                                                @elseif($userPlan->status === 'in_progress') bg-blue-100 text-blue-800
                                                                @else bg-gray-100 text-gray-800
                                                                @endif">
                                                                {{ ucfirst($userPlan->status) }}
                                                            </span>
                                                        </div>
                                                        @if($userPlan->progress_percentage > 0)
                                                            <div class="flex items-center gap-2 mt-1">
                                                                <div class="w-32 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                                    <div class="h-full bg-gradient-to-r from-green-500 to-green-600 rounded-full transition-all duration-300" 
                                                                         style="width: {{ $userPlan->progress_percentage }}%"></div>
                                                                </div>
                                                                <span class="text-xs font-semibold text-gray-600">{{ number_format($userPlan->progress_percentage, 0) }}%</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <span class="text-xs text-gray-500">{{ $userPlan->assigned_at->format('d/m/Y') }}</span>
                                                    <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" 
                                                         :class="expanded ? 'rotate-180' : ''"
                                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            
                                            {{-- Certificaciones del usuario --}}
                                            <div x-show="expanded" 
                                                 x-transition:enter="transition ease-out duration-300"
                                                 x-transition:enter-start="opacity-0"
                                                 x-transition:enter-end="opacity-100"
                                                 x-transition:leave="transition ease-in duration-200"
                                                 x-transition:leave-start="opacity-100"
                                                 x-transition:leave-end="opacity-0"
                                                 style="display: none;"
                                                 class="p-4 bg-white overflow-hidden">
                                                <p class="text-xs font-semibold text-gray-700 uppercase tracking-wide mb-3">
                                                    Certificaciones del plan ({{ $selectedPlan->certifications->count() }})
                                                </p>
                                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                                    @foreach($selectedPlan->certifications->sortBy('order') as $planCert)
                                                        @php
                                                            $cert = $planCert->certification;
                                                            $hasCert = in_array($cert->id, $userActiveCertIds);
                                                        @endphp
                                                        <div class="relative group">
                                                            <div class="bg-white rounded-lg border-2 p-3 transition-all duration-300
                                                                @if($hasCert) border-green-300 shadow-md hover:shadow-lg
                                                                @else border-gray-200 hover:border-gray-300
                                                                @endif">
                                                                {{-- Logo de la certificación --}}
                                                                <div class="aspect-square flex items-center justify-center mb-2 relative">
                                                                    @if($cert->image_url)
                                                                        <img src="{{ $cert->image_url }}" 
                                                                             alt="{{ $cert->name }}"
                                                                             class="w-full h-full object-contain rounded
                                                                             @if($hasCert) 
                                                                                 opacity-100
                                                                             @else 
                                                                                 opacity-40 grayscale blur-sm
                                                                             @endif
                                                                             transition-all duration-300">
                                                                    @else
                                                                        <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 rounded flex items-center justify-center
                                                                            @if($hasCert) 
                                                                                from-green-200 to-green-300
                                                                            @endif">
                                                                            <span class="text-xs font-bold text-gray-600
                                                                                @if($hasCert) text-green-800 @endif">
                                                                                {{ substr($cert->name, 0, 2) }}
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                    
                                                                    {{-- Badge de completado --}}
                                                                    @if($hasCert)
                                                                        <div class="absolute top-0 right-0 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center shadow-lg transform translate-x-1 -translate-y-1">
                                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                                            </svg>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                
                                                                {{-- Nombre de la certificación --}}
                                                                <p class="text-xs font-semibold text-gray-900 text-center line-clamp-2
                                                                    @if($hasCert) text-green-800 @endif"
                                                                   title="{{ $cert->name }}">
                                                                    {{ $cert->name }}
                                                                </p>
                                                                
                                                                {{-- Prioridad --}}
                                                                <div class="mt-1 flex justify-center">
                                                                    <span class="px-1.5 py-0.5 text-[10px] font-bold rounded
                                                                        @if($planCert->priority <= 2) bg-red-100 text-red-800
                                                                        @elseif($planCert->priority == 3) bg-yellow-100 text-yellow-800
                                                                        @else bg-gray-100 text-gray-800
                                                                        @endif">
                                                                        {{ $planCert->priority_label }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                
                                                {{-- Resumen --}}
                                                @php
                                                    $completedCount = $selectedPlan->certifications->filter(function($planCert) use ($userActiveCertIds) {
                                                        return in_array($planCert->certification_id, $userActiveCertIds);
                                                    })->count();
                                                    $totalCount = $selectedPlan->certifications->count();
                                                @endphp
                                                <div class="mt-4 pt-4 border-t border-gray-200">
                                                    <div class="flex items-center justify-between text-sm">
                                                        <span class="font-semibold text-gray-700">Progreso:</span>
                                                        <span class="font-bold 
                                                            @if($completedCount === $totalCount) text-green-600
                                                            @elseif($completedCount > 0) text-blue-600
                                                            @else text-gray-600
                                                            @endif">
                                                            {{ $completedCount }} / {{ $totalCount }} certificaciones
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 text-center py-4">Ningún usuario tiene este plan asignado</p>
                            @endif
                        </div>
            </div>
            @endif
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl flex items-center justify-between gap-3">
                @if($selectedPlan)
                <button wire:click="syncPlanWithUsers({{ $selectedPlan->id }})" 
                        wire:confirm="¿Sincronizar este plan con todos los usuarios que deberían tenerlo?"
                        class="px-4 py-2 text-sm font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-xl border border-blue-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Sincronizar con usuarios
                </button>
                @endif
                <button wire:click="closePlanDetailModal" 
                        class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border border-gray-200">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
