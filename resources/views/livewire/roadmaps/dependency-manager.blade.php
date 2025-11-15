<div>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-gray-900">Gestión de Dependencias</h2>
        @can('update', $plan)
            <button @click="$wire.openAddModal()" 
                    class="px-4 py-2 text-sm font-semibold bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-lg hover:from-blue-700 hover:to-cyan-700 transition-all">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Agregar Dependencia
            </button>
        @endcan
    </div>

    @if($milestoneId)
        @php
            $predecessors = $dependencies['predecessors'] ?? collect();
            $successors = $dependencies['successors'] ?? collect();
        @endphp

        @if($predecessors->count() > 0 || $successors->count() > 0)
            <div class="space-y-4">
                @if($predecessors->count() > 0)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Predecesores (debe completarse antes)</h3>
                        <div class="space-y-2">
                            @foreach($predecessors as $dependency)
                                <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-3 flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-sm text-gray-900">{{ $dependency->predecessor->name }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $dependency->type_label }} @if($dependency->lag_days > 0) • {{ $dependency->lag_days }} días de retraso @endif</p>
                                    </div>
                                    @can('update', $plan)
                                        <button wire:click="removeDependency({{ $dependency->id }})" 
                                                wire:confirm="¿Estás seguro de eliminar esta dependencia?"
                                                class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endcan
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($successors->count() > 0)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Sucesores (dependen de este hito)</h3>
                        <div class="space-y-2">
                            @foreach($successors as $dependency)
                                <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-3 flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-sm text-gray-900">{{ $dependency->successor->name }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $dependency->type_label }} @if($dependency->lag_days > 0) • {{ $dependency->lag_days }} días de retraso @endif</p>
                                    </div>
                                    @can('update', $plan)
                                        <button wire:click="removeDependency({{ $dependency->id }})" 
                                                wire:confirm="¿Estás seguro de eliminar esta dependencia?"
                                                class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endcan
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-600 mb-4">No hay dependencias definidas</p>
                @can('update', $plan)
                    <button @click="$wire.openAddModal()" 
                            class="px-4 py-2 text-sm font-semibold bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-lg hover:from-blue-700 hover:to-cyan-700 transition-all">
                        Agregar Dependencia
                    </button>
                @endcan
            </div>
        @endif
    @else
        @if($dependencies->count() > 0)
            <div class="space-y-3">
                @foreach($dependencies as $dependency)
                    <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-500 flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-sm text-gray-900">{{ $dependency->predecessor->name }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                <span class="font-semibold text-sm text-gray-900">{{ $dependency->successor->name }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $dependency->type_label }} @if($dependency->lag_days > 0) • {{ $dependency->lag_days }} días de retraso @endif</p>
                        </div>
                        @can('update', $plan)
                            <button wire:click="removeDependency({{ $dependency->id }})" 
                                    wire:confirm="¿Estás seguro de eliminar esta dependencia?"
                                    class="ml-4 p-2 text-gray-400 hover:text-red-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        @endcan
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-600 mb-4">No hay dependencias definidas</p>
                @can('update', $plan)
                    <button @click="$wire.openAddModal()" 
                            class="px-4 py-2 text-sm font-semibold bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-lg hover:from-blue-700 hover:to-cyan-700 transition-all">
                        Agregar Dependencia
                    </button>
                @endcan
            </div>
        @endif
    @endif

    <!-- Modal para agregar dependencia -->
    <div x-show="$wire.showAddModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-gray-900/40 flex items-center justify-center p-4"
         style="display: none;"
         @click.self="$wire.closeAddModal()">
        <div x-show="$wire.showAddModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                <h3 class="text-xl font-bold text-white">Agregar Dependencia</h3>
                <button @click="$wire.closeAddModal()" class="text-white/80 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form wire:submit.prevent="addDependency" class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hito Predecesor *</label>
                    <select wire:model="predecessorId" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            required>
                        <option value="">Seleccionar hito...</option>
                        @foreach($availableMilestones as $milestone)
                            <option value="{{ $milestone->id }}">{{ $milestone->name }}</option>
                        @endforeach
                    </select>
                    @error('predecessorId') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hito Sucesor *</label>
                    <select wire:model="successorId" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            required>
                        <option value="">Seleccionar hito...</option>
                        @foreach($availableMilestones as $milestone)
                            <option value="{{ $milestone->id }}">{{ $milestone->name }}</option>
                        @endforeach
                    </select>
                    @error('successorId') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Dependencia *</label>
                        <select wire:model="type" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                required>
                            <option value="fs">Finish-to-Start (FS)</option>
                            <option value="ss">Start-to-Start (SS)</option>
                            <option value="ff">Finish-to-Finish (FF)</option>
                            <option value="sf">Start-to-Finish (SF)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Días de Retraso</label>
                        <input type="number" wire:model="lagDays" min="0"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                </div>

                @if(session()->has('error'))
                    <div class="p-3 bg-red-50 border-l-4 border-red-500 rounded-lg">
                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Footer -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" @click="$wire.closeAddModal()" 
                            class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border border-gray-200">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-xl hover:from-blue-700 hover:to-cyan-700">
                        Crear Dependencia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
