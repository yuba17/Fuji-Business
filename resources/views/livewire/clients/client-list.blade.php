<div>
    <!-- Filtros -->
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-md p-4">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="search" 
                           wire:model.live.debounce.300ms="search"
                           placeholder="Buscar clientes..." 
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
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
                <div class="w-full sm:w-auto">
                    <select wire:model.live="tamaño_empresa" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        <option value="">Todos los tamaños</option>
                        @foreach($sizes as $size)
                            <option value="{{ $size }}">{{ $size }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Clientes -->
    @if($clients->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($clients as $client)
                <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition-shadow cursor-pointer border-l-4 border-blue-500"
                     onclick="window.location.href='{{ route('clients.show', $client) }}'">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $client->name }}</h3>
                            @if($client->sector_economico)
                                <p class="text-xs text-gray-500">{{ $client->sector_economico }}</p>
                            @endif
                        </div>
                        @if($client->is_active)
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-lg">Activo</span>
                        @endif
                    </div>
                    
                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        @if($client->contacto_principal)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>{{ $client->contacto_principal }}</span>
                            </div>
                        @endif
                        @if($client->email)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $client->email }}</span>
                            </div>
                        @endif
                        @if($client->ubicacion)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>{{ $client->ubicacion }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div class="text-xs text-gray-500">
                            {{ $client->projects_count }} proyecto(s)
                        </div>
                        <a href="{{ route('clients.show', $client) }}" 
                           class="text-xs font-medium text-red-600 hover:text-red-700">
                            Ver →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Paginación -->
        <div class="mt-6">
            {{ $clients->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow-md p-12">
            <div class="text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p class="text-sm mb-4">No hay clientes que coincidan con los filtros</p>
            </div>
        </div>
    @endif
</div>
