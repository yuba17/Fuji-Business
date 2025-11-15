<div>
    <!-- Resumen por Sectores -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($sectorData as $sector)
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-xl transition-shadow cursor-pointer"
                 wire:click="selectSector('{{ $sector['sector'] }}')"
                 :class="{ 'ring-2 ring-blue-500': selectedSector === '{{ $sector['sector'] }}' }">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">{{ $sector['sector'] }}</h3>
                    @if($selectedSector === $sector['sector'])
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                </div>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Clientes</span>
                        <span class="text-lg font-bold text-gray-900">{{ $sector['clients_count'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Proyectos</span>
                        <span class="text-lg font-bold text-gray-900">{{ $sector['projects_count'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Activos</span>
                        <span class="text-lg font-bold text-green-600">{{ $sector['active_projects'] }}</span>
                    </div>
                    @if($sector['total_budget'] > 0)
                        <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                            <span class="text-sm text-gray-600">Presupuesto Total</span>
                            <span class="text-lg font-bold text-blue-600">{{ number_format($sector['total_budget'], 2) }} €</span>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Detalle del Sector Seleccionado -->
    @if($selectedData)
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Análisis: {{ $selectedData['sector'] }}</h2>
                <button wire:click="selectSector(null)" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all">
                    Cerrar
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-md p-4 text-white">
                    <p class="text-blue-100 text-xs font-medium uppercase tracking-wide">Total Clientes</p>
                    <p class="text-3xl font-bold mt-1">{{ $selectedData['clients_count'] }}</p>
                </div>
                
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-md p-4 text-white">
                    <p class="text-green-100 text-xs font-medium uppercase tracking-wide">Total Proyectos</p>
                    <p class="text-3xl font-bold mt-1">{{ $selectedData['projects_count'] }}</p>
                </div>
                
                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-md p-4 text-white">
                    <p class="text-yellow-100 text-xs font-medium uppercase tracking-wide">Proyectos Activos</p>
                    <p class="text-3xl font-bold mt-1">{{ $selectedData['active_projects'] }}</p>
                </div>
                
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-md p-4 text-white">
                    <p class="text-purple-100 text-xs font-medium uppercase tracking-wide">Presupuesto Total</p>
                    <p class="text-3xl font-bold mt-1">{{ number_format($selectedData['total_budget'], 0) }} €</p>
                </div>
            </div>
            
            @if($selectedData['projects_count'] > 0)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Distribución de Proyectos</h3>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Activos</span>
                                    <div class="flex items-center gap-2">
                                        <div class="w-32 bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($selectedData['active_projects'] / $selectedData['projects_count']) * 100 }}%"></div>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900">{{ $selectedData['active_projects'] }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Completados</span>
                                    <div class="flex items-center gap-2">
                                        <div class="w-32 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ ($selectedData['completed_projects'] / $selectedData['projects_count']) * 100 }}%"></div>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900">{{ $selectedData['completed_projects'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Métricas Financieras</h3>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Presupuesto Promedio</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ number_format($selectedData['average_budget'], 2) }} €</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Presupuesto Total</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ number_format($selectedData['total_budget'], 2) }} €</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
