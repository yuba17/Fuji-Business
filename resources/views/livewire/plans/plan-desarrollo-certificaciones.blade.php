<div x-data="{ viewMode: 'inventory', selectedUserId: null }" x-cloak>
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

    {{-- Filtros --}}
    <div class="bg-white rounded-xl shadow-md p-4 mb-6 border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
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
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Proveedor</label>
                <select wire:model.live="provider" 
                        class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    <option value="">Todos</option>
                    @foreach($providers as $prov)
                        <option value="{{ $prov }}">{{ $prov }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Nivel</label>
                <select wire:model.live="level" 
                        class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    <option value="">Todos</option>
                    @foreach($levels as $lev)
                        <option value="{{ $lev }}">{{ $lev }}</option>
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
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Certificación</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Proveedor</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase">Nivel</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase">Validez</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase">Coste</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase">Puntos</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase">Usuarios</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($certifications as $cert)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            @if($cert->is_critical)
                                                <span class="px-2 py-0.5 bg-red-100 text-red-800 text-[10px] font-bold rounded-full">🔴</span>
                                            @endif
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $cert->name }}</p>
                                                @if($cert->code)
                                                    <p class="text-xs text-gray-500">{{ $cert->code }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-gray-700">{{ $cert->provider }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($cert->level)
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">{{ $cert->level }}</span>
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
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-sm font-semibold text-gray-900">{{ $cert->user_certifications_count }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button wire:click="openCertificationModal({{ $cert->id }})" 
                                                class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                            Editar
                                        </button>
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

    {{-- Vista: Matriz por Rol --}}
    <div x-show="viewMode === 'matrix'" x-transition style="display: none;">
        @if($matrixByRole->count() > 0)
            <div class="space-y-6">
                @foreach($matrixByRole as $roleData)
                    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                            <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M12 11a4 4 0 110-8 4 4 0 010 8z"/>
                                </svg>
                                {{ $roleData['role']->name }}
                                <span class="text-xs font-normal text-gray-500">({{ $roleData['users']->count() }} usuarios)</span>
                            </h3>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($roleData['certifications'] as $certData)
                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 border border-gray-200">
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="flex-1">
                                                <h4 class="text-sm font-bold text-gray-900">{{ $certData['certification']->name }}</h4>
                                                @if($certData['certification']->is_critical)
                                                    <span class="px-2 py-0.5 bg-red-100 text-red-800 text-[10px] font-bold rounded-full mt-1 inline-block">🔴 Crítica</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xs text-gray-600">Cobertura</span>
                                                <span class="text-xs font-bold text-gray-900">{{ number_format($certData['coverage'], 0) }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-2 rounded-full transition-all" 
                                                     style="width: {{ min($certData['coverage'], 100) }}%"></div>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $certData['users_count'] }} de {{ $roleData['users']->count() }} usuarios
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-xl shadow-md border border-gray-200">
                <p class="text-sm text-gray-500">No hay datos de certificaciones por rol.</p>
            </div>
        @endif
    </div>

    {{-- Vista: Roadmap Personalizado --}}
    <div x-show="viewMode === 'roadmap'" x-transition style="display: none;">
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200 mb-6">
            <label class="block text-xs font-medium text-gray-700 mb-2">Seleccionar Usuario</label>
            <select wire:model.live="selectedUserId" 
                    class="w-full md:w-1/3 px-4 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                <option value="">-- Selecciona un usuario --</option>
                @foreach($teamUsers as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        @if($selectedUserId && $personalRoadmap && $personalRoadmap->count() > 0)
            <div class="space-y-4">
                @foreach($personalRoadmap->groupBy(function($item) {
                    return $item->planned_date ? $item->planned_date->format('Y-m') : 'sin-fecha';
                }) as $month => $items)
                    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-purple-100">
                            <h3 class="text-sm font-bold text-gray-900">
                                @if($month !== 'sin-fecha')
                                    {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->locale('es')->translatedFormat('F Y') }}
                                @else
                                    Sin fecha planificada
                                @endif
                            </h3>
                        </div>
                        <div class="p-4 space-y-3">
                            @foreach($items->sortBy('priority') as $uc)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h4 class="text-sm font-bold text-gray-900">{{ $uc->certification->name }}</h4>
                                            @if($uc->priority > 0)
                                                <span class="px-2 py-0.5 bg-purple-100 text-purple-800 text-[10px] font-bold rounded-full">
                                                    Prioridad {{ $uc->priority }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500">{{ $uc->certification->provider }}</p>
                                        @if($uc->notes)
                                            <p class="text-xs text-gray-600 mt-1">{{ $uc->notes }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right ml-4">
                                        @if($uc->planned_date)
                                            <p class="text-xs text-gray-500">Fecha Planificada</p>
                                            <p class="text-sm font-bold text-purple-600">{{ $uc->planned_date->format('d/m/Y') }}</p>
                                        @endif
                                        <button wire:click="openUserCertificationModal({{ $uc->user_id }}, {{ $uc->id }})" 
                                                class="mt-2 text-blue-600 hover:text-blue-800 text-xs font-medium">
                                            Editar
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @elseif($selectedUserId)
            <div class="text-center py-12 bg-white rounded-xl shadow-md border border-gray-200">
                <p class="text-sm text-gray-500">Este usuario no tiene certificaciones planificadas.</p>
                <button wire:click="openUserCertificationModal({{ $selectedUserId }})" 
                        class="mt-4 px-4 py-2 text-sm font-semibold bg-purple-600 text-white rounded-lg hover:bg-purple-700">
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
                            <input type="text" wire:model="certificationProvider" 
                                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            @error('certificationProvider') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                            <input type="text" wire:model="certificationCategory" 
                                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nivel</label>
                            <select wire:model="certificationLevel" 
                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">-- Selecciona --</option>
                                <option value="Entry">Entry</option>
                                <option value="Intermediate">Intermediate</option>
                                <option value="Advanced">Advanced</option>
                                <option value="Expert">Expert</option>
                            </select>
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
