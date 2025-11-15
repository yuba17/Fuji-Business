<div>
    <!-- Header -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-red-500 via-orange-500 to-red-600 rounded-2xl shadow-lg p-8 text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 via-transparent to-transparent"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold mb-2">Personalizar Dashboard</h1>
                        <p class="text-red-50 text-sm">Configura los widgets y el diseño de tu dashboard</p>
                    </div>
                    <button wire:click="saveDashboard" 
                            class="px-4 py-2 text-sm font-semibold bg-white/20 backdrop-blur-sm text-white rounded-lg hover:bg-white/30 transition-all shadow-sm">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuración del Dashboard -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Configuración General</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Dashboard</label>
                <input type="text" wire:model="dashboardName" 
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <input type="text" wire:model="dashboardDescription" 
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
            </div>
        </div>
    </div>

    <!-- Widgets Disponibles -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900">Widgets Disponibles</h2>
            <button @click="$wire.showAddWidgetModal = true" 
                    class="px-4 py-2 text-sm font-semibold bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-lg hover:from-blue-700 hover:to-cyan-700 transition-all">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Agregar Widget
            </button>
        </div>

        <!-- Grid de Widgets -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" 
             x-data="{ draggedWidget: null }"
             @dragover.prevent
             @drop.prevent="if(draggedWidget) { $wire.updateWidgetOrder([...$wire.currentWidgets.map(w => w.id).filter(id => id !== draggedWidget), draggedWidget]); draggedWidget = null; }">
            @foreach($currentWidgets as $index => $widget)
                @php
                    $widgetInfo = $availableWidgets[$widget['type']] ?? ['name' => $widget['title'], 'icon' => 'fa-cube'];
                @endphp
                <div class="bg-gray-50 rounded-lg p-4 border-2 border-gray-200 hover:border-blue-300 transition-all cursor-move"
                     draggable="true"
                     @dragstart="draggedWidget = {{ $widget['id'] }}"
                     @dragend="draggedWidget = null">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center text-white">
                                <i class="fa-solid {{ $widgetInfo['icon'] }}"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $widget['title'] }}</h3>
                                <p class="text-xs text-gray-500">{{ $widget['width'] }}x{{ $widget['height'] }} columnas</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button wire:click="toggleWidgetVisibility({{ $widget['id'] }})" 
                                    class="p-2 text-gray-400 hover:text-blue-600 transition-colors"
                                    title="{{ $widget['is_visible'] ? 'Ocultar' : 'Mostrar' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($widget['is_visible'])
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    @endif
                                </svg>
                            </button>
                            <button wire:click="removeWidget({{ $widget['id'] }})" 
                                    wire:confirm="¿Estás seguro de eliminar este widget?"
                                    class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        Orden: {{ $widget['order'] }}
                    </div>
                </div>
            @endforeach

            @if(count($currentWidgets) === 0)
                <div class="col-span-full text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">No hay widgets agregados</p>
                    <button @click="$wire.showAddWidgetModal = true" 
                            class="px-4 py-2 text-sm font-semibold bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-lg hover:from-blue-700 hover:to-cyan-700 transition-all">
                        Agregar Primer Widget
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal para agregar widget -->
    <div x-show="$wire.showAddWidgetModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-gray-900/40 flex items-center justify-center p-4"
         style="display: none;"
         @click.self="$wire.showAddWidgetModal = false">
        <div x-show="$wire.showAddWidgetModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                <h3 class="text-xl font-bold text-white">Agregar Widget</h3>
                <button @click="$wire.showAddWidgetModal = false" class="text-white/80 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($availableWidgets as $type => $widget)
                        <button wire:click="addWidget('{{ $type }}')" 
                                class="p-4 bg-gray-50 rounded-lg border-2 border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all text-left">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center text-white">
                                    <i class="fa-solid {{ $widget['icon'] }}"></i>
                                </div>
                                <h4 class="font-semibold text-gray-900">{{ $widget['name'] }}</h4>
                            </div>
                            <p class="text-xs text-gray-500">Tamaño: {{ $widget['default_width'] }}x{{ $widget['default_height'] }} columnas</p>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
