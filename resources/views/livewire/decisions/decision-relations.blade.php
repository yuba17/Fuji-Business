<div>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-gray-900">Planes Relacionados</h2>
        @can('update', $decision)
            <button @click="$wire.openAddModal()" 
                    class="px-4 py-2 text-sm font-semibold bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-lg hover:from-blue-700 hover:to-cyan-700 transition-all">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Agregar Plan
            </button>
        @endcan
    </div>

    @if($decision->plans->count() > 0)
        <div class="space-y-3">
            @foreach($decision->plans as $plan)
                <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-500 flex items-center justify-between">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">{{ $plan->name }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $plan->planType->name ?? 'Sin tipo' }}</p>
                        <a href="{{ route('plans.show', $plan) }}" class="text-xs text-blue-600 hover:text-blue-700 mt-1 inline-block">
                            Ver plan →
                        </a>
                    </div>
                    @can('update', $decision)
                        <button wire:click="removePlan({{ $plan->id }})" 
                                wire:confirm="¿Estás seguro de eliminar esta relación?"
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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-sm text-gray-600 mb-4">No hay planes relacionados</p>
            @can('update', $decision)
                <button @click="$wire.openAddModal()" 
                        class="px-4 py-2 text-sm font-semibold bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-lg hover:from-blue-700 hover:to-cyan-700 transition-all">
                    Agregar Plan
                </button>
            @endcan
        </div>
    @endif

    <!-- Modal para agregar planes -->
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
                <h3 class="text-xl font-bold text-white">Agregar Plan Relacionado</h3>
                <button @click="$wire.closeAddModal()" class="text-white/80 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="p-6">
                @if($availablePlans->count() > 0)
                    <div class="space-y-2">
                        @foreach($availablePlans as $plan)
                            <button wire:click="addPlan({{ $plan->id }})" 
                                    class="w-full text-left bg-gray-50 hover:bg-blue-50 rounded-lg p-4 border border-gray-200 hover:border-blue-300 transition-all">
                                <h4 class="font-semibold text-gray-900">{{ $plan->name }}</h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $plan->planType->name ?? 'Sin tipo' }}</p>
                            </button>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-sm text-gray-600">No hay planes disponibles para agregar</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
