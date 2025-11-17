<div>
    <x-ui.card>
        <form wire:submit.prevent="performSearch" class="space-y-6">
            <!-- Campo de búsqueda -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" 
                       wire:model.live.debounce.300ms="query"
                       placeholder="Escribe tu búsqueda..."
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
            </div>

            <!-- Tipos de contenido -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar en</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="selectedTypes" value="plans" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        <span class="text-sm text-gray-700">Planes</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="selectedTypes" value="tasks" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        <span class="text-sm text-gray-700">Tareas</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="selectedTypes" value="risks" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        <span class="text-sm text-gray-700">Riesgos</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="selectedTypes" value="decisions" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        <span class="text-sm text-gray-700">Decisiones</span>
                    </label>
                </div>
            </div>

            <!-- Filtros avanzados -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select wire:model="status" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                        <option value="">Todos los estados</option>
                        <option value="draft">Borrador</option>
                        <option value="in_progress">En Progreso</option>
                        <option value="approved">Aprobado</option>
                        <option value="completed">Completado</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                    <select wire:model="category" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                        <option value="">Todas las categorías</option>
                        <option value="critico">Crítico</option>
                        <option value="alto">Alto</option>
                        <option value="medio">Medio</option>
                        <option value="bajo">Bajo</option>
                    </select>
                </div>
            </div>

            <!-- Etiquetas -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por etiquetas</label>
                @livewire('tags.tag-filter', ['selectedTags' => $selectedTags])
            </div>

            <!-- Botones de acción -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <button type="button" 
                        wire:click="clearFilters"
                        class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border border-gray-200 transition-all">
                    Limpiar Filtros
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-teal-600 to-cyan-600 text-white rounded-xl hover:from-teal-700 hover:to-cyan-700 transition-all shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Buscar
                </button>
            </div>
        </form>
    </x-ui.card>

    <!-- Resultados -->
    @if(!empty($results))
        <div class="mt-6 space-y-6">
            @if(isset($results['plans']) && $results['plans']->count() > 0)
                <x-ui.card>
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Planes ({{ $results['plans']->count() }})</h2>
                    <div class="space-y-3">
                        @foreach($results['plans'] as $plan)
                            <a href="{{ route('plans.show', $plan) }}" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border-l-4 border-red-500">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $plan->name }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($plan->description, 150) }}</p>
                                @if($plan->tags->count() > 0)
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        @foreach($plan->tags->take(3) as $tag)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $tag->color }}20; color: {{ $tag->color }}">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif

            @if(isset($results['tasks']) && $results['tasks']->count() > 0)
                <x-ui.card>
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Tareas ({{ $results['tasks']->count() }})</h2>
                    <div class="space-y-3">
                        @foreach($results['tasks'] as $task)
                            <a href="{{ route('tasks.show', $task) }}" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border-l-4 border-green-500">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $task->title }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($task->description, 150) }}</p>
                                @if($task->tags->count() > 0)
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        @foreach($task->tags->take(3) as $tag)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $tag->color }}20; color: {{ $tag->color }}">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif

            @if(isset($results['risks']) && $results['risks']->count() > 0)
                <x-ui.card>
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Riesgos ({{ $results['risks']->count() }})</h2>
                    <div class="space-y-3">
                        @foreach($results['risks'] as $risk)
                            <a href="{{ route('risks.show', $risk) }}" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border-l-4 border-orange-500">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $risk->name }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($risk->description, 150) }}</p>
                                @if($risk->tags->count() > 0)
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        @foreach($risk->tags->take(3) as $tag)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $tag->color }}20; color: {{ $tag->color }}">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif

            @if(isset($results['decisions']) && $results['decisions']->count() > 0)
                <x-ui.card>
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Decisiones ({{ $results['decisions']->count() }})</h2>
                    <div class="space-y-3">
                        @foreach($results['decisions'] as $decision)
                            <a href="{{ route('decisions.show', $decision) }}" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border-l-4 border-purple-500">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $decision->title }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($decision->description, 150) }}</p>
                                @if($decision->tags->count() > 0)
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        @foreach($decision->tags->take(3) as $tag)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $tag->color }}20; color: {{ $tag->color }}">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif
        </div>
    @elseif($query)
        <x-ui.card class="mt-6">
            <div class="text-center py-12 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <p class="text-sm mb-4">No se encontraron resultados para "{{ $query }}".</p>
                <button wire:click="clearFilters" class="text-sm font-medium text-teal-600 hover:text-teal-700">
                    Limpiar búsqueda →
                </button>
            </div>
        </x-ui.card>
    @endif
</div>
