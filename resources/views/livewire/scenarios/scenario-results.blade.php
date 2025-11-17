<div>
    @if($results)
        <!-- Resumen del Impacto General -->
        <x-ui.card class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-gray-900">Resumen del Impacto</h2>
                <button wire:click="recalculate" 
                        class="px-4 py-2 text-sm font-semibold text-purple-700 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Recalcular
                </button>
            </div>

            @if(isset($results['overall_impact_score']))
                <div class="p-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Puntuación de Impacto General</p>
                            <p class="text-4xl font-bold {{ $results['overall_impact_score'] < 5 ? 'text-green-600' : ($results['overall_impact_score'] < 10 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $results['overall_impact_score'] }}/20
                            </p>
                        </div>
                        <div class="text-right">
                            @if($results['overall_impact_score'] < 5)
                                <span class="px-4 py-2 bg-green-100 text-green-800 text-sm font-bold rounded-full">✅ Bajo Impacto</span>
                            @elseif($results['overall_impact_score'] < 10)
                                <span class="px-4 py-2 bg-yellow-100 text-yellow-800 text-sm font-bold rounded-full">⚠️ Impacto Medio</span>
                            @else
                                <span class="px-4 py-2 bg-red-100 text-red-800 text-sm font-bold rounded-full">❌ Alto Impacto</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($results['overall_recommendations']) && count($results['overall_recommendations']) > 0)
                <div class="mt-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Recomendaciones</h3>
                    <ul class="space-y-2">
                        @foreach($results['overall_recommendations'] as $recommendation)
                            <li class="flex items-start gap-2 p-3 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                <span class="text-sm text-gray-700">{{ $recommendation }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </x-ui.card>

        <!-- Impactos Detallados -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @if(isset($results['budget_simulation']))
                <x-ui.card>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Impacto en Presupuesto
                    </h3>
                    <div class="space-y-3">
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <p class="text-sm text-gray-600">Cambio: <span class="font-bold text-blue-600">{{ $results['budget_simulation']['budget_change_percentage'] }}%</span></p>
                            <p class="text-sm text-gray-600 mt-1">Presupuesto Original: <span class="font-semibold">{{ number_format($results['budget_simulation']['original_budget'], 2) }}€</span></p>
                            <p class="text-sm text-gray-600 mt-1">Presupuesto Simulado: <span class="font-semibold">{{ number_format($results['budget_simulation']['simulated_budget'], 2) }}€</span></p>
                        </div>
                        @if(isset($results['budget_simulation']['kpi_impact']) && count($results['budget_simulation']['kpi_impact']) > 0)
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-2">Impacto en KPIs:</p>
                                <ul class="space-y-1">
                                    @foreach(array_slice($results['budget_simulation']['kpi_impact'], 0, 3) as $impact)
                                        <li class="text-xs text-gray-600 flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full {{ $impact['impact'] === 'positivo' ? 'bg-green-500' : ($impact['impact'] === 'negativo' ? 'bg-red-500' : 'bg-gray-400') }}"></span>
                                            {{ $impact['name'] }}: {{ $impact['impact'] }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </x-ui.card>
            @endif

            @if(isset($results['team_simulation']))
                <x-ui.card>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Impacto en Equipo
                    </h3>
                    <div class="space-y-3">
                        <div class="p-3 bg-green-50 rounded-lg">
                            <p class="text-sm text-gray-600">Cambio: <span class="font-bold text-green-600">{{ $results['team_simulation']['team_change'] > 0 ? '+' : '' }}{{ $results['team_simulation']['team_change'] }} personas</span></p>
                            <p class="text-sm text-gray-600 mt-1">Tamaño Original: <span class="font-semibold">{{ $results['team_simulation']['original_team_size'] }} personas</span></p>
                            <p class="text-sm text-gray-600 mt-1">Tamaño Simulado: <span class="font-semibold">{{ $results['team_simulation']['simulated_team_size'] }} personas</span></p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600">Carga de Trabajo: <span class="font-semibold capitalize">{{ $results['team_simulation']['workload_impact'] }}</span></p>
                            <p class="text-sm text-gray-600 mt-1">Impacto en Tiempo: <span class="font-semibold capitalize">{{ $results['team_simulation']['time_impact'] }}</span></p>
                        </div>
                    </div>
                </x-ui.card>
            @endif

            @if(isset($results['delay_simulation']))
                <x-ui.card class="lg:col-span-2">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Impacto del Retraso
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-3 bg-orange-50 rounded-lg">
                            <p class="text-sm text-gray-600">Retraso: <span class="font-bold text-orange-600">{{ $results['delay_simulation']['delay_days'] }} días</span></p>
                            <p class="text-sm text-gray-600 mt-1">Fecha Original: <span class="font-semibold">{{ \Carbon\Carbon::parse($results['delay_simulation']['original_target_date'])->format('d/m/Y') }}</span></p>
                            <p class="text-sm text-gray-600 mt-1">Fecha Simulada: <span class="font-semibold">{{ \Carbon\Carbon::parse($results['delay_simulation']['simulated_target_date'])->format('d/m/Y') }}</span></p>
                        </div>
                        @if(isset($results['delay_simulation']['milestone_impact']) && count($results['delay_simulation']['milestone_impact']) > 0)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm font-medium text-gray-700 mb-2">Hitos Afectados:</p>
                                <p class="text-2xl font-bold text-orange-600">{{ count($results['delay_simulation']['milestone_impact']) }}</p>
                            </div>
                        @endif
                        @if(isset($results['delay_simulation']['task_impact']) && count($results['delay_simulation']['task_impact']) > 0)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm font-medium text-gray-700 mb-2">Tareas Afectadas:</p>
                                <p class="text-2xl font-bold text-orange-600">{{ count($results['delay_simulation']['task_impact']) }}</p>
                            </div>
                        @endif
                    </div>
                </x-ui.card>
            @endif
        </div>
    @else
        <x-ui.card>
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay resultados disponibles</h3>
                <p class="text-sm text-gray-600 mb-6">Los resultados de la simulación se están calculando...</p>
                <button wire:click="loadResults" 
                        class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all">
                    Cargar Resultados
                </button>
            </div>
        </x-ui.card>
    @endif
</div>
