<div>
    <!-- Métricas Comerciales Generales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-md p-4 text-white">
            <p class="text-blue-100 text-xs font-medium uppercase tracking-wide mb-1">Total Clientes</p>
            <p class="text-3xl font-bold">{{ $commercialMetrics['total_clients'] }}</p>
            <p class="text-blue-100 text-xs mt-1">Clientes asociados</p>
        </div>
        
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-md p-4 text-white">
            <p class="text-green-100 text-xs font-medium uppercase tracking-wide mb-1">Total Proyectos</p>
            <p class="text-3xl font-bold">{{ $commercialMetrics['total_projects'] }}</p>
            <p class="text-green-100 text-xs mt-1">Proyectos activos</p>
        </div>
        
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-md p-4 text-white">
            <p class="text-purple-100 text-xs font-medium uppercase tracking-wide mb-1">Presupuesto Total</p>
            <p class="text-3xl font-bold">{{ number_format($commercialMetrics['total_budget'], 2, ',', '.') }} €</p>
            <p class="text-purple-100 text-xs mt-1">Presupuesto acumulado</p>
        </div>
        
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-md p-4 text-white">
            <p class="text-orange-100 text-xs font-medium uppercase tracking-wide mb-1">Sectores</p>
            <p class="text-3xl font-bold">{{ $commercialMetrics['sectors_count'] }}</p>
            <p class="text-orange-100 text-xs mt-1">Sectores económicos</p>
        </div>
    </div>

    <!-- Análisis por Sector -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-indigo-500 mb-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                📊
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Análisis por Sector Económico</h2>
        </div>

        @if(count($sectorData) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                @foreach($sectorData as $sector)
                    <div wire:click="selectSector('{{ $sector['sector'] }}')" 
                         class="border-2 rounded-xl p-4 cursor-pointer transition-all hover:shadow-lg {{ $selectedSector === $sector['sector'] ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-indigo-300' }}">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="text-lg font-bold text-gray-900">{{ $sector['sector'] }}</h3>
                            @if($selectedSector === $sector['sector'])
                                <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Clientes</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $sector['clients_count'] }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Proyectos</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $sector['projects_count'] }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Presupuesto</p>
                                <p class="text-lg font-semibold text-gray-900">{{ number_format($sector['total_budget'], 2, ',', '.') }} €</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Activos</p>
                                <p class="text-lg font-semibold text-green-600">{{ $sector['active_projects'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($selectedData)
                <div class="mt-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Detalle del Sector: {{ $selectedData['sector'] }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Proyectos Activos</p>
                            <p class="text-3xl font-bold text-green-600">{{ $selectedData['active_projects'] }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Proyectos Completados</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $selectedData['completed_projects'] }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Proyectos Pendientes</p>
                            <p class="text-3xl font-bold text-orange-600">{{ $selectedData['pending_projects'] }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 bg-white rounded-lg p-4 border border-gray-200">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Presupuesto Promedio por Proyecto</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($selectedData['average_budget'], 2, ',', '.') }} €</p>
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-8">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <p class="text-sm text-gray-500">No hay datos sectoriales disponibles para este plan comercial</p>
                <p class="text-xs text-gray-400 mt-2">Asocia clientes y proyectos al plan para ver el análisis sectorial</p>
            </div>
        @endif
    </div>
</div>

