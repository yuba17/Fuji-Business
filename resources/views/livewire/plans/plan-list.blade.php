<div>
    <!-- Filtros -->
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                    <input 
                        type="search" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Buscar planes..." 
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                </div>
                <div class="w-full sm:w-auto">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Plan</label>
                    <select 
                        wire:model.live="planTypeId"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <option value="">Todos los tipos</option>
                        @foreach($planTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-auto">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select 
                        wire:model.live="status"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <option value="">Todos los estados</option>
                        <option value="draft">Borrador</option>
                        <option value="internal_review">En Revisión Interna</option>
                        <option value="director_review">En Revisión Dirección</option>
                        <option value="approved">Aprobado</option>
                        <option value="in_progress">En Ejecución</option>
                        <option value="under_review">En Revisión Periódica</option>
                        <option value="closed">Cerrado</option>
                    </select>
                </div>
                @if(auth()->user()->isDirector())
                <div class="w-full sm:w-auto">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Área</label>
                    <select 
                        wire:model.live="areaId"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <option value="">Todas las áreas</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Lista de Planes -->
    @if($plans->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($plans as $plan)
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200 hover:shadow-lg hover:border-red-300 transition-all cursor-pointer group" 
                     onclick="window.location.href='{{ route('plans.show', $plan) }}'">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-red-600 transition-colors">{{ $plan->name }}</h3>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-lg">{{ $plan->planType->name ?? 'Sin tipo' }}</span>
                                <x-ui.badge 
                                    variant="{{ $plan->status === 'approved' ? 'success' : ($plan->status === 'in_progress' ? 'info' : ($plan->status === 'closed' ? 'gray' : 'warning')) }}">
                                    {{ $plan->status_label }}
                                </x-ui.badge>
                            </div>
                        </div>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $plan->description }}</p>
                    
                    <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 mb-4">
                        @if($plan->start_date && $plan->end_date)
                            <div class="flex items-center gap-1.5 bg-blue-50 px-2 py-1 rounded-lg">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-blue-700 font-medium">{{ $plan->start_date->format('d/m/Y') }} - {{ $plan->end_date->format('d/m/Y') }}</span>
                            </div>
                        @endif
                        @if($plan->area)
                            <div class="flex items-center gap-1.5 bg-green-50 px-2 py-1 rounded-lg">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span class="text-green-700 font-medium">{{ $plan->area->name }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div class="flex items-center gap-3">
                            @if($plan->tasks_count > 0)
                                <div class="flex items-center gap-1.5 bg-orange-50 px-2 py-1 rounded-lg">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <span class="text-orange-700 text-xs font-semibold">{{ $plan->tasks_count }}</span>
                                </div>
                            @endif
                            @if($plan->kpis_count > 0)
                                <div class="flex items-center gap-1.5 bg-blue-50 px-2 py-1 rounded-lg">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <span class="text-blue-700 text-xs font-semibold">{{ $plan->kpis_count }}</span>
                                </div>
                            @endif
                        </div>
                        <a href="{{ route('plans.show', $plan) }}" 
                           class="text-xs font-semibold bg-gradient-to-r from-red-600 to-orange-600 text-white px-4 py-2 rounded-lg hover:from-red-700 hover:to-orange-700 transition-all flex items-center gap-1 shadow-sm">
                            Ver más
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Paginación -->
        <div class="mt-6">
            {{ $plans->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <p class="text-gray-500 text-sm font-medium mb-4">No se encontraron planes con los filtros seleccionados</p>
                @if($search || $planTypeId || $status || $areaId)
                    <button wire:click="$set('search', ''); $set('planTypeId', ''); $set('status', ''); $set('areaId', '')" 
                            class="text-sm text-red-600 hover:text-red-700 font-semibold">
                        Limpiar filtros
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>

