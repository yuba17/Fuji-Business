<div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">Resumen de KPIs</h3>
        <button wire:click="loadData" class="p-1 text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
        </button>
    </div>
    
    <div class="grid grid-cols-2 gap-4">
        <div>
            <p class="text-sm text-gray-600 mb-1">Total KPIs</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalKpis }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600 mb-1">En Ruta</p>
            <p class="text-2xl font-bold text-green-600">{{ $onTrackKpis }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600 mb-1">En Riesgo</p>
            <p class="text-2xl font-bold text-red-600">{{ $atRiskKpis }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600 mb-1">Activos</p>
            <p class="text-2xl font-bold text-blue-600">{{ $activeKpis }}</p>
        </div>
    </div>
</div>
