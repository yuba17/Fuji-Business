<div>
    <!-- Filtros -->
    <div class="mb-6">
        <x-ui.card variant="compact">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Plan</label>
                    <select wire:model.live="planId" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        <option value="">Todos los planes</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Área</label>
                    <select wire:model.live="areaId" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        <option value="">Todas las áreas</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </x-ui.card>
    </div>

    <!-- Tablero Kanban -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" 
         x-data="kanbanBoard()"
         @task-dropped.window="handleDrop($event.detail)">
        
        @foreach($columns as $status => $column)
            @php
                $bgColors = [
                    'gray' => 'bg-gray-50',
                    'blue' => 'bg-blue-50',
                    'yellow' => 'bg-yellow-50',
                    'green' => 'bg-green-50',
                ];
                $borderColors = [
                    'gray' => 'border-gray-200',
                    'blue' => 'border-blue-200',
                    'yellow' => 'border-yellow-200',
                    'green' => 'border-green-200',
                ];
                $bgClass = $bgColors[$column['color']] ?? 'bg-gray-50';
                $borderClass = $borderColors[$column['color']] ?? 'border-gray-200';
            @endphp
            <div class="flex flex-col">
                <!-- Header de la columna -->
                <div class="{{ $bgClass }} border {{ $borderClass }} rounded-t-lg p-4">
                    <h3 class="font-semibold text-gray-900">
                        {{ $column['label'] }}
                        <span class="ml-2 text-sm font-normal text-gray-500">
                            ({{ $tasks[$status]->count() }})
                        </span>
                    </h3>
                </div>
                
                <!-- Tareas de la columna -->
                <div 
                    class="flex-1 bg-gray-50 border-x border-b {{ $borderClass }} rounded-b-lg p-4 min-h-[400px] space-y-3"
                    data-status="{{ $status }}"
                    x-on:dragover.prevent="event.dataTransfer.dropEffect = 'move'"
                    x-on:dragenter.prevent="event.currentTarget.classList.add('bg-opacity-50')"
                    x-on:dragleave.prevent="event.currentTarget.classList.remove('bg-opacity-50')"
                    x-on:drop.prevent="handleColumnDrop($event, '{{ $status }}'); event.currentTarget.classList.remove('bg-opacity-50')">
                    
                    @forelse($tasks[$status] as $task)
                        <div 
                            draggable="true"
                            data-task-id="{{ $task->id }}"
                            data-task-order="{{ $task->order ?? 0 }}"
                            x-on:dragstart="handleDragStart($event, {{ $task->id }}, '{{ $status }}')"
                            x-on:dragend="handleDragEnd($event)"
                            class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 cursor-move hover:shadow-md transition-shadow"
                            x-data="{ showActions: false }"
                            @mouseenter="showActions = true"
                            @mouseleave="showActions = false">
                            
                            <!-- Prioridad -->
                            <div class="flex items-start justify-between mb-2">
                                <x-ui.badge 
                                    variant="{{ $task->priority === 'critical' ? 'error' : ($task->priority === 'high' ? 'warning' : ($task->priority === 'medium' ? 'info' : 'success')) }}"
                                    size="sm">
                                    {{ $task->priority_label }}
                                </x-ui.badge>
                                
                                @if($task->isOverdue())
                                    <span class="text-red-500 text-xs">⚠️ Vencida</span>
                                @endif
                            </div>
                            
                            <!-- Título -->
                            <h4 class="font-medium text-gray-900 mb-2 line-clamp-2">
                                <a href="{{ route('tasks.show', $task) }}" class="hover:text-red-600">
                                    {{ $task->title }}
                                </a>
                            </h4>
                            
                            <!-- Subtareas -->
                            @if($task->subtasks && $task->subtasks->count() > 0)
                                <div class="mb-2 flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                    <span>{{ $task->subtasks->count() }} subtarea{{ $task->subtasks->count() > 1 ? 's' : '' }}</span>
                                    @php
                                        $completedSubtasks = $task->subtasks->where('status', 'done')->count();
                                        $totalSubtasks = $task->subtasks->count();
                                    @endphp
                                    @if($totalSubtasks > 0)
                                        <span class="text-gray-400">•</span>
                                        <span class="{{ $completedSubtasks === $totalSubtasks ? 'text-green-600' : 'text-gray-600' }}">
                                            {{ $completedSubtasks }}/{{ $totalSubtasks }} completadas
                                        </span>
                                    @endif
                                </div>
                            @endif
                            
                            <!-- Información adicional -->
                            <div class="space-y-1 text-xs text-gray-500">
                                @if($task->plan)
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        {{ $task->plan->name }}
                                    </div>
                                @endif
                                
                                @if($task->assignedUser)
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ $task->assignedUser->name }}
                                    </div>
                                @endif
                                
                                @if($task->due_date)
                                    <div class="flex items-center gap-1 {{ $task->isOverdue() ? 'text-red-600 font-medium' : '' }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $task->due_date->format('d/m/Y') }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Acciones (hover) -->
                            <div x-show="showActions" class="mt-3 flex gap-2 pt-3 border-t border-gray-100">
                                <a href="{{ route('tasks.show', $task) }}" class="text-xs text-gray-600 hover:text-red-600">
                                    Ver
                                </a>
                                @can('update', $task)
                                    <a href="{{ route('tasks.edit', $task) }}" class="text-xs text-gray-600 hover:text-red-600">
                                        Editar
                                    </a>
                                @endcan
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400 text-sm">
                            No hay tareas
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
function kanbanBoard() {
    return {
        draggedTaskId: null,
        draggedFromStatus: null,
        
        handleDragStart(event, taskId, status) {
            this.draggedTaskId = taskId;
            this.draggedFromStatus = status;
            event.dataTransfer.effectAllowed = 'move';
            event.currentTarget.style.opacity = '0.5';
        },
        
        handleDragEnd(event) {
            event.currentTarget.style.opacity = '1';
        },
        
        handleColumnDrop(event, newStatus) {
            if (!this.draggedTaskId) return;
            
            event.preventDefault();
            const taskId = this.draggedTaskId;
            const oldStatus = this.draggedFromStatus;
            
            // Obtener el índice de inserción basado en la posición del drop
            const dropZone = event.currentTarget;
            const tasks = Array.from(dropZone.querySelectorAll('[data-task-id]'));
            
            // Encontrar la tarea después de la cual insertar
            let insertAfterIndex = -1;
            for (let i = 0; i < tasks.length; i++) {
                const rect = tasks[i].getBoundingClientRect();
                if (event.clientY < rect.top + rect.height / 2) {
                    insertAfterIndex = i - 1;
                    break;
                }
            }
            
            // Si no se encontró posición, insertar al final
            if (insertAfterIndex === -1) {
                insertAfterIndex = tasks.length - 1;
            }
            
            // Calcular el nuevo orden basado en la posición
            let newOrder;
            if (tasks.length === 0) {
                newOrder = 1;
            } else if (insertAfterIndex < 0) {
                // Insertar al principio
                const firstTaskOrder = parseInt(tasks[0].getAttribute('data-task-order')) || 1;
                newOrder = Math.max(1, firstTaskOrder - 1);
            } else if (insertAfterIndex >= tasks.length - 1) {
                // Insertar al final
                const lastTask = tasks[tasks.length - 1];
                const lastTaskOrder = parseInt(lastTask.getAttribute('data-task-order')) || tasks.length;
                newOrder = lastTaskOrder + 1;
            } else {
                // Insertar entre dos tareas
                const beforeTask = tasks[insertAfterIndex];
                const afterTask = tasks[insertAfterIndex + 1];
                const beforeOrder = parseInt(beforeTask.getAttribute('data-task-order')) || (insertAfterIndex + 1);
                const afterOrder = parseInt(afterTask.getAttribute('data-task-order')) || (insertAfterIndex + 2);
                newOrder = Math.floor((beforeOrder + afterOrder) / 2);
                // Si el orden es el mismo, incrementar
                if (newOrder === beforeOrder) {
                    newOrder = beforeOrder + 1;
                }
            }
            
            // Actualizar estado y orden via Livewire
            @this.updateTaskStatus(taskId, newStatus, newOrder);
            
            this.draggedTaskId = null;
            this.draggedFromStatus = null;
        },
        
        handleDrop(event, taskId, newStatus, newOrder) {
            // Método para manejar drops específicos con orden
            @this.updateTaskOrder(taskId, newStatus, newOrder, this.draggedFromStatus);
            this.draggedTaskId = null;
            this.draggedFromStatus = null;
        }
    }
}
</script>
@endpush
