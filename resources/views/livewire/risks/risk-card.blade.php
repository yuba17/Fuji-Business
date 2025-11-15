<div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-{{ $risk->category_color }}-500 hover:shadow-lg transition-all">
    <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-2">
                <h3 class="text-base font-bold text-gray-900 truncate">
                    <a href="{{ route('risks.show', $risk) }}" class="hover:text-red-600 transition-colors">
                        {{ $risk->name }}
                    </a>
                </h3>
                <x-ui.badge 
                    variant="{{ $risk->category === 'critico' ? 'error' : ($risk->category === 'alto' ? 'warning' : ($risk->category === 'medio' ? 'info' : 'success')) }}"
                >
                    {{ ucfirst($risk->category) }}
                </x-ui.badge>
            </div>
            
            @if($risk->description)
                <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ $risk->description }}</p>
            @endif
            
            <div class="flex items-center gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Probabilidad:</span>
                    <span class="font-bold text-gray-900 ml-1">{{ $risk->probability }}/5</span>
                </div>
                <div>
                    <span class="text-gray-500">Impacto:</span>
                    <span class="font-bold text-gray-900 ml-1">{{ $risk->impact }}/5</span>
                </div>
                <div>
                    <span class="text-gray-500">Nivel:</span>
                    <span class="font-bold text-{{ $risk->category_color }}-600 ml-1">{{ $risk->risk_level }}/25</span>
                </div>
            </div>
            
            @if($risk->owner)
                <div class="mt-2 text-xs text-gray-500">
                    Propietario: <span class="font-medium">{{ $risk->owner->name }}</span>
                </div>
            @endif
            
            @if($risk->plan)
                <div class="mt-1 text-xs text-gray-500">
                    Plan: <a href="{{ route('plans.show', $risk->plan) }}" class="font-medium text-red-600 hover:text-red-700">
                        {{ $risk->plan->name }}
                    </a>
                </div>
            @endif
        </div>
        
        <div class="flex flex-col gap-2 ml-4">
            <button 
                @click="$wire.toggleDetails()" 
                class="p-2 text-gray-400 hover:text-gray-600 transition-colors"
                title="Ver detalles"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </button>
            
            @can('update', $risk)
                <a 
                    href="{{ route('risks.edit', $risk) }}" 
                    class="p-2 text-gray-400 hover:text-gray-600 transition-colors"
                    title="Editar"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </a>
            @endcan
        </div>
    </div>
    
    @if($showDetails)
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="grid grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-gray-500">Estrategia:</span>
                    <span class="font-medium text-gray-700 ml-1">{{ $risk->strategy_label }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Estado:</span>
                    <span class="font-medium text-gray-700 ml-1">{{ ucfirst($risk->status) }}</span>
                </div>
                @if($risk->area)
                    <div>
                        <span class="text-gray-500">Área:</span>
                        <span class="font-medium text-gray-700 ml-1">{{ $risk->area->name }}</span>
                    </div>
                @endif
                @if($risk->mitigationActions->count() > 0)
                    <div>
                        <span class="text-gray-500">Acciones:</span>
                        <span class="font-medium text-gray-700 ml-1">{{ $risk->mitigationActions->count() }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
