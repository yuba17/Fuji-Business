<div>
    <!-- Botón para abrir modal -->
    @can('update', $plan)
        <button 
            wire:click="openModal"
            class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border border-gray-200 shadow-sm hover:shadow transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Cambiar Estado
        </button>
    @endcan

    <!-- Modal -->
    @if($showModal)
    <div 
        class="fixed inset-0 z-50 bg-gray-900/40 flex items-center justify-center p-4"
        wire:click.self="closeModal"
        wire:keydown.escape="closeModal">
        
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-red-600 to-orange-600 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                <h3 class="text-xl font-bold text-white">Cambiar Estado del Plan</h3>
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
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Estado actual:</p>
                    <x-ui.badge 
                        variant="{{ $plan->status === 'approved' ? 'success' : ($plan->status === 'in_progress' ? 'info' : 'warning') }}">
                        {{ $plan->status_label }}
                    </x-ui.badge>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nuevo Estado <span class="text-red-600">*</span>
                    </label>
                    <select 
                        wire:model="newStatus"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        @foreach($availableStatuses as $status)
                            <option value="{{ $status }}">
                                {{ $statusOptions[$status] ?? $status }}
                            </option>
                        @endforeach
                    </select>
                    @error('newStatus')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Comentario (opcional)
                    </label>
                    <textarea 
                        wire:model="comment"
                        rows="3"
                        placeholder="Agrega un comentario sobre el cambio de estado..."
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all resize-none"></textarea>
                    @error('comment')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                @if(session()->has('error'))
                    <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                @endif
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
                    wire:click="updateStatus"
                    type="button"
                    class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700 transition-all">
                    Actualizar Estado
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

