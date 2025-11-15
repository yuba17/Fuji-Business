<div>
    @if($risk->mitigationActions->count() > 0)
        <div class="space-y-3">
            @foreach($risk->mitigationActions as $action)
                <div class="border-l-4 border-blue-500 pl-4 py-3 bg-gray-50 rounded-r-lg">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <h3 class="font-semibold text-gray-900">{{ $action->action }}</h3>
                                <x-ui.badge 
                                    variant="{{ $action->status === 'completed' ? 'success' : ($action->status === 'in_progress' ? 'warning' : ($action->status === 'cancelled' ? 'error' : 'info')) }}"
                                >
                                    {{ $action->status_label }}
                                </x-ui.badge>
                            </div>
                            @if($action->description)
                                <p class="text-sm text-gray-600 mt-1">{{ $action->description }}</p>
                            @endif
                            <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                @if($action->responsible)
                                    <span>Responsable: <strong>{{ $action->responsible->name }}</strong></span>
                                @endif
                                @if($action->target_date)
                                    <span>Objetivo: <strong>{{ $action->target_date->format('d/m/Y') }}</strong></span>
                                @endif
                                @if($action->cost)
                                    <span>Costo: <strong>${{ number_format($action->cost, 2) }}</strong></span>
                                @endif
                            </div>
                            @if($action->expected_probability_reduction || $action->expected_impact_reduction)
                                <div class="mt-2 text-xs text-gray-500">
                                    Reducción esperada: 
                                    @if($action->expected_probability_reduction)
                                        Probabilidad: -{{ $action->expected_probability_reduction }}
                                    @endif
                                    @if($action->expected_impact_reduction)
                                        Impacto: -{{ $action->expected_impact_reduction }}
                                    @endif
                                </div>
                            @endif
                            @if($action->notes)
                                <p class="text-xs text-gray-600 mt-2 italic">{{ $action->notes }}</p>
                            @endif
                        </div>
                        @can('update', $risk)
                            <div class="flex gap-2 ml-4">
                                <a 
                                    href="{{ route('risks.mitigation-actions.edit', [$risk, $action]) }}"
                                    class="p-2 text-gray-400 hover:text-gray-600 transition-colors"
                                    title="Editar"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm">No hay acciones de mitigación definidas</p>
            @can('update', $risk)
                <p class="text-xs text-gray-400 mt-1">Crea la primera acción para comenzar a mitigar este riesgo</p>
            @endcan
        </div>
    @endif
</div>
