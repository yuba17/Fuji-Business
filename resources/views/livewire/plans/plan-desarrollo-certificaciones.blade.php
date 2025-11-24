<div x-data="{ viewMode: 'inventory', selectedUserId: null, filtersOpen: false }" x-cloak>
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            animation: fade-in 0.5s ease-out;
        }
    </style>
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
            <p class="text-xs text-gray-500">Gestiona certificaciones, roadmap personalizado y gamificación</p>
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
                <button @click="viewMode = 'leaderboard'" 
                        :class="viewMode === 'leaderboard' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                        class="px-3 py-2 text-xs font-semibold rounded-lg border border-gray-200 transition-all">
                    Leaderboard
                </button>
                <button @click="viewMode = 'badges'" 
                        :class="viewMode === 'badges' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                        class="px-3 py-2 text-xs font-semibold rounded-lg border border-gray-200 transition-all">
                    Badges
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
        @if($showMatrixDetailModal && $selectedMatrixCertification)
            @php
                $certDetail = $matrixData->firstWhere('certification.id', $selectedMatrixCertification);
            @endphp
            @if($certDetail)
                <div class="fixed inset-0 z-50 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4" 
                     x-show="true"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100">
                    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto"
                         x-show="true"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         @click.away="window.livewire.find('{{ $this->getId() }}').call('closeMatrixDetailModal')">
                        {{-- Header --}}
                        <div class="bg-gradient-to-r from-red-600 to-orange-600 px-6 py-4 flex items-center justify-between rounded-t-2xl sticky top-0 z-10">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-white mb-1">{{ $certDetail['certification']->name }}</h3>
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
                </div>
            @endif
        @endif
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
                        <button wire:click="openUserCertificationModal({{ $selectedUserId }})" 
                                class="px-4 py-2 text-sm font-semibold bg-gradient-to-r from-purple-600 to-indigo-500 text-white rounded-lg hover:from-purple-700 hover:to-indigo-600 transition-all flex items-center gap-2 shadow-lg hover:shadow-xl">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Añadir Certificación
                        </button>
                    </div>
                @endif
            </div>
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
                                    x-intersect="$el.classList.add('animate-fade-in')">
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
                                                                    @if($item['is_critical'])
                                                                        <span class="px-3 py-1.5 bg-gradient-to-r from-blue-600 to-cyan-500 text-white text-xs font-bold rounded-full shadow-sm">Crítica</span>
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
             style="display: none;">
            <div x-show="selectedItem"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
                 @click.stop>
                <template x-if="selectedItem">
                    <div>
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
                                        <template x-if="selectedItem?.is_critical">
                                            <span class="px-3 py-1 bg-blue-600/80 backdrop-blur-sm text-white text-sm font-bold rounded-full">🔴 Crítica</span>
                                        </template>
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
                            <template x-if="selectedItem?.image_url">
                                <div class="flex justify-center">
                                    <img :src="selectedItem.image_url" 
                                         :alt="selectedItem.name" 
                                         class="w-32 h-32 object-contain rounded-xl border-2 border-gray-200 bg-white p-2 shadow-md">
                                </div>
                            </template>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                                    <p class="text-xs font-medium text-blue-600 uppercase tracking-wide mb-1">Proveedor</p>
                                    <p class="text-base font-bold text-gray-900" x-text="selectedItem?.provider_label || selectedItem?.provider"></p>
                                </div>
                                
                                <div class="p-4 bg-cyan-50 rounded-xl border border-cyan-200">
                                    <p class="text-xs font-medium text-cyan-600 uppercase tracking-wide mb-1">Fecha Planificada</p>
                                    <p class="text-base font-bold text-cyan-600" x-text="selectedItem?.date_formatted || 'Sin fecha'"></p>
                                </div>
                                
                                <template x-if="selectedItem?.priority > 0">
                                    <div class="p-4 bg-amber-50 rounded-xl border border-amber-200">
                                        <p class="text-xs font-medium text-amber-600 uppercase tracking-wide mb-1">Prioridad</p>
                                        <p class="text-base font-bold text-amber-600" x-text="'Nivel ' + selectedItem.priority"></p>
                                    </div>
                                </template>
                            </div>
                            
                            <template x-if="selectedItem?.notes">
                                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                                    <p class="text-xs font-medium text-gray-600 uppercase tracking-wide mb-2">Notas</p>
                                    <p class="text-sm text-gray-700 whitespace-pre-wrap" x-text="selectedItem.notes"></p>
                                </div>
                            </template>
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
                </template>
            </div>
        </div>
    </div>

    {{-- Vista: Leaderboard --}}
    <div x-show="viewMode === 'leaderboard'" x-transition style="display: none;">
        @if($leaderboard->count() > 0)
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-100">
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                        Leaderboard de Certificaciones
                    </h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($leaderboard as $index => $entry)
                        <div class="px-4 py-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-red-500 to-orange-500 text-white flex items-center justify-center font-bold text-lg">
                                    @if($index === 0)
                                        🥇
                                    @elseif($index === 1)
                                        🥈
                                    @elseif($index === 2)
                                        🥉
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-sm font-bold text-gray-900">{{ $entry['user']->name }}</h4>
                                        @if($entry['user']->internalRole)
                                            <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-[10px] font-medium rounded-full">
                                                {{ $entry['user']->internalRole->name }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-4 mt-1">
                                        <span class="text-xs text-gray-600">
                                            {{ $entry['active_certifications'] }} certificaciones activas
                                        </span>
                                        <span class="text-xs text-gray-600">
                                            {{ $entry['badges'] }} badges
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-purple-600">{{ $entry['points'] }}</p>
                                    <p class="text-xs text-gray-500">puntos</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-xl shadow-md border border-gray-200">
                <p class="text-sm text-gray-500">No hay datos para el leaderboard.</p>
            </div>
        @endif
    </div>

    {{-- Vista: Badges --}}
    <div x-show="viewMode === 'badges'" x-transition style="display: none;">
        @if($recentBadges->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($recentBadges as $badge)
                    <div class="bg-white rounded-xl shadow-md p-4 border border-gray-200 hover:shadow-lg transition-shadow">
                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-indigo-500 text-white flex items-center justify-center text-2xl flex-shrink-0">
                                {{ $badge->icon ?? '🏆' }}
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-gray-900">{{ $badge->name }}</h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $badge->user->name }}</p>
                                @if($badge->description)
                                    <p class="text-xs text-gray-600 mt-1">{{ $badge->description }}</p>
                                @endif
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="px-2 py-0.5 bg-purple-100 text-purple-800 text-[10px] font-bold rounded-full">
                                        +{{ $badge->points }} pts
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $badge->earned_at->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-xl shadow-md border border-gray-200">
                <p class="text-sm text-gray-500">No hay badges recientes.</p>
            </div>
        @endif
    </div>

    {{-- Modal: Crear/Editar Certificación --}}
    @if($showCertificationModal)
        <div class="fixed inset-0 z-50 bg-gray-900/40 flex items-center justify-center p-4" 
             x-show="true"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
                 x-show="true"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
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
    @endif

    {{-- Modal: Cropper de Imagen de Certificación --}}
    @if($showCertificationCropper && $certificationImage)
        <div x-data="certificationCropperModal()" 
             x-show="true"
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="closeModal()"
             class="fixed inset-0 z-[100] bg-gray-900/80 backdrop-blur-sm flex items-center justify-center p-4"
             style="display: block; position: fixed; z-index: 9999;"
             wire:ignore>
            <div x-show="true"
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
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-4">Ajusta el recuadro para encuadrar el logo perfectamente. Puedes hacer zoom y mover la imagen.</p>
                    
                    <div class="mb-6" style="max-height: 500px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                        <img id="cropper-certification-image" 
                             src="{{ $certificationImage->temporaryUrl() }}" 
                             alt="Imagen a recortar"
                             style="max-width: 100%; max-height: 500px; display: block;">
                    </div>
                </div>

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
    @endif

    {{-- Modal: Asignar Certificación a Usuario --}}
    @if($showUserCertificationModal)
        <div class="fixed inset-0 z-50 bg-gray-900/40 flex items-center justify-center p-4" 
             x-show="true"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
                 x-show="true"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
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
    @endif
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
<script>
function certificationCropperModal() {
    return {
        cropper: null,
        
        init() {
            // Esperar a que la imagen esté cargada
            this.$nextTick(() => {
                setTimeout(() => {
                    this.initCropper();
                }, 300);
            });
        },
        
        initCropper() {
            const imageId = 'cropper-certification-image';
            const image = document.getElementById(imageId);
            
            if (image && !this.cropper) {
                // Destruir cropper anterior si existe
                if (this.cropper) {
                    this.cropper.destroy();
                }
                
                this.cropper = new Cropper(image, {
                    aspectRatio: 1, // Cuadrado para logos
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 0.8,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                    minCropBoxWidth: 100,
                    minCropBoxHeight: 100,
                });
            }
        },
        
        destroyCropper() {
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
        },
        
        cropAndSave() {
            if (!this.cropper) {
                alert('El cropper no está inicializado. Por favor espera un momento.');
                return;
            }
            
            const canvas = this.cropper.getCroppedCanvas({
                width: 512,
                height: 512,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });
            
            const croppedDataUrl = canvas.toDataURL('image/jpeg', 0.9);
            
            @this.cropCertificationImage(croppedDataUrl);
            
            // Esperar un momento y luego guardar
            setTimeout(() => {
                @this.saveCroppedCertificationImage();
                this.closeModal();
            }, 100);
        },
        
        closeModal() {
            this.destroyCropper();
            @this.cancelCertificationCrop();
        }
    }
}
</script>
@endpush
