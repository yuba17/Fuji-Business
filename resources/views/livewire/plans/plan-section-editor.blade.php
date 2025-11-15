<div>
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-xl font-bold text-gray-900">Secciones del Plan</h3>
        @can('update', $plan)
            <button 
                wire:click="openModal"
                class="px-4 py-2 text-sm font-semibold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700 transition-all shadow-lg hover:shadow-xl flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva Sección
            </button>
        @endcan
    </div>

    @if($sections->count() > 0)
        <div class="space-y-4">
            @foreach($sections as $section)
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500 hover:shadow-lg transition-all">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h4 class="text-lg font-bold text-gray-900">{{ $section->title }}</h4>
                                @if($section->is_required)
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-full">Requerida</span>
                                @endif
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">{{ ucfirst($section->type) }}</span>
                            </div>
                            @if($section->content)
                                <div class="text-sm text-gray-600 mt-2 line-clamp-3">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($section->content), 200) }}
                                </div>
                            @endif
                        </div>
                        @can('update', $plan)
                            <div class="flex items-center gap-2 ml-4">
                                <button 
                                    wire:click="openModal({{ $section->id }})"
                                    class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button 
                                    wire:click="delete({{ $section->id }})"
                                    wire:confirm="¿Estás seguro de eliminar esta sección?"
                                    class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        @endcan
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-8 text-center border-2 border-dashed border-gray-300">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-gray-500 text-sm mb-4">No hay secciones en este plan</p>
            @can('update', $plan)
                <button 
                    wire:click="openModal"
                    class="px-4 py-2 text-sm font-semibold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700 transition-all">
                    Crear Primera Sección
                </button>
            @endcan
        </div>
    @endif

    <!-- Modal -->
    @if($showModal)
    <div 
        class="fixed inset-0 z-50 bg-gray-900/40 flex items-center justify-center p-4"
        wire:click.self="closeModal">
        
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-6 py-4 flex items-center justify-between rounded-t-2xl sticky top-0 z-10">
                <h3 class="text-xl font-bold text-white">
                    {{ $sectionId ? 'Editar Sección' : 'Nueva Sección' }}
                </h3>
                <button 
                    wire:click="closeModal"
                    class="text-white/80 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Contenido -->
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Título <span class="text-red-600">*</span>
                        </label>
                        <input 
                            type="text"
                            wire:model="title"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Contenido
                        </label>
                        <select 
                            wire:model="type"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                            <option value="text">Texto</option>
                            <option value="html">HTML</option>
                            <option value="markdown">Markdown</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Contenido
                        </label>
                        <textarea 
                            wire:model="content"
                            rows="8"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all resize-none"></textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input 
                            type="checkbox"
                            wire:model="isRequired"
                            id="isRequired"
                            class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <label for="isRequired" class="ml-2 text-sm font-medium text-gray-700">
                            Sección requerida
                        </label>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl flex items-center justify-end gap-3">
                <button 
                    wire:click="closeModal"
                    type="button"
                    class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border border-gray-200">
                    Cancelar
                </button>
                <button 
                    wire:click="save"
                    type="button"
                    class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl hover:from-purple-700 hover:to-blue-700 transition-all">
                    Guardar
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

