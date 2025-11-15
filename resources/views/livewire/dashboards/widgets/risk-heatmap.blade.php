<div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">Mapa de Calor de Riesgos</h3>
        <button wire:click="loadData" class="p-1 text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
        </button>
    </div>
    
    @if($totalRisks > 0)
        <div class="grid grid-cols-4 gap-3">
            <div class="text-center">
                <div class="w-full h-16 bg-red-100 border-2 border-red-500 rounded-lg flex items-center justify-center mb-2">
                    <span class="text-2xl font-bold text-red-700">{{ $riskDistribution['critico'] ?? 0 }}</span>
                </div>
                <p class="text-xs font-semibold text-gray-700">Crítico</p>
            </div>
            <div class="text-center">
                <div class="w-full h-16 bg-orange-100 border-2 border-orange-500 rounded-lg flex items-center justify-center mb-2">
                    <span class="text-2xl font-bold text-orange-700">{{ $riskDistribution['alto'] ?? 0 }}</span>
                </div>
                <p class="text-xs font-semibold text-gray-700">Alto</p>
            </div>
            <div class="text-center">
                <div class="w-full h-16 bg-yellow-100 border-2 border-yellow-500 rounded-lg flex items-center justify-center mb-2">
                    <span class="text-2xl font-bold text-yellow-700">{{ $riskDistribution['medio'] ?? 0 }}</span>
                </div>
                <p class="text-xs font-semibold text-gray-700">Medio</p>
            </div>
            <div class="text-center">
                <div class="w-full h-16 bg-green-100 border-2 border-green-500 rounded-lg flex items-center justify-center mb-2">
                    <span class="text-2xl font-bold text-green-700">{{ $riskDistribution['bajo'] ?? 0 }}</span>
                </div>
                <p class="text-xs font-semibold text-gray-700">Bajo</p>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600 text-center">
                <span class="font-semibold text-gray-900">{{ $totalRisks }}</span> riesgos totales identificados
            </p>
        </div>
    @else
        <div class="text-center py-8">
            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <p class="text-sm text-gray-600">No hay riesgos identificados</p>
        </div>
    @endif
</div>
