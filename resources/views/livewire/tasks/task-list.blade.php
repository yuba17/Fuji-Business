@php
    use Illuminate\Support\Str;
@endphp

<div>
    <!-- Filtros y Búsqueda -->
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
            <div class="flex flex-wrap items-end gap-4">
                <!-- Búsqueda -->
                <div class="flex-1 min-w-[250px]">
                    <label class="block text-xs font-medium text-gray-700 uppercase tracking-wide mb-2">Búsqueda</label>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Buscar por título o descripción..."
                        class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                </div>

                <!-- Filtro Plan -->
                <div class="w-full sm:w-auto min-w-[180px]">
                    <label class="block text-xs font-medium text-gray-700 uppercase tracking-wide mb-2">Plan</label>
                    <select wire:model.live="planId" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <option value="">Todos los planes</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro Estado -->
                <div class="w-full sm:w-auto min-w-[150px]">
                    <label class="block text-xs font-medium text-gray-700 uppercase tracking-wide mb-2">Estado</label>
                    <select wire:model.live="status" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <option value="">Todos</option>
                        <option value="todo">📋 Por Hacer</option>
                        <option value="in_progress">🔄 En Progreso</option>
                        <option value="blocked">🚫 Bloqueada</option>
                        <option value="review">👀 En Revisión</option>
                        <option value="done">✅ Completada</option>
                    </select>
                </div>

                <!-- Filtro Prioridad -->
                <div class="w-full sm:w-auto min-w-[150px]">
                    <label class="block text-xs font-medium text-gray-700 uppercase tracking-wide mb-2">Prioridad</label>
                    <select wire:model.live="priority" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <option value="">Todas</option>
                        <option value="low">🟢 Baja</option>
                        <option value="medium">🟡 Media</option>
                        <option value="high">🟠 Alta</option>
                        <option value="critical">🔴 Crítica</option>
                    </select>
                </div>

                <!-- Filtro Asignado -->
                <div class="w-full sm:w-auto min-w-[180px]">
                    <label class="block text-xs font-medium text-gray-700 uppercase tracking-wide mb-2">Asignado a</label>
                    <select wire:model.live="assignedTo" class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <option value="">Todos</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Botón Limpiar Filtros -->
                @if($search || $planId || $areaId || $status || $priority || $assignedTo)
                <div class="w-full sm:w-auto">
                    <button 
                        wire:click="clearFilters"
                        class="px-4 py-2.5 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border border-gray-200 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Limpiar
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Lista de Tareas -->
    @if($tasks->count() > 0)
        <!-- Tabla de Tareas -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left">
                                <button 
                                    wire:click="sortBy('title')"
                                    class="text-xs font-bold text-gray-700 uppercase tracking-wide flex items-center gap-2 hover:text-red-600 transition-colors">
                                    Título
                                    @if($sortBy === 'title')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th class="px-4 py-3 text-left">
                                <button 
                                    wire:click="sortBy('status')"
                                    class="text-xs font-bold text-gray-700 uppercase tracking-wide flex items-center gap-2 hover:text-red-600 transition-colors">
                                    Estado
                                    @if($sortBy === 'status')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th class="px-4 py-3 text-left">
                                <button 
                                    wire:click="sortBy('priority')"
                                    class="text-xs font-bold text-gray-700 uppercase tracking-wide flex items-center gap-2 hover:text-red-600 transition-colors">
                                    Prioridad
                                    @if($sortBy === 'priority')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Plan</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Asignado</th>
                            <th class="px-4 py-3 text-left">
                                <button 
                                    wire:click="sortBy('due_date')"
                                    class="text-xs font-bold text-gray-700 uppercase tracking-wide flex items-center gap-2 hover:text-red-600 transition-colors">
                                    Fecha Vencimiento
                                    @if($sortBy === 'due_date')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th class="px-4 py-3 text-left">
                                <button 
                                    wire:click="sortBy('created_at')"
                                    class="text-xs font-bold text-gray-700 uppercase tracking-wide flex items-center gap-2 hover:text-red-600 transition-colors">
                                    Creada
                                    @if($sortBy === 'created_at')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wide">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tasks as $task)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-4">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-1">
                                            <a href="{{ route('tasks.show', $task) }}" class="text-sm font-semibold text-gray-900 hover:text-red-600 transition-colors">
                                                {{ $task->title }}
                                            </a>
                                            @if($task->description)
                                                <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ Str::limit($task->description, 60) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <x-ui.badge 
                                        variant="{{ $task->status === 'done' ? 'success' : ($task->status === 'in_progress' ? 'info' : ($task->status === 'blocked' ? 'error' : 'warning')) }}">
                                        {{ $task->status_label }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-4 py-4">
                                    <x-ui.badge 
                                        variant="{{ $task->priority === 'critical' ? 'error' : ($task->priority === 'high' ? 'warning' : ($task->priority === 'medium' ? 'info' : 'success')) }}">
                                        {{ $task->priority_label }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-4 py-4">
                                    @if($task->plan)
                                        <span class="text-xs text-gray-600">{{ $task->plan->name }}</span>
                                    @else
                                        <span class="text-xs text-gray-400">Sin plan</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    @if($task->assignedUser)
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded-full bg-gradient-to-br from-red-500 to-orange-500 text-white flex items-center justify-center text-[10px] font-bold">
                                                {{ $task->assignedUser->initials() }}
                                            </span>
                                            <span class="text-xs text-gray-600">{{ $task->assignedUser->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">Sin asignar</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    @if($task->due_date)
                                        <span class="text-xs {{ $task->isOverdue() ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                            {{ $task->due_date->format('d/m/Y') }}
                                            @if($task->isOverdue())
                                                <span class="ml-1">⚠️</span>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">Sin fecha</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-xs text-gray-600">{{ $task->created_at->format('d/m/Y') }}</span>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <a href="{{ route('tasks.show', $task) }}" 
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación -->
        <div class="mt-6">
            {{ $tasks->links() }}
        </div>
    @else
        <!-- Estado Vacío -->
        <div class="bg-white rounded-xl shadow-md p-12 border border-gray-200">
            <div class="text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-900 mb-2">No se encontraron tareas</p>
                <p class="text-xs text-gray-500 mb-4">
                    @if($search || $planId || $status || $priority || $assignedTo)
                        Intenta ajustar los filtros para ver más resultados
                    @else
                        Crea tu primera tarea para comenzar
                    @endif
                </p>
                @if($search || $planId || $status || $priority || $assignedTo)
                    <button 
                        wire:click="clearFilters"
                        class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-orange-600 rounded-xl hover:from-red-700 hover:to-orange-700 transition-all">
                        Limpiar Filtros
                    </button>
                @else
                    @can('create', \App\Models\Task::class)
                        <a href="{{ route('tasks.create') }}" 
                           class="inline-block px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-orange-600 rounded-xl hover:from-red-700 hover:to-orange-700 transition-all">
                            Crear Primera Tarea
                        </a>
                    @endcan
                @endif
            </div>
        </div>
    @endif
</div>
