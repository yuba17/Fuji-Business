<div>
    <x-ui.card>
        <form wire:submit.prevent="save" class="space-y-6">
            <!-- Información básica -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Escenario *</label>
                    <input type="text" 
                           wire:model="name"
                           required
                           placeholder="Ej: Reducción de presupuesto 10%"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                    @error('name') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                    <textarea wire:model="description" 
                              rows="3"
                              placeholder="Describe el escenario y su propósito..."
                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all resize-none"></textarea>
                    @error('description') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Parámetros de simulación -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Parámetros de Simulación</h3>
                <p class="text-sm text-gray-600 mb-6">Especifica al menos un parámetro para simular el escenario.</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Cambio de Presupuesto -->
                    <div class="p-4 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl border-2 border-blue-200">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <label class="block text-sm font-bold text-gray-900">Cambio de Presupuesto</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="number" 
                                   wire:model="simulationParams.budget_change"
                                   step="0.1"
                                   placeholder="0"
                                   class="flex-1 px-3 py-2 border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <span class="text-sm font-medium text-gray-700">%</span>
                        </div>
                        <p class="text-xs text-gray-600 mt-2">Valor positivo = aumento, negativo = reducción</p>
                        @error('simulationParams.budget_change') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Cambio de Equipo -->
                    <div class="p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border-2 border-green-200">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <label class="block text-sm font-bold text-gray-900">Cambio de Equipo</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="number" 
                                   wire:model="simulationParams.team_change"
                                   placeholder="0"
                                   class="flex-1 px-3 py-2 border-2 border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                            <span class="text-sm font-medium text-gray-700">personas</span>
                        </div>
                        <p class="text-xs text-gray-600 mt-2">Valor positivo = más personas, negativo = menos</p>
                        @error('simulationParams.team_change') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Retraso en Días -->
                    <div class="p-4 bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl border-2 border-orange-200">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <label class="block text-sm font-bold text-gray-900">Retraso</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="number" 
                                   wire:model="simulationParams.delay_days"
                                   min="0"
                                   placeholder="0"
                                   class="flex-1 px-3 py-2 border-2 border-orange-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                            <span class="text-sm font-medium text-gray-700">días</span>
                        </div>
                        <p class="text-xs text-gray-600 mt-2">Días de retraso en el plan</p>
                        @error('simulationParams.delay_days') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Mensajes de error/success -->
            @if (session()->has('error'))
                <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-lg">
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            @if (session()->has('success'))
                <div class="p-4 bg-green-50 border-l-4 border-green-400 rounded-lg">
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Botones de acción -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('scenarios.index', $plan) }}" 
                   class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border border-gray-200 transition-all">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Crear y Simular Escenario
                </button>
            </div>
        </form>
    </x-ui.card>
</div>
