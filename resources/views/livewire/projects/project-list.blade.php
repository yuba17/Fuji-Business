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
                    <select wire:model.live="client_id" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <option value="">Todos los clientes</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
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
                <div class="w-full sm:w-auto">
                    <select wire:model.live="sector_economico" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <option value="">Todos los sectores</option>
                        @foreach($sectors as $sector)
                            <option value="{{ $sector }}">{{ $sector }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Proyectos -->
    @if($projects->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($projects as $project)
                <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition-shadow cursor-pointer border-l-4 border-green-500"
                     onclick="window.location.href='{{ route('projects.show', $project) }}'">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $project->name }}</h3>
                            <p class="text-xs text-gray-500">{{ $project->client->name ?? 'Sin cliente' }}</p>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-lg
                            {{ $project->status === 'activo' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $project->status === 'completado' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $project->status === 'cancelado' ? 'bg-red-100 text-red-800' : '' }}
                            {{ in_array($project->status, ['prospecto', 'en_negociacion', 'en_pausa']) ? 'bg-yellow-100 text-yellow-800' : '' }}">
                            {{ $project->status_label }}
                        </span>
                    </div>
                    
                    @if($project->description)
                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $project->description }}</p>
                    @endif
                    
                    <div class="flex items-center justify-between text-xs text-gray-500 pt-4 border-t border-gray-200">
                        <div class="flex items-center gap-4">
                            @if($project->presupuesto)
                                <span class="font-semibold">{{ number_format($project->presupuesto, 2) }} {{ $project->moneda }}</span>
                            @endif
                            @if($project->manager)
                                <span>{{ $project->manager->name }}</span>
                            @endif
                        </div>
                        <a href="{{ route('projects.show', $project) }}" 
                           class="text-xs font-medium text-red-600 hover:text-red-700">
                            Ver →
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
                <p class="text-sm mb-4">No hay proyectos que coincidan con los filtros</p>
            </div>
        </div>
    @endif
</div>
