<div>
    <!-- Filtros -->
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-md p-6">
            <form wire:submit.prevent class="flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input 
                        type="search" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Buscar decisiones..." 
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                </div>
                <div class="w-full sm:w-auto">
                    <select wire:model.live="status" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <option value="">Todos los estados</option>
                        <option value="proposed">Propuesta</option>
                        <option value="discussion">En Discusión</option>
                        <option value="pending_approval">Pendiente de Aprobación</option>
                        <option value="approved">Aprobada</option>
                        <option value="rejected">Rechazada</option>
                        <option value="implemented">Implementada</option>
                    </select>
                </div>
                <div class="w-full sm:w-auto">
                    <select wire:model.live="planId" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <option value="">Todos los planes</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                        @endforeach
                    </select>
                </div>
                @if($search || $status || $planId)
                    <button wire:click="clearFilters" type="button" class="px-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all">
                        Limpiar
                    </button>
                @endif
            </form>
        </div>
    </div>

    <!-- Lista de Decisiones -->
    @if($decisions->count() > 0)
        <div class="space-y-4">
            @foreach($decisions as $decision)
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500 hover:shadow-lg transition-all cursor-pointer" onclick="window.location.href='{{ route('decisions.show', $decision) }}'">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-bold text-gray-900">{{ $decision->title }}</h3>
                                <x-ui.badge 
                                    variant="{{ $decision->status === 'approved' ? 'success' : ($decision->status === 'rejected' ? 'error' : ($decision->status === 'implemented' ? 'info' : 'warning')) }}">
                                    {{ $decision->status_label }}
                                </x-ui.badge>
                            </div>
                            
                            @if($decision->description)
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $decision->description }}</p>
                            @endif
                            
                            <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500">
                                @if($decision->proponent)
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $decision->proponent->name }}
                                    </div>
                                @endif
                                @if($decision->decision_date)
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $decision->decision_date->format('d/m/Y') }}
                                    </div>
                                @endif
                                @if($decision->plans->count() > 0)
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        {{ $decision->plans->count() }} plan(es)
                                    </div>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('decisions.show', $decision) }}" 
                           class="ml-4 text-sm font-medium text-purple-600 hover:text-purple-700">
                            Ver →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Paginación -->
        <div class="mt-6">
            {{ $decisions->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow-md p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-sm text-gray-600 mb-4">No se encontraron decisiones</p>
            @if($search || $status || $planId)
                <button wire:click="clearFilters" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all">
                    Limpiar filtros
                </button>
            @endif
        </div>
    @endif
</div>
