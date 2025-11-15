<div>
    <!-- Controles de Vista -->
    <div class="mb-6 flex items-center justify-between">
        <div class="flex gap-2">
            <button 
                wire:click="setViewMode('gantt')"
                class="px-4 py-2 text-sm font-semibold rounded-xl transition-all {{ $viewMode === 'gantt' ? 'bg-gradient-to-r from-red-600 to-orange-600 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}"
            >
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Vista Gantt
            </button>
            <button 
                wire:click="setViewMode('list')"
                class="px-4 py-2 text-sm font-semibold rounded-xl transition-all {{ $viewMode === 'list' ? 'bg-gradient-to-r from-red-600 to-orange-600 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}"
            >
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                Vista Lista
            </button>
        </div>
    </div>

    @if($viewMode === 'gantt')
        <!-- Vista Gantt -->
        <x-ui.card>
            <div class="overflow-x-auto">
                <div class="min-w-full">
                    <!-- Header de fechas -->
                    <div class="flex border-b-2 border-gray-200 pb-2 mb-4">
                        <div class="w-64 flex-shrink-0 font-semibold text-gray-700">Hito</div>
                        <div class="flex-1">
                            <div class="grid grid-cols-12 gap-1 text-xs text-gray-600">
                                @php
                                    $startDate = $milestones->min('start_date') ?? now();
                                    $endDate = $milestones->max('target_date') ?? now()->addMonths(6);
                                    $months = [];
                                    $current = \Carbon\Carbon::parse($startDate)->startOfMonth();
                                    while ($current <= \Carbon\Carbon::parse($endDate)) {
                                        $months[] = $current->copy();
                                        $current->addMonth();
                                    }
                                @endphp
                                @foreach($months as $month)
                                    <div class="text-center">{{ $month->format('M Y') }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Filas de hitos -->
                    <div class="space-y-4">
                        @foreach($milestones as $milestone)
                            <div class="flex items-center border-b border-gray-100 pb-4">
                                <div class="w-64 flex-shrink-0">
                                    <div class="flex items-center gap-2">
                                        <a 
                                            href="{{ route('plans.milestones.show', [$plan, $milestone]) }}"
                                            class="font-semibold text-gray-900 hover:text-red-600 transition-colors"
                                        >
                                            {{ $milestone->name }}
                                        </a>
                                        @if($milestone->isDelayed())
                                            <x-ui.badge variant="error">⚠️ Retrasado</x-ui.badge>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        @if($milestone->responsible)
                                            {{ $milestone->responsible->name }}
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1 relative h-12">
                                    @if($milestone->start_date && $milestone->target_date)
                                        @php
                                            $start = \Carbon\Carbon::parse($milestone->start_date);
                                            $end = \Carbon\Carbon::parse($milestone->target_date);
                                            $totalDays = $start->diffInDays($end);
                                            $startOffset = \Carbon\Carbon::parse($startDate)->startOfMonth()->diffInDays($start);
                                            $width = ($totalDays / 30) * 100; // Aproximación mensual
                                            $left = ($startOffset / 30) * 100;
                                        @endphp
                                        <div 
                                            class="absolute h-8 rounded-lg flex items-center justify-center text-xs font-medium text-white transition-all hover:scale-105"
                                            style="left: {{ max(0, $left) }}%; width: {{ max(5, $width) }}%; background: {{ $milestone->status === 'completed' ? 'linear-gradient(to right, #10b981, #059669)' : ($milestone->status === 'in_progress' ? 'linear-gradient(to right, #f59e0b, #d97706)' : ($milestone->isDelayed() ? 'linear-gradient(to right, #ef4444, #dc2626)' : 'linear-gradient(to right, #6366f1, #4f46e5)')); }}"
                                            title="{{ $milestone->name }}: {{ $start->format('d/m/Y') }} - {{ $end->format('d/m/Y') }}"
                                        >
                                            <span class="truncate px-2">{{ $milestone->name }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </x-ui.card>
    @else
        <!-- Vista Lista -->
        <div class="space-y-4">
            @forelse($milestones as $milestone)
                <x-ui.card>
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-bold text-gray-900">
                                    <a 
                                        href="{{ route('plans.milestones.show', [$plan, $milestone]) }}"
                                        class="hover:text-red-600 transition-colors"
                                    >
                                        {{ $milestone->name }}
                                    </a>
                                </h3>
                                <x-ui.badge 
                                    variant="{{ $milestone->status === 'completed' ? 'success' : ($milestone->status === 'delayed' ? 'error' : ($milestone->status === 'in_progress' ? 'warning' : 'info')) }}"
                                >
                                    {{ $milestone->status_label }}
                                </x-ui.badge>
                                @if($milestone->isDelayed())
                                    <x-ui.badge variant="error">⚠️ Retrasado</x-ui.badge>
                                @endif
                            </div>
                            
                            @if($milestone->description)
                                <p class="text-sm text-gray-600 mb-3">{{ $milestone->description }}</p>
                            @endif
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Inicio:</span>
                                    <span class="font-medium text-gray-900 ml-1">
                                        {{ $milestone->start_date ? $milestone->start_date->format('d/m/Y') : '-' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Objetivo:</span>
                                    <span class="font-medium text-gray-900 ml-1">
                                        {{ $milestone->target_date->format('d/m/Y') }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Progreso:</span>
                                    <span class="font-bold text-gray-900 ml-1">{{ $milestone->progress_percentage }}%</span>
                                </div>
                                @if($milestone->responsible)
                                    <div>
                                        <span class="text-gray-500">Responsable:</span>
                                        <span class="font-medium text-gray-900 ml-1">{{ $milestone->responsible->name }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            @if($milestone->tasks->count() > 0)
                                <div class="mt-3 text-xs text-gray-500">
                                    {{ $milestone->tasks->count() }} tarea(s) asociada(s)
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex gap-2 ml-4">
                            @can('update', $plan)
                                <a 
                                    href="{{ route('plans.milestones.edit', [$plan, $milestone]) }}"
                                    class="p-2 text-gray-400 hover:text-gray-600 transition-colors"
                                    title="Editar"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                            @endcan
                        </div>
                    </div>
                </x-ui.card>
            @empty
                <x-ui.card>
                    <div class="text-center py-12 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <p class="text-sm">No hay hitos definidos para este plan</p>
                        @can('update', $plan)
                            <a 
                                href="{{ route('plans.milestones.create', $plan) }}"
                                class="mt-4 inline-block px-4 py-2 text-sm font-semibold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700"
                            >
                                Crear Primer Hito
                            </a>
                        @endcan
                    </div>
                </x-ui.card>
            @endforelse
        </div>
    @endif
</div>
