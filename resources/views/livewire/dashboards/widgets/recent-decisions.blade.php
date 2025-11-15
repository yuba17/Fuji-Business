<div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">Decisiones Recientes</h3>
        <div class="flex items-center gap-3">
            @if($pendingDecisions > 0)
                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-lg">
                    {{ $pendingDecisions }} pendientes
                </span>
            @endif
            <button wire:click="loadData" class="p-1 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </button>
        </div>
    </div>
    
    @if($recentDecisions->count() > 0)
        <div class="space-y-3">
            @foreach($recentDecisions as $decision)
                <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 hover:border-purple-300 transition-all">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="font-semibold text-sm text-gray-900 mb-1">{{ $decision->title }}</p>
                            <div class="flex items-center gap-2 text-xs text-gray-600">
                                <span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded font-medium">
                                    {{ $decision->status_label }}
                                </span>
                                <span>{{ $decision->decision_date->format('d/m/Y') }}</span>
                                @if($decision->proponent)
                                    <span>• {{ $decision->proponent->name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4 pt-4 border-t border-gray-200">
            <a href="{{ route('decisions.index') }}" class="text-sm font-semibold text-purple-600 hover:text-purple-700 transition-colors">
                Ver todas las decisiones →
            </a>
        </div>
    @else
        <div class="text-center py-8">
            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-sm text-gray-600">No hay decisiones registradas</p>
        </div>
    @endif
</div>
