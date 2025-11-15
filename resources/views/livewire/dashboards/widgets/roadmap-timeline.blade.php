<div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">Línea de Tiempo</h3>
        <button wire:click="loadData" class="p-1 text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
        </button>
    </div>
    
    @if($delayedMilestones->count() > 0)
        <div class="mb-6">
            <h4 class="text-sm font-semibold text-red-600 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Retrasados ({{ $delayedMilestones->count() }})
            </h4>
            <div class="space-y-2">
                @foreach($delayedMilestones as $milestone)
                    <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-3">
                        <p class="font-semibold text-sm text-gray-900">{{ $milestone->name }}</p>
                        <p class="text-xs text-gray-600 mt-1">
                            {{ $milestone->plan->name ?? 'Sin plan' }} • 
                            {{ $milestone->target_date->format('d/m/Y') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($upcomingMilestones->count() > 0)
        <div>
            <h4 class="text-sm font-semibold text-blue-600 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Próximos ({{ $upcomingMilestones->count() }})
            </h4>
            <div class="space-y-2">
                @foreach($upcomingMilestones as $milestone)
                    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-3">
                        <p class="font-semibold text-sm text-gray-900">{{ $milestone->name }}</p>
                        <p class="text-xs text-gray-600 mt-1">
                            {{ $milestone->plan->name ?? 'Sin plan' }} • 
                            {{ $milestone->target_date->format('d/m/Y') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif($delayedMilestones->count() === 0)
        <div class="text-center py-8">
            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-sm text-gray-600">No hay milestones próximos</p>
        </div>
    @endif
</div>
