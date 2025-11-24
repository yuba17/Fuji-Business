<div class="space-y-6">
    {{-- Estadísticas --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
            <p class="text-xs font-medium text-blue-700 uppercase tracking-wide">Total</p>
            <p class="mt-2 text-2xl font-bold text-blue-900">{{ $stats['total'] }}</p>
            <p class="mt-1 text-xs text-blue-800">Evaluaciones</p>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
            <p class="text-xs font-medium text-green-700 uppercase tracking-wide">Completadas</p>
            <p class="mt-2 text-2xl font-bold text-green-900">{{ $stats['completed'] }}</p>
            <p class="mt-1 text-xs text-green-800">Finalizadas</p>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
            <p class="text-xs font-medium text-purple-700 uppercase tracking-wide">Aprobadas</p>
            <p class="mt-2 text-2xl font-bold text-purple-900">{{ $stats['approved'] }}</p>
            <p class="mt-1 text-xs text-purple-800">Confirmadas</p>
        </div>
        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 border border-red-200">
            <p class="text-xs font-medium text-red-700 uppercase tracking-wide">Vencidas</p>
            <p class="mt-2 text-2xl font-bold text-red-900">{{ $stats['overdue'] }}</p>
            <p class="mt-1 text-xs text-red-800">Pendientes</p>
        </div>
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 border border-orange-200">
            <p class="text-xs font-medium text-orange-700 uppercase tracking-wide">Próximas</p>
            <p class="mt-2 text-2xl font-bold text-orange-900">{{ $stats['upcoming'] }}</p>
            <p class="mt-1 text-xs text-orange-800">En 30 días</p>
        </div>
        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-4 border border-indigo-200">
            <p class="text-xs font-medium text-indigo-700 uppercase tracking-wide">Promedio</p>
            <p class="mt-2 text-2xl font-bold text-indigo-900">{{ $stats['avg_score'] }}/5</p>
            <p class="mt-1 text-xs text-indigo-800">Puntuación</p>
        </div>
    </div>

    {{-- Próxima evaluación --}}
    @if($nextEvaluation)
    <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl p-4 border-2 border-indigo-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-indigo-700 uppercase tracking-wide mb-1">Próxima Evaluación</p>
                <p class="text-lg font-bold text-indigo-900">{{ $nextEvaluation->type_label }}</p>
                <p class="text-sm text-indigo-700 mt-1">
                    Fecha: {{ $nextEvaluation->next_evaluation_date->format('d/m/Y') }}
                    ({{ $nextEvaluation->next_evaluation_date->diffForHumans() }})
                </p>
            </div>
            <div class="text-right">
                <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-bold rounded-full">
                    {{ $nextEvaluation->status_label }}
                </span>
            </div>
        </div>
    </div>
    @endif

    {{-- Filtros --}}
    <div class="bg-white rounded-xl shadow-md p-4 border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Buscar</label>
                <input type="text" wire:model.live.debounce.300ms="search" 
                       placeholder="Buscar en evaluaciones..."
                       class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Tipo</label>
                <select wire:model.live="type" 
                        class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    <option value="">Todos</option>
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Estado</label>
                <select wire:model.live="status" 
                        class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    <option value="">Todos</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Lista de evaluaciones --}}
    @if($evaluations->count() > 0)
        <div class="space-y-4">
            @foreach($evaluations as $evaluation)
                <div class="bg-white rounded-xl shadow-md p-5 border-l-4 
                    @if($evaluation->status === 'approved') border-green-500
                    @elseif($evaluation->status === 'completed') border-blue-500
                    @elseif($evaluation->status === 'rejected') border-red-500
                    @else border-gray-300
                    @endif">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-bold text-gray-900">{{ $evaluation->type_label }}</h3>
                                <span class="px-2 py-1 text-xs font-bold rounded-full
                                    @if($evaluation->status === 'approved') bg-green-100 text-green-800
                                    @elseif($evaluation->status === 'completed') bg-blue-100 text-blue-800
                                    @elseif($evaluation->status === 'rejected') bg-red-100 text-red-800
                                    @elseif($evaluation->status === 'in_progress') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $evaluation->status_label }}
                                </span>
                            </div>
                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                <span>📅 {{ $evaluation->evaluation_date->format('d/m/Y') }}</span>
                                @if($evaluation->evaluator)
                                    <span>👤 Evaluado por: {{ $evaluation->evaluator->name }}</span>
                                @endif
                                @if($evaluation->overall_score)
                                    <span class="font-bold text-indigo-600">⭐ {{ $evaluation->overall_score }}/5</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($evaluation->strengths || $evaluation->feedback)
                        <div class="mt-4 space-y-3">
                            @if($evaluation->strengths)
                                <div>
                                    <p class="text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">Fortalezas</p>
                                    <p class="text-sm text-gray-800">{{ $evaluation->strengths }}</p>
                                </div>
                            @endif
                            @if($evaluation->feedback)
                                <div>
                                    <p class="text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">Feedback</p>
                                    <p class="text-sm text-gray-800">{{ $evaluation->feedback }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($evaluation->next_evaluation_date)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-600">
                                Próxima evaluación: <span class="font-semibold">{{ $evaluation->next_evaluation_date->format('d/m/Y') }}</span>
                                @if($evaluation->isOverdue())
                                    <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-800 text-xs font-bold rounded-full">⚠️ Vencida</span>
                                @elseif($evaluation->isUpcoming())
                                    <span class="ml-2 px-2 py-0.5 bg-orange-100 text-orange-800 text-xs font-bold rounded-full">🔔 Próxima</span>
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach

            {{ $evaluations->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-xl shadow-md border border-gray-200">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-sm text-gray-500">No hay evaluaciones registradas todavía.</p>
        </div>
    @endif
</div>
