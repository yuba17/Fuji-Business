<div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">Carga del Equipo</h3>
        <button wire:click="loadData" class="p-1 text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
        </button>
    </div>
    
    @if($overdueTasks > 0)
        <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 rounded-lg">
            <p class="text-sm font-semibold text-red-700">
                ⚠️ {{ $overdueTasks }} tareas vencidas
            </p>
        </div>
    @endif

    @if($teamWorkload->count() > 0)
        <div class="space-y-3">
            @foreach($teamWorkload as $workload)
                <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                {{ $workload['user']->initials() }}
                            </div>
                            <span class="font-semibold text-sm text-gray-900">{{ $workload['user']->name }}</span>
                        </div>
                        <span class="text-lg font-bold text-gray-900">{{ $workload['total_tasks'] }}</span>
                    </div>
                    <div class="flex items-center gap-4 text-xs text-gray-600">
                        @if($workload['overdue_tasks'] > 0)
                            <span class="text-red-600 font-semibold">
                                {{ $workload['overdue_tasks'] }} vencidas
                            </span>
                        @endif
                        @if($workload['estimated_hours'] > 0)
                            <span>{{ $workload['estimated_hours'] }}h estimadas</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4 pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600 text-center">
                <span class="font-semibold text-gray-900">{{ $totalTasks }}</span> tareas activas en total
            </p>
        </div>
    @else
        <div class="text-center py-8">
            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <p class="text-sm text-gray-600">No hay carga de trabajo asignada</p>
        </div>
    @endif
</div>
