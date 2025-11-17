<div x-data="{ viewMode: 'gantt' }" x-cloak>
    @php use Carbon\Carbon; @endphp
    {{-- Header con acciones --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-bold text-gray-900">Roadmap Operativo</h3>
            <p class="text-xs text-gray-500">Vista Gantt de hitos operativos y dependencias</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2">
                <button @click="viewMode = 'gantt'" 
                        :class="viewMode === 'gantt' ? 'bg-teal-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                        class="px-3 py-2 text-xs font-semibold rounded-lg border border-gray-200 transition-all">
                    Gantt
                </button>
                <button @click="viewMode = 'list'" 
                        :class="viewMode === 'list' ? 'bg-teal-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                        class="px-3 py-2 text-xs font-semibold rounded-lg border border-gray-200 transition-all">
                    Lista
                </button>
                <button @click="viewMode = 'timeline'" 
                        :class="viewMode === 'timeline' ? 'bg-teal-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                        class="px-3 py-2 text-xs font-semibold rounded-lg border border-gray-200 transition-all">
                    Timeline
                </button>
            </div>
            <a href="{{ route('plans.milestones.index', $plan) }}" 
               class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-teal-600 to-cyan-500 text-white rounded-xl hover:from-teal-700 hover:to-cyan-600 transition-all shadow-lg hover:shadow-xl flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Gestionar Hitos
            </a>
        </div>
    </div>

    {{-- Estadísticas rápidas --}}
    <div class="grid grid-cols-1 md:grid-cols-7 gap-4 mb-6">
        <div class="bg-gradient-to-br from-teal-50 to-cyan-100 rounded-xl p-4 border border-teal-200">
            <p class="text-xs font-medium text-teal-700 uppercase tracking-wide">Total</p>
            <p class="mt-2 text-3xl font-bold text-teal-900">{{ $stats['total'] }}</p>
            <p class="mt-1 text-xs text-teal-800">Hitos</p>
        </div>
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
            <p class="text-xs font-medium text-gray-700 uppercase tracking-wide">No Iniciados</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['not_started'] }}</p>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
            <p class="text-xs font-medium text-blue-700 uppercase tracking-wide">En Progreso</p>
            <p class="mt-2 text-3xl font-bold text-blue-900">{{ $stats['in_progress'] }}</p>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
            <p class="text-xs font-medium text-green-700 uppercase tracking-wide">Completados</p>
            <p class="mt-2 text-3xl font-bold text-green-900">{{ $stats['completed'] }}</p>
        </div>
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 border border-orange-200">
            <p class="text-xs font-medium text-orange-700 uppercase tracking-wide">Retrasados</p>
            <p class="mt-2 text-3xl font-bold text-orange-900">{{ $stats['delayed'] }}</p>
        </div>
        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 border border-red-200">
            <p class="text-xs font-medium text-red-700 uppercase tracking-wide">Vencidos</p>
            <p class="mt-2 text-3xl font-bold text-red-900">{{ $stats['overdue'] }}</p>
        </div>
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
            <p class="text-xs font-medium text-gray-700 uppercase tracking-wide">Cancelados</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['cancelled'] }}</p>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="bg-white rounded-xl shadow-md p-4 mb-6 border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Estado</label>
                <select wire:model.live="status" 
                        class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                    <option value="">Todos</option>
                    <option value="not_started">No Iniciado</option>
                    <option value="in_progress">En Progreso</option>
                    <option value="completed">Completado</option>
                    <option value="delayed">Retrasado</option>
                    <option value="cancelled">Cancelado</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Responsable</label>
                <select wire:model.live="responsibleId" 
                        class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                    <option value="">Todos</option>
                    @foreach($teamUsers as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Estado de tareas</label>
                <select wire:model.live="taskStatus" 
                        class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                    <option value="">Todas</option>
                    <option value="todo">Por hacer</option>
                    <option value="in_progress">En progreso</option>
                    <option value="review">En revisión</option>
                    <option value="done">Completadas</option>
                    <option value="cancelled">Canceladas</option>
                </select>
            </div>
<div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Zoom</label>
                <select wire:model.live="zoomLevel" 
                        class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                    <option value="week">Semana</option>
                    <option value="month">Mes</option>
                    <option value="quarter">Trimestre</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <div class="flex-1">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Desde</label>
                    <input type="date" wire:model.live="startDate" 
                           class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Hasta</label>
                    <input type="date" wire:model.live="endDate" 
                           class="w-full px-3 py-2 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                </div>
            </div>
        </div>
    </div>

    {{-- Vista: Gantt --}}
    <div x-show="viewMode === 'gantt'" x-transition>
        @if($milestones->count() > 0)
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase sticky left-0 bg-gradient-to-r from-gray-50 to-gray-100 z-10 min-w-[250px]">
                                    Hito
                                </th>
                                @foreach($timeline as $period)
                                    <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase border-l border-gray-200 min-w-[120px]">
                                        {{ $period['label'] }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($ganttData as $item)
                                @php
                                    $milestone = $item['milestone'];
                                    $startDate = Carbon::parse($item['start']);
                                    $endDate = Carbon::parse($item['end']);
                                    $planStart = Carbon::parse($startDate);
                                    $planEnd = Carbon::parse($endDate);
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 sticky left-0 bg-white z-10 border-r border-gray-200">
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <h4 class="text-sm font-bold text-gray-900">{{ $milestone->name }}</h4>
                                                    @if($item['isDelayed'])
                                                        <span class="px-2 py-0.5 bg-red-100 text-red-800 text-[10px] font-bold rounded-full">⚠️ Retrasado</span>
                                                    @endif
                                                    <span class="px-2 py-0.5 {{ match($milestone->status) {
                                                        'completed' => 'bg-green-100 text-green-800',
                                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                                        'delayed' => 'bg-orange-100 text-orange-800',
                                                        'cancelled' => 'bg-gray-100 text-gray-800',
                                                        default => 'bg-gray-100 text-gray-800'
                                                    } }} text-[10px] font-medium rounded-full">
                                                        {{ $milestone->status_label }}
                                                    </span>
                                                </div>
                                                @if($milestone->responsible)
                                                    <p class="text-xs text-gray-500">{{ $milestone->responsible->name }}</p>
                                                @endif
                                                @if($milestone->progress_percentage > 0)
                                                    <div class="mt-1">
                                                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                            <div class="bg-teal-600 h-1.5 rounded-full transition-all" style="width: {{ $milestone->progress_percentage }}%"></div>
                                                        </div>
                                                        <p class="text-[10px] text-gray-500 mt-0.5">{{ $milestone->progress_percentage }}%</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    @foreach($timeline as $period)
                                        @php
                                            $periodStart = $period['date']->copy();
                                            $periodEnd = $periodStart->copy();
                                            if ($zoomLevel === 'week') {
                                                $periodEnd->addDay();
                                            } elseif ($zoomLevel === 'month') {
                                                $periodEnd->endOfMonth();
                                            } else {
                                                $periodEnd->endOfQuarter();
                                            }
                                            
                                            // Calcular si el milestone se solapa con este período
                                            $overlaps = $startDate <= $periodEnd && $endDate >= $periodStart;
                                            $isStart = $startDate >= $periodStart && $startDate <= $periodEnd;
                                            $isEnd = $endDate >= $periodStart && $endDate <= $periodEnd;
                                            $isWithin = $startDate >= $periodStart && $endDate <= $periodEnd;
                                            
                                            // Calcular porcentaje de ocupación
                                            $overlapStart = max($startDate, $periodStart);
                                            $overlapEnd = min($endDate, $periodEnd);
                                            $overlapDays = max(0, $overlapStart->diffInDays($overlapEnd) + 1);
                                            $periodDays = $periodStart->diffInDays($periodEnd) + 1;
                                            $percentage = $periodDays > 0 ? ($overlapDays / $periodDays) * 100 : 0;
                                        @endphp
                                        <td class="px-2 py-3 border-l border-gray-200 relative">
                                            @if($overlaps)
                                                <div class="relative h-8">
                                                    <div class="absolute inset-0 flex items-center">
                                                        <div class="h-6 rounded {{ match($milestone->status) {
                                                            'completed' => 'bg-green-500',
                                                            'in_progress' => 'bg-blue-500',
                                                            'delayed' => 'bg-orange-500',
                                                            'cancelled' => 'bg-gray-400',
                                                            default => 'bg-gray-300'
                                                        } }} transition-all" 
                                                             style="width: {{ $percentage }}%; {{ $isStart ? 'margin-left: ' . (($startDate->diffInDays($periodStart) / $periodDays) * 100) . '%' : '' }}"
                                                             title="{{ $milestone->name }} ({{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }})">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-xl shadow-md border border-gray-200">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <p class="text-sm text-gray-500 mb-2">No hay hitos en el roadmap</p>
                <a href="{{ route('plans.milestones.index', $plan) }}" 
                   class="mt-4 inline-block px-4 py-2 text-sm font-semibold bg-teal-600 text-white rounded-lg hover:bg-teal-700">
                    Crear Primer Hito
                </a>
            </div>
        @endif
    </div>

    {{-- Vista: Lista --}}
    <div x-show="viewMode === 'list'" x-transition style="display: none;">
        @if($milestones->count() > 0)
            <div class="space-y-3">
                @foreach($milestones as $milestone)
                    <div class="bg-white rounded-xl shadow-md p-4 border border-gray-200 hover:shadow-lg transition-shadow">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <h4 class="text-sm font-bold text-gray-900">{{ $milestone->name }}</h4>
                                    <span class="px-2 py-0.5 {{ match($milestone->status) {
                                        'completed' => 'bg-green-100 text-green-800',
                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                        'delayed' => 'bg-orange-100 text-orange-800',
                                        'cancelled' => 'bg-gray-100 text-gray-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    } }} text-[10px] font-medium rounded-full">
                                        {{ $milestone->status_label }}
                                    </span>
                                    @if($milestone->isDelayed())
                                        <span class="px-2 py-0.5 bg-red-100 text-red-800 text-[10px] font-bold rounded-full">⚠️ Retrasado</span>
                                    @endif
                                </div>
                                @if($milestone->description)
                                    <p class="text-xs text-gray-600 mb-2">{{ Str::limit($milestone->description, 150) }}</p>
                                @endif
                                <div class="flex items-center gap-4 text-xs text-gray-500">
                                    @if($milestone->start_date)
                                        <span>Inicio: {{ $milestone->start_date->format('d/m/Y') }}</span>
                                    @endif
                                    <span>Objetivo: {{ $milestone->target_date->format('d/m/Y') }}</span>
                                    @if($milestone->end_date)
                                        <span>Fin: {{ $milestone->end_date->format('d/m/Y') }}</span>
                                    @endif
                                    @if($milestone->responsible)
                                        <span>Responsable: {{ $milestone->responsible->name }}</span>
                                    @endif
                                </div>
                                @if($milestone->progress_percentage > 0)
                                    <div class="mt-2">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-teal-600 h-2 rounded-full transition-all" style="width: {{ $milestone->progress_percentage }}%"></div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">{{ $milestone->progress_percentage }}% completado</p>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4 text-right">
                                <a href="{{ route('plans.milestones.show', [$plan, $milestone]) }}" 
                                   class="text-teal-600 hover:text-teal-800 text-xs font-medium">
                                    Ver detalles →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-xl shadow-md border border-gray-200">
                <p class="text-sm text-gray-500">No hay hitos en el roadmap.</p>
            </div>
        @endif
    </div>

    {{-- Vista: Timeline --}}
    <div x-show="viewMode === 'timeline'" x-transition style="display: none;">
        @if($milestones->count() > 0)
            <div class="relative">
                @php
                    $groupedByMonth = $milestones->filter(fn($m) => $m->target_date)->groupBy(function($m) {
                        return $m->target_date->format('Y-m');
                    });
                @endphp
                @foreach($groupedByMonth as $month => $monthMilestones)
                    <div class="mb-8">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex-shrink-0 w-1 h-12 bg-gradient-to-b from-teal-500 to-cyan-500 rounded-full"></div>
                            <h3 class="text-lg font-bold text-gray-900">
                                {{ Carbon::createFromFormat('Y-m', $month)->locale('es')->translatedFormat('F Y') }}
                            </h3>
                        </div>
                        <div class="ml-6 space-y-3">
                            @foreach($monthMilestones as $milestone)
                                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 {{ match($milestone->status) {
                                    'completed' => 'border-green-500',
                                    'in_progress' => 'border-blue-500',
                                    'delayed' => 'border-orange-500',
                                    'cancelled' => 'border-gray-400',
                                    default => 'border-gray-300'
                                } }} hover:shadow-lg transition-shadow">
                                    <div class="flex items-start justify-between">
                                <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <h4 class="text-sm font-bold text-gray-900">{{ $milestone->name }}</h4>
                                                <span class="px-2 py-0.5 {{ match($milestone->status) {
                                                    'completed' => 'bg-green-100 text-green-800',
                                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                                    'delayed' => 'bg-orange-100 text-orange-800',
                                                    'cancelled' => 'bg-gray-100 text-gray-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                } }} text-[10px] font-medium rounded-full">
                                                    {{ $milestone->status_label }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                {{ optional($milestone->target_date)->format('d/m/Y') }}
                                                @if($milestone->responsible)
                                                    • {{ $milestone->responsible->name }}
                                                @endif
                                            </p>
                                        </div>
                                        @if($milestone->progress_percentage > 0)
                                            <div class="ml-4 text-right">
                                                <p class="text-xs font-bold text-gray-900">{{ $milestone->progress_percentage }}%</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-xl shadow-md border border-gray-200">
                <p class="text-sm text-gray-500">No hay hitos en el roadmap.</p>
            </div>
        @endif
    </div>

    {{-- Próximos hitos y tareas --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-gradient-to-br from-teal-500 to-cyan-500 text-white flex items-center justify-center text-xs font-bold">🚀</span>
                    Hitos próximos (60 días)
                </h3>
                <span class="text-xs text-gray-500">{{ $upcomingMilestones->count() }} hitos</span>
            </div>
            <div class="space-y-3 max-h-72 overflow-y-auto pr-1">
                @forelse($upcomingMilestones as $milestone)
                    <div class="p-3 rounded-xl border border-gray-200 bg-gradient-to-br from-gray-50 to-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $milestone->name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ optional($milestone->target_date)->format('d/m/Y') ?? 'Sin fecha' }}
                                    @if($milestone->responsible)
                                        • {{ $milestone->responsible->name }}
                                    @endif
                                </p>
                            </div>
                            <span class="px-2 py-0.5 {{ match($milestone->status) {
                                'completed' => 'bg-green-100 text-green-800',
                                'in_progress' => 'bg-blue-100 text-blue-800',
                                'delayed' => 'bg-orange-100 text-orange-800',
                                default => 'bg-gray-100 text-gray-800'
                            } }} text-[10px] font-medium rounded-full">
                                {{ $milestone->status_label }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-6">No hay hitos planificados en los próximos 60 días.</p>
                @endforelse
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-gradient-to-br from-orange-500 to-red-500 text-white flex items-center justify-center text-xs font-bold">🗓️</span>
                    Tareas críticas (30 días)
                </h3>
                <span class="text-xs text-gray-500">{{ $upcomingTasks->count() }} tareas</span>
            </div>
            <div class="space-y-3 max-h-72 overflow-y-auto pr-1">
                @forelse($upcomingTasks as $task)
                    <div class="p-3 rounded-xl border border-gray-200 bg-gradient-to-br from-gray-50 to-white">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">{{ $task->title }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ optional($task->due_date)->format('d/m/Y') ?? 'Sin fecha' }}
                                    @if($task->assignedUser)
                                        • {{ $task->assignedUser->name }}
                                    @endif
                                </p>
                                @if($task->milestone)
                                    <p class="text-[10px] text-gray-400 mt-1">Hito: {{ $task->milestone->name }}</p>
                                @endif
                            </div>
                            @if($task->isOverdue())
                                <span class="px-2 py-0.5 bg-red-100 text-red-800 text-[10px] font-bold rounded-full">⚠️ Vencida</span>
                            @else
                                <span class="px-2 py-0.5 bg-orange-100 text-orange-800 text-[10px] font-bold rounded-full">Pendiente</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-6">No hay tareas críticas próximas.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Swimlane por responsable --}}
    <div class="mt-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-gradient-to-br from-purple-500 to-indigo-500 text-white flex items-center justify-center text-xs font-bold">👥</span>
                Flujo por responsable
            </h3>
            <span class="text-xs text-gray-500">Distribución de tareas activas por responsable</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @forelse($swimlaneData as $lane)
                <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $lane['user']->name ?? 'Sin asignar' }}</p>
                            <p class="text-xs text-gray-500">{{ $lane['tasks']->count() }} tareas</p>
                        </div>
                        <span class="px-2 py-0.5 bg-purple-100 text-purple-800 text-[10px] font-bold rounded-full">{{ $lane['progress'] }}% completado</span>
                    </div>
                    <div class="space-y-2 max-h-40 overflow-y-auto pr-1">
                        @foreach($lane['tasks']->take(4) as $task)
                            <div class="flex items-center justify-between text-xs text-gray-600">
                                <span class="font-semibold">{{ Str::limit($task->title, 28) }}</span>
                                <span class="text-gray-400">{{ optional($task->due_date)->format('d/m') ?? '-' }}</span>
                            </div>
                        @endforeach
                        @if($lane['tasks']->count() > 4)
                            <p class="text-[10px] text-gray-400">+{{ $lane['tasks']->count() - 4 }} tareas más</p>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">No hay tareas registradas para mostrar el swimlane.</p>
            @endforelse
        </div>
    </div>

    {{-- Alertas y dependencias --}}
    <div class="mt-8 bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-orange-500 text-white flex items-center justify-center text-lg">⚠️</div>
            <div>
                <h3 class="text-sm font-bold text-gray-900">Alertas y dependencias críticas</h3>
                <p class="text-xs text-gray-500">Monitorea retrasos y tareas bloqueadas</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 border border-orange-200">
                <h4 class="text-xs font-bold text-orange-800 uppercase tracking-wide mb-2">Hitos retrasados</h4>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @forelse($dependencyAlerts['delayedMilestones'] as $milestone)
                        <div class="p-2 bg-white/70 rounded-lg border border-orange-200">
                            <p class="text-sm font-semibold text-orange-900">{{ $milestone->name }}</p>
                            <p class="text-[11px] text-orange-700">
                                Objetivo {{ optional($milestone->target_date)->format('d/m/Y') ?? 'Sin fecha' }}
                                @if($milestone->responsible)
                                    • {{ $milestone->responsible->name }}
                                @endif
                            </p>
                        </div>
                    @empty
                        <p class="text-xs text-orange-700">Sin retrasos registrados</p>
                    @endforelse
                </div>
            </div>
            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 border border-red-200">
                <h4 class="text-xs font-bold text-red-800 uppercase tracking-wide mb-2">Tareas vencidas</h4>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @forelse($dependencyAlerts['overdueTasks'] as $task)
                        <div class="p-2 bg-white/70 rounded-lg border border-red-200">
                            <p class="text-sm font-semibold text-red-900">{{ $task->title }}</p>
                            <p class="text-[11px] text-red-700">
                                Vencida el {{ optional($task->due_date)->format('d/m/Y') ?? 'N/A' }}
                                @if($task->assignedUser)
                                    • {{ $task->assignedUser->name }}
                                @endif
                            </p>
                        </div>
                    @empty
                        <p class="text-xs text-red-700">No hay tareas vencidas</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
