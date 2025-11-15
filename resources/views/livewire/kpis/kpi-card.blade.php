<div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-{{ $kpi->status_color }}-500 hover:shadow-lg transition-all">
    <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-2">
                <h3 class="text-base font-bold text-gray-900 truncate">{{ $kpi->name }}</h3>
                <x-ui.badge :variant="$kpi->status === 'green' ? 'success' : ($kpi->status === 'yellow' ? 'warning' : 'error')">
                    {{ $kpi->status === 'green' ? '✅' : ($kpi->status === 'yellow' ? '⚠️' : '❌') }}
                </x-ui.badge>
            </div>
            
            @if($kpi->description)
                <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ $kpi->description }}</p>
            @endif
            
            <div class="flex items-center gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Actual:</span>
                    <span class="font-bold text-gray-900 ml-1">{{ number_format($kpi->current_value, 2) }} {{ $kpi->unit }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Meta:</span>
                    <span class="font-semibold text-gray-700 ml-1">{{ number_format($kpi->target_value, 2) }} {{ $kpi->unit }}</span>
                </div>
                @if($kpi->percentage !== null)
                    <div>
                        <span class="text-gray-500">Cumplimiento:</span>
                        <span class="font-bold text-{{ $kpi->status_color }}-600 ml-1">{{ number_format($kpi->percentage, 1) }}%</span>
                    </div>
                @endif
            </div>
            
            @if($kpi->responsible)
                <div class="mt-2 text-xs text-gray-500">
                    Responsable: <span class="font-medium">{{ $kpi->responsible->name }}</span>
                </div>
            @endif
            
            @if($kpi->last_updated_at)
                <div class="mt-1 text-xs text-gray-400">
                    Actualizado: {{ $kpi->last_updated_at->diffForHumans() }}
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
            
            @can('update', $kpi)
                <a 
                    href="{{ route('kpis.show', $kpi) }}" 
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
                    <span class="text-gray-500">Tipo:</span>
                    <span class="font-medium text-gray-700 ml-1">{{ $kpi->type }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Frecuencia:</span>
                    <span class="font-medium text-gray-700 ml-1">{{ $kpi->update_frequency ?? 'N/A' }}</span>
                </div>
                @if($kpi->plan)
                    <div>
                        <span class="text-gray-500">Plan:</span>
                        <a href="{{ route('plans.show', $kpi->plan) }}" class="font-medium text-red-600 hover:text-red-700 ml-1">
                            {{ $kpi->plan->name }}
                        </a>
                    </div>
                @endif
                @if($kpi->area)
                    <div>
                        <span class="text-gray-500">Área:</span>
                        <span class="font-medium text-gray-700 ml-1">{{ $kpi->area->name }}</span>
                    </div>
                @endif
            </div>
            
            <div class="mt-3">
                <a href="{{ route('kpis.history.index', $kpi) }}" class="text-xs text-red-600 hover:text-red-700 font-medium">
                    Ver historial completo →
                </a>
            </div>
        </div>
    @endif
</div>
