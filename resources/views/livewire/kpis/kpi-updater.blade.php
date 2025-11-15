<div>
    <button 
        @click="$wire.openModal()" 
        class="px-4 py-2 text-sm font-semibold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700 transition-all"
    >
        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
        Actualizar Valor
    </button>
    
    <x-ui.modal wire:model="showModal" title="Actualizar KPI: {{ $kpi->name }}">
        <form wire:submit.prevent="updateValue" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Valor Actual
                </label>
                <input 
                    type="number" 
                    step="0.01"
                    wire:model="newValue" 
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all"
                    required
                >
                <p class="text-xs text-gray-500 mt-1">Unidad: {{ $kpi->unit }}</p>
                @error('newValue')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Notas (opcional)
                </label>
                <textarea 
                    wire:model="notes" 
                    rows="3"
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all resize-none"
                    placeholder="Agrega comentarios sobre este valor..."
                ></textarea>
                @error('notes')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="bg-gray-50 rounded-lg p-3 text-sm">
                <div class="flex justify-between mb-1">
                    <span class="text-gray-600">Valor anterior:</span>
                    <span class="font-semibold text-gray-900">{{ number_format($kpi->current_value, 2) }} {{ $kpi->unit }}</span>
                </div>
                <div class="flex justify-between mb-1">
                    <span class="text-gray-600">Meta:</span>
                    <span class="font-semibold text-gray-900">{{ number_format($kpi->target_value, 2) }} {{ $kpi->unit }}</span>
                </div>
                @if($kpi->percentage !== null)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Cumplimiento actual:</span>
                        <span class="font-bold text-{{ $kpi->status_color }}-600">{{ number_format($kpi->percentage, 1) }}%</span>
                    </div>
                @endif
            </div>
            
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <button 
                    type="button"
                    @click="$wire.closeModal()" 
                    class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border border-gray-200"
                >
                    Cancelar
                </button>
                <button 
                    type="submit"
                    class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700"
                >
                    Guardar
                </button>
            </div>
        </form>
    </x-ui.modal>
    
    @if(session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg text-white text-sm font-medium transition-all duration-300 z-50 bg-green-500">
            {{ session('success') }}
        </div>
    @endif
</div>
