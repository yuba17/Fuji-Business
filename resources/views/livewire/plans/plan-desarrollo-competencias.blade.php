<div x-data="{ viewMode: 'matrix' }" x-cloak>
    {{-- Header con acciones --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-bold text-gray-900">Matriz de Competencias</h3>
            <p class="text-xs text-gray-500">Gestiona y evalúa las competencias del equipo</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="viewMode = viewMode === 'matrix' ? 'gap' : 'matrix'" 
                    class="px-4 py-2 text-sm font-semibold bg-white hover:bg-gray-50 rounded-xl border border-gray-200 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span x-text="viewMode === 'matrix' ? 'Ver Gap Analysis' : 'Ver Matriz'"></span>
            </button>
            <button wire:click="openCompetencyModal()" 
                    class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-purple-600 to-indigo-500 text-white rounded-xl hover:from-purple-700 hover:to-indigo-600 transition-all shadow-lg hover:shadow-xl flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva Competencia
            </button>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="bg-white rounded-xl shadow-md p-4 mb-6 border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Buscar</label>
                <input type="text" wire:model.live.debounce.300ms="search" 
                       placeholder="Nombre o descripción..."
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
                <label class="block text-xs font-medium text-gray-700 mb-1">Rol Interno</label>
                <select wire:model.live="internalRoleId" 
                        class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    <option value="">Todos</option>
                    @foreach($internalRoles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model.live="showCriticalOnly" 
                           class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <span class="text-xs font-medium text-gray-700">Solo críticas</span>
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

    {{-- Vista: Matriz de Competencias --}}
    <div x-show="viewMode === 'matrix'" x-transition>
        @if($competencies->count() > 0 && $teamUsers->count() > 0)
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide sticky left-0 bg-gradient-to-r from-gray-50 to-gray-100 z-10">
                                    Competencia
                                </th>
                                @foreach($teamUsers as $user)
                                    <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wide min-w-[100px]">
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="w-8 h-8 rounded-full bg-gradient-to-br from-red-500 to-orange-500 text-white flex items-center justify-center text-[10px] font-bold">
                                                {{ $user->initials() }}
                                            </span>
                                            <span class="text-[10px]">{{ Str::limit($user->name, 15) }}</span>
                                        </div>
                                    </th>
                                @endforeach
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wide">
                                    Promedio
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $currentCategory = null;
                            @endphp
                            @foreach($competencies as $competency)
                                @if($currentCategory !== $competency->category)
                                    @php $currentCategory = $competency->category; @endphp
                                    <tr class="bg-purple-50">
                                        <td colspan="{{ $teamUsers->count() + 2 }}" class="px-4 py-2">
                                            <p class="text-xs font-bold text-purple-900 uppercase tracking-wide">
                                                {{ $competency->category ?? 'Sin categoría' }}
                                            </p>
                                        </td>
                                    </tr>
                                @endif
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 sticky left-0 bg-white z-10">
                                        <div class="flex items-center gap-2">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $competency->name }}</p>
                                                @if($competency->description)
                                                    <p class="text-[10px] text-gray-500 mt-0.5">{{ Str::limit($competency->description, 50) }}</p>
                                                @endif
                                            </div>
                                            @if($competency->is_critical)
                                                <span class="px-2 py-0.5 bg-red-100 text-red-800 text-[10px] font-bold rounded-full">🔴 Crítica</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <button wire:click="openCompetencyModal({{ $competency->id }})" 
                                                    class="text-[10px] text-purple-600 hover:text-purple-800 font-medium">
                                                Editar
                                            </button>
                                            <span class="text-gray-300">|</span>
                                            <button wire:click="deleteCompetency({{ $competency->id }})" 
                                                    onclick="return confirm('¿Estás seguro de eliminar esta competencia?')"
                                                    class="text-[10px] text-red-600 hover:text-red-800 font-medium">
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                    @foreach($teamUsers as $user)
                                        @php
                                            $userComp = $gapAnalysis[$competency->id]['users'][$user->id] ?? null;
                                            $current = $userComp['current'] ?? 0;
                                            $target = $userComp['target'] ?? 0;
                                        @endphp
                                        <td class="px-3 py-3 text-center">
                                            <button wire:click="openEvaluationModal({{ $user->id }}, {{ $competency->id }})" 
                                                    class="w-full group">
                                                <div class="flex flex-col items-center gap-1">
                                                    @if($current > 0)
                                                        <div class="relative">
                                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center text-sm font-bold transition-all group-hover:scale-110
                                                                @if($current >= 4) bg-green-100 text-green-800
                                                                @elseif($current >= 3) bg-blue-100 text-blue-800
                                                                @elseif($current >= 2) bg-yellow-100 text-yellow-800
                                                                @else bg-red-100 text-red-800
                                                                @endif">
                                                                {{ $current }}
                                                            </div>
                                                            @if($target > 0 && $target > $current)
                                                                <div class="absolute -top-1 -right-1 w-4 h-4 bg-orange-500 rounded-full flex items-center justify-center text-[8px] text-white font-bold" 
                                                                     title="Objetivo: {{ $target }}">
                                                                    {{ $target }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <div class="w-10 h-10 rounded-lg bg-gray-100 text-gray-400 flex items-center justify-center text-sm font-bold transition-all group-hover:bg-gray-200">
                                                            -
                                                        </div>
                                                    @endif
                                                    @if($target > 0 && $target > $current)
                                                        <span class="text-[9px] text-orange-600 font-medium">Gap: {{ $target - $current }}</span>
                                                    @endif
                                                </div>
                                            </button>
                                        </td>
                                    @endforeach
                                    <td class="px-4 py-3 text-center">
                                        @php
                                            $avg = $gapAnalysis[$competency->id]['avg_current'] ?? 0;
                                        @endphp
                                        <span class="text-sm font-semibold text-gray-900">
                                            {{ $avg > 0 ? number_format($avg, 1) : '-' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="text-center py-10 bg-white rounded-xl shadow-md border border-gray-200">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                <p class="text-sm text-gray-500 mb-4">
                    @if($competencies->count() === 0)
                        No hay competencias definidas. Crea la primera competencia para comenzar.
                    @else
                        No hay usuarios en el equipo del área para mostrar la matriz.
                    @endif
                </p>
                @if($competencies->count() === 0)
                    <button wire:click="openCompetencyModal()" 
                            class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-purple-600 to-indigo-500 text-white rounded-xl hover:from-purple-700 hover:to-indigo-600 transition-all">
                        Crear Primera Competencia
                    </button>
                @endif
            </div>
        @endif
    </div>

    {{-- Vista: Gap Analysis --}}
    <div x-show="viewMode === 'gap'" x-transition style="display: none;">
        @if($competencies->count() > 0)
            <div class="space-y-4">
                @foreach($competencies as $competency)
                    @php
                        $gapData = $gapAnalysis[$competency->id] ?? null;
                        $totalGap = $gapData['total_gap'] ?? 0;
                        $avgCurrent = $gapData['avg_current'] ?? 0;
                        $avgTarget = $gapData['avg_target'] ?? 0;
                    @endphp
                    <div class="bg-white rounded-xl shadow-md p-4 border border-gray-200">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="text-sm font-bold text-gray-900">{{ $competency->name }}</h4>
                                    @if($competency->is_critical)
                                        <span class="px-2 py-0.5 bg-red-100 text-red-800 text-[10px] font-bold rounded-full">🔴 Crítica</span>
                                    @endif
                                    @if($competency->category)
                                        <span class="px-2 py-0.5 bg-purple-100 text-purple-800 text-[10px] font-medium rounded-full">{{ $competency->category }}</span>
                                    @endif
                                </div>
                                @if($competency->description)
                                    <p class="text-xs text-gray-600">{{ $competency->description }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <p class="text-[10px] text-gray-500 uppercase tracking-wide">Promedio Actual</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $avgCurrent > 0 ? number_format($avgCurrent, 1) : '-' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-gray-500 uppercase tracking-wide">Promedio Objetivo</p>
                                    <p class="text-lg font-bold text-orange-600">{{ $avgTarget > 0 ? number_format($avgTarget, 1) : '-' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-gray-500 uppercase tracking-wide">Gap Total</p>
                                    <p class="text-lg font-bold {{ $totalGap > 0 ? 'text-red-600' : 'text-green-600' }}">{{ $totalGap }}</p>
                                </div>
                            </div>
                        </div>
                        
                        @if(isset($gapData['users']) && count($gapData['users']) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mt-4">
                                @foreach($gapData['users'] as $userData)
                                    @php
                                        $user = $userData['user'];
                                        $current = $userData['current'];
                                        $target = $userData['target'];
                                        $gap = $userData['gap'];
                                    @endphp
                                    @if($current > 0 || $target > 0)
                                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-3 border border-gray-200">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="w-7 h-7 rounded-full bg-gradient-to-br from-red-500 to-orange-500 text-white flex items-center justify-center text-[10px] font-bold">
                                                        {{ $user->initials() }}
                                                    </span>
                                                    <span class="text-xs font-semibold text-gray-900">{{ $user->name }}</span>
                                                </div>
                                                <button wire:click="openEvaluationModal({{ $user->id }}, {{ $competency->id }})" 
                                                        class="text-[10px] text-purple-600 hover:text-purple-800 font-medium">
                                                    Evaluar
                                                </button>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-[10px] text-gray-500">Actual</p>
                                                    <p class="text-sm font-bold text-gray-900">{{ $current > 0 ? $current : '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-[10px] text-gray-500">Objetivo</p>
                                                    <p class="text-sm font-bold text-orange-600">{{ $target > 0 ? $target : '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-[10px] text-gray-500">Gap</p>
                                                    <p class="text-sm font-bold {{ $gap > 0 ? 'text-red-600' : 'text-green-600' }}">
                                                        {{ $gap > 0 ? '+' . $gap : '0' }}
                                                    </p>
                                                </div>
                                            </div>
                                            @if($gap > 0)
                                                <div class="mt-2 pt-2 border-t border-gray-200">
                                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                                        <div class="bg-red-500 h-2 rounded-full transition-all" style="width: {{ min(100, ($gap / 5) * 100) }}%"></div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-500 text-center py-4">No hay evaluaciones para esta competencia</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-10 bg-white rounded-xl shadow-md border border-gray-200">
                <p class="text-sm text-gray-500">No hay competencias definidas para mostrar el gap analysis.</p>
            </div>
        @endif
    </div>

    {{-- Modal: Crear/Editar Competencia --}}
    @if($showCompetencyModal)
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
                        {{ $competencyId ? 'Editar Competencia' : 'Nueva Competencia' }}
                    </h3>
                    <button wire:click="closeCompetencyModal" class="text-white/80 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                            <input type="text" wire:model="competencyName" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                            @error('competencyName') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                            <textarea wire:model="competencyDescription" rows="3"
                                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all resize-none"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                                <input type="text" wire:model="competencyCategory" 
                                       placeholder="Ej: Técnica, Soft Skills..."
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rol Interno</label>
                                <select wire:model="competencyInternalRoleId" 
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                                    <option value="">Ninguno</option>
                                    @foreach($internalRoles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="competencyIsCritical" 
                                       class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                <span class="text-sm font-medium text-gray-700">Competencia crítica</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl flex items-center justify-end gap-3">
                    <button wire:click="closeCompetencyModal" 
                            class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border border-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="saveCompetency" 
                            class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-purple-600 to-indigo-500 text-white rounded-xl hover:from-purple-700 hover:to-indigo-600">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal: Evaluar Competencia --}}
    @if($showEvaluationModal)
        <div class="fixed inset-0 z-50 bg-gray-900/40 flex items-center justify-center p-4" 
             x-show="true"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">
            <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full"
                 x-show="true"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="bg-gradient-to-r from-purple-600 to-indigo-500 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                    <h3 class="text-xl font-bold text-white">Evaluar Competencia</h3>
                    <button wire:click="closeEvaluationModal" class="text-white/80 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                            <select wire:model="evaluationUserId" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                                <option value="">Selecciona un usuario</option>
                                @foreach($teamUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('evaluationUserId') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Competencia</label>
                            <select wire:model="evaluationCompetencyId" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                                <option value="">Selecciona una competencia</option>
                                @foreach($competencies as $comp)
                                    <option value="{{ $comp->id }}">{{ $comp->name }}</option>
                                @endforeach
                            </select>
                            @error('evaluationCompetencyId') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nivel Actual (1-5) *</label>
                                <input type="number" wire:model="evaluationCurrentLevel" min="1" max="5" 
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                                @error('evaluationCurrentLevel') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nivel Objetivo (1-5)</label>
                                <input type="number" wire:model="evaluationTargetLevel" min="1" max="5" 
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                            <textarea wire:model="evaluationNotes" rows="3"
                                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all resize-none"></textarea>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl flex items-center justify-end gap-3">
                    <button wire:click="closeEvaluationModal" 
                            class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border border-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="saveEvaluation" 
                            class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-purple-600 to-indigo-500 text-white rounded-xl hover:from-purple-700 hover:to-indigo-600">
                        Guardar Evaluación
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
