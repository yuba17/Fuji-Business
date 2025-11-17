<div>
    <x-ui.card class="mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Seleccionar Escenarios para Comparar</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Escenario 1</label>
                <select wire:model="scenario1Id" 
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    <option value="">-- Seleccionar --</option>
                    @foreach($scenarios as $scenario)
                        <option value="{{ $scenario->id }}">{{ $scenario->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Escenario 2</label>
                <select wire:model="scenario2Id" 
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    <option value="">-- Seleccionar --</option>
                    @foreach($scenarios as $scenario)
                        <option value="{{ $scenario->id }}">{{ $scenario->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-4">
            <button wire:click="compare" 
                    :disabled="!scenario1Id || !scenario2Id || scenario1Id === scenario2Id"
                    class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Comparar Escenarios
            </button>
        </div>
    </x-ui.card>

    @if($comparison)
        <div class="space-y-6">
            <!-- Resumen de Comparación -->
            <x-ui.card>
                <h2 class="text-xl font-bold text-gray-900 mb-4">Resumen de Comparación</h2>
                @if(isset($comparison['summary']))
                    <div class="p-4 bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl border-l-4 border-purple-500">
                        <p class="text-sm text-gray-700">{{ $comparison['summary'] }}</p>
                    </div>
                @endif
            </x-ui.card>

            <!-- Comparación Lado a Lado -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Escenario 1 -->
                <x-ui.card>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Escenario 1</h3>
                    @if(isset($comparison['scenario1']['overall_impact_score']))
                        <div class="p-4 bg-gray-50 rounded-lg mb-4">
                            <p class="text-sm text-gray-600 mb-1">Puntuación de Impacto</p>
                            <p class="text-3xl font-bold {{ $comparison['scenario1']['overall_impact_score'] < 5 ? 'text-green-600' : ($comparison['scenario1']['overall_impact_score'] < 10 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $comparison['scenario1']['overall_impact_score'] }}/20
                            </p>
                        </div>
                    @endif
                    @if(isset($comparison['scenario1']['overall_recommendations']) && count($comparison['scenario1']['overall_recommendations']) > 0)
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Recomendaciones:</p>
                            <ul class="space-y-1">
                                @foreach(array_slice($comparison['scenario1']['overall_recommendations'], 0, 3) as $rec)
                                    <li class="text-xs text-gray-600 flex items-start gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500 mt-1.5 flex-shrink-0"></span>
                                        {{ $rec }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </x-ui.card>

                <!-- Escenario 2 -->
                <x-ui.card>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Escenario 2</h3>
                    @if(isset($comparison['scenario2']['overall_impact_score']))
                        <div class="p-4 bg-gray-50 rounded-lg mb-4">
                            <p class="text-sm text-gray-600 mb-1">Puntuación de Impacto</p>
                            <p class="text-3xl font-bold {{ $comparison['scenario2']['overall_impact_score'] < 5 ? 'text-green-600' : ($comparison['scenario2']['overall_impact_score'] < 10 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $comparison['scenario2']['overall_impact_score'] }}/20
                            </p>
                        </div>
                    @endif
                    @if(isset($comparison['scenario2']['overall_recommendations']) && count($comparison['scenario2']['overall_recommendations']) > 0)
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Recomendaciones:</p>
                            <ul class="space-y-1">
                                @foreach(array_slice($comparison['scenario2']['overall_recommendations'], 0, 3) as $rec)
                                    <li class="text-xs text-gray-600 flex items-start gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5 flex-shrink-0"></span>
                                        {{ $rec }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </x-ui.card>
            </div>

            <!-- Diferencias -->
            @if(isset($comparison['differences']) && count($comparison['differences']) > 0)
                <x-ui.card>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Diferencias Clave</h3>
                    <ul class="space-y-2">
                        @foreach($comparison['differences'] as $key => $difference)
                            <li class="p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-400">
                                <p class="text-sm text-gray-700">{{ $difference }}</p>
                            </li>
                        @endforeach
                    </ul>
                </x-ui.card>
            @endif
        </div>
    @elseif($scenario1Id && $scenario2Id && $scenario1Id === $scenario2Id)
        <x-ui.card>
            <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
                <p class="text-sm text-yellow-800">Por favor, selecciona dos escenarios diferentes para comparar.</p>
            </div>
        </x-ui.card>
    @endif
</div>
