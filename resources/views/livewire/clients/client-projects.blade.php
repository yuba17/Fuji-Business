<div>
    <!-- Filtros -->
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-md p-4">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="search" 
                           wire:model.live.debounce.300ms="search"
                           placeholder="Buscar proyectos..." 
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                </div>
                <div class="w-full sm:w-auto">
                    <select wire:model.live="status" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <option value="">Todos los estados</option>
                        <option value="prospecto">Prospecto</option>
                        <option value="en_negociacion">En Negociación</option>
                        <option value="activo">Activo</option>
                        <option value="en_pausa">En Pausa</option>
                        <option value="completado">Completado</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Proyectos -->
    @if($projects->count() > 0)
        <div class="space-y-4">
            @foreach($projects as $project)
                <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition-shadow border-l-4 border-green-500">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-1">
                                <a href="{{ route('projects.show', $project) }}" class="hover:text-red-600 transition-colors">
                                    {{ $project->name }}
                                </a>
                            </h3>
                            @if($project->description)
                                <p class="text-sm text-gray-600 mt-2">{{ \Illuminate\Support\Str::limit($project->description, 150) }}</p>
                            @endif
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-lg
                            {{ $project->status === 'activo' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $project->status === 'completado' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $project->status === 'cancelado' ? 'bg-red-100 text-red-800' : '' }}
                            {{ in_array($project->status, ['prospecto', 'en_negociacion', 'en_pausa']) ? 'bg-yellow-100 text-yellow-800' : '' }}">
                            {{ $project->status_label }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600 mb-4">
                        @if($project->presupuesto)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-semibold">{{ number_format($project->presupuesto, 2) }} {{ $project->moneda }}</span>
                            </div>
                        @endif
                        @if($project->manager)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>{{ $project->manager->name }}</span>
                            </div>
                        @endif
                        @if($project->fecha_inicio)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $project->fecha_inicio->format('d/m/Y') }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                        <a href="{{ route('projects.show', $project) }}" 
                           class="px-4 py-2 text-sm font-semibold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-lg hover:from-red-700 hover:to-orange-700 transition-all">
                            Ver Detalles
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Paginación -->
        <div class="mt-6">
            {{ $projects->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow-md p-12">
            <div class="text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <p class="text-sm mb-4">No hay proyectos para este cliente</p>
            </div>
        </div>
    @endif
</div>
