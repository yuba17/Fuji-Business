<div>
    <!-- Filtros y búsqueda -->
    <x-ui.card class="mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="search" 
                       wire:model.live.debounce.300ms="search"
                       placeholder="Buscar etiquetas..."
                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all">
            </div>
            <div class="w-full sm:w-auto">
                <select wire:model.live="category" class="px-4 py-2 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all">
                    <option value="">Todas las categorías</option>
                    <option value="domain">Dominio</option>
                    <option value="priority">Prioridad</option>
                    <option value="status">Estado</option>
                    <option value="type">Tipo</option>
                </select>
            </div>
            <button wire:click="openCreateModal" 
                    class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-pink-600 to-rose-600 text-white rounded-xl hover:from-pink-700 hover:to-rose-700 transition-all shadow-lg hover:shadow-xl">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva Etiqueta
            </button>
        </div>
    </x-ui.card>

    <!-- Lista de etiquetas -->
    @if($tags->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($tags as $tag)
                <x-ui.card class="hover:shadow-xl transition-shadow">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="w-4 h-4 rounded-full flex-shrink-0" style="background-color: {{ $tag->color ?? '#6366F1' }}"></div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900">{{ $tag->name }}</h3>
                                @if($tag->category)
                                    <p class="text-xs text-gray-500 mt-1">{{ ucfirst($tag->category) }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button wire:click="openEditModal({{ $tag->id }})" 
                                    class="p-2 text-gray-400 hover:text-pink-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button wire:click="deleteTag({{ $tag->id }})" 
                                    onclick="return confirm('¿Estás seguro de eliminar esta etiqueta?')"
                                    class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                        <div class="text-xs text-gray-500">
                            Usado {{ $tag->usage_count }} vez(es)
                        </div>
                        @if($tag->is_predefined)
                            <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">Predefinida</span>
                        @endif
                    </div>
                </x-ui.card>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $tags->links() }}
        </div>
    @else
        <x-ui.card>
            <div class="text-center py-12 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <p class="text-sm mb-4">No hay etiquetas registradas aún.</p>
                <button wire:click="openCreateModal" 
                        class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-pink-600 to-rose-600 text-white rounded-xl hover:from-pink-700 hover:to-rose-700 transition-all">
                    Crear Primera Etiqueta
                </button>
            </div>
        </x-ui.card>
    @endif

    <!-- Modal para crear/editar etiqueta -->
    <x-ui.modal name="showCreateModal" maxWidth="md">
        <x-slot:title>
            {{ $editingTag ? 'Editar Etiqueta' : 'Nueva Etiqueta' }}
        </x-slot:title>
        <x-slot:content>
            <form wire:submit.prevent="saveTag" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                    <input type="text" 
                           wire:model="name"
                           required
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                    <div class="flex items-center gap-3">
                        <input type="color" 
                               wire:model="color"
                               class="w-16 h-12 border-2 border-gray-300 rounded-lg cursor-pointer">
                        <input type="text" 
                               wire:model="color"
                               placeholder="#6366F1"
                               class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                    <select wire:model="tagCategory" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all">
                        <option value="">Sin categoría</option>
                        <option value="domain">Dominio</option>
                        <option value="priority">Prioridad</option>
                        <option value="status">Estado</option>
                        <option value="type">Tipo</option>
                    </select>
                </div>
            </form>
        </x-slot:content>
        <x-slot:footer>
            <button wire:click="$set('showCreateModal', false)" 
                    class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border border-gray-200 transition-all">
                Cancelar
            </button>
            <button wire:click="saveTag" 
                    class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-pink-600 to-rose-600 text-white rounded-xl hover:from-pink-700 hover:to-rose-700 transition-all">
                {{ $editingTag ? 'Guardar Cambios' : 'Crear Etiqueta' }}
            </button>
        </x-slot:footer>
    </x-ui.modal>
</div>
