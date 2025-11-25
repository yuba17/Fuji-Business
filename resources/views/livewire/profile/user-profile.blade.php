<div x-data="{ activeTab: '{{ $activeTab }}' }" 
     x-on:avatar-updated.window="$wire.loadUser()"
     class="space-y-6">
    <!-- Header del Perfil -->
    <div class="bg-gradient-to-r from-red-500 via-orange-500 to-red-600 rounded-2xl shadow-lg p-8 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-white/10 via-transparent to-transparent"></div>
        <div class="relative z-10">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 rounded-full bg-white/20 backdrop-blur-sm border-4 border-white/30 flex items-center justify-center text-3xl font-bold overflow-hidden">
                        @if($user->avatar_url)
                            <img src="{{ $user->avatar_url }}?v={{ time() }}" alt="{{ $user->name }}" class="w-full h-full object-cover" wire:key="avatar-header-{{ $user->id }}">
                        @else
                            {{ $user->initials() }}
                        @endif
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold mb-2">{{ $user->name }}</h1>
                        <p class="text-red-50 text-sm">{{ $user->email }}</p>
                        @if($user->internalRole)
                            <p class="text-red-100 text-xs mt-1">{{ $user->internalRole->name }} - {{ $user->area->name ?? 'Sin área' }}</p>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2">
                        <p class="text-xs font-medium text-red-100 uppercase tracking-wide">Perfil completado</p>
                        <p class="text-3xl font-bold mt-1">{{ $user->profile_completion_percent ?? 0 }}%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs de Navegación -->
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-2 border border-gray-200">
        <nav class="flex flex-wrap gap-2">
            <button @click="activeTab = 'info'; $wire.setActiveTab('info')" 
                    :class="activeTab === 'info' ? 'bg-gradient-to-r from-red-600 to-orange-600 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Información
            </button>
            <button @click="activeTab = 'competencies'; $wire.setActiveTab('competencies')" 
                    :class="activeTab === 'competencies' ? 'bg-gradient-to-r from-purple-600 to-indigo-500 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                Competencias
            </button>
            <button @click="activeTab = 'certifications'; $wire.setActiveTab('certifications')" 
                    :class="activeTab === 'certifications' ? 'bg-gradient-to-r from-yellow-600 to-amber-500 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
                Certificaciones
            </button>
            <button @click="activeTab = 'goals'; $wire.setActiveTab('goals')" 
                    :class="activeTab === 'goals' ? 'bg-gradient-to-r from-green-600 to-emerald-500 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
                Objetivos
            </button>
            <button @click="activeTab = 'availability'; $wire.setActiveTab('availability')" 
                    :class="activeTab === 'availability' ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Disponibilidad
            </button>
            <button @click="activeTab = 'evaluations'; $wire.setActiveTab('evaluations')" 
                    :class="activeTab === 'evaluations' ? 'bg-gradient-to-r from-indigo-600 to-purple-500 text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="whitespace-nowrap py-2.5 px-4 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Evaluaciones
            </button>
        </nav>
    </div>

    <!-- Mensaje de éxito -->
    @if(session('message'))
    <div class="bg-green-50 border-l-4 border-green-400 rounded-r-lg p-4">
        <p class="text-sm text-green-800">{{ session('message') }}</p>
    </div>
    @endif

    <!-- Tab: Información Básica -->
    <div x-show="activeTab === 'info'" class="mt-6 space-y-6">
        <!-- Información Básica -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-500">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Información Básica</h2>
            <form wire:submit.prevent="saveBasicInfo" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre completo</label>
                        <input type="text" wire:model="name" 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                        <input type="text" wire:model="phone" 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        @error('phone') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de incorporación</label>
                        <input type="date" wire:model="joined_at" 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        @error('joined_at') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Última evaluación</label>
                        <input type="date" wire:model="last_evaluation_at" 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                        @error('last_evaluation_at') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Biografía / Descripción</label>
                    <textarea wire:model="bio" rows="4" 
                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all resize-none"
                              placeholder="Escribe una breve descripción sobre ti..."></textarea>
                    @error('bio') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center justify-end">
                    <button type="submit" 
                            class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl hover:from-red-700 hover:to-orange-700 transition-all shadow-lg hover:shadow-xl">
                        Guardar información
                    </button>
                </div>
            </form>
        </div>

        <!-- Avatar -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Foto de Perfil</h2>
            @livewire('profile.avatar-uploader', ['userId' => $user->id], key('avatar-' . $user->id))
        </div>
    </div>

    <!-- Tab: Competencias -->
    <div x-show="activeTab === 'competencies'" class="mt-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Mis Competencias</h2>
            <p class="text-sm text-gray-600 mb-6">Gestiona tus competencias y niveles. Puedes auto-evaluarte o esperar la evaluación de tu manager.</p>
            
            @if(count($competencies) > 0)
            <div class="space-y-4">
                @foreach($competencies as $index => $competency)
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $competency['name'] }}</h3>
                            <p class="text-xs text-gray-500">{{ $competency['category'] }}</p>
                        </div>
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs font-bold rounded-full">
                            Nivel actual: {{ $competency['current_level'] }}/5
                        </span>
                    </div>
                    @if($competency['target_level'] > $competency['current_level'])
                    <div class="mt-2">
                        <p class="text-xs text-gray-600">Objetivo: Nivel {{ $competency['target_level'] }}</p>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                            <div class="bg-gradient-to-r from-purple-500 to-indigo-500 h-2 rounded-full" 
                                 style="width: {{ ($competency['current_level'] / $competency['target_level']) * 100 }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-10">
                <p class="text-sm text-gray-500">No tienes competencias asignadas todavía.</p>
                <p class="text-xs text-gray-400 mt-2">Contacta con tu manager para que te asigne competencias relevantes.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Tab: Certificaciones -->
    <div x-show="activeTab === 'certifications'" class="mt-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Mis Certificaciones</h2>
                    <p class="text-sm text-gray-600 mt-1">Gestiona tus certificaciones obtenidas y planificadas.</p>
                </div>
                <button wire:click="openCertificationModal()" 
                        class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-yellow-600 to-amber-500 text-white rounded-xl hover:from-yellow-700 hover:to-amber-600 transition-all shadow-lg hover:shadow-xl flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Añadir Certificación
                </button>
            </div>
            
            @if(count($certifications) > 0)
            <div class="space-y-4">
                @foreach($certifications as $cert)
                <div class="bg-white rounded-xl p-4 border-2 border-gray-200 hover:border-yellow-300 hover:shadow-lg transition-all">
                    <div class="flex items-start gap-4">
                        {{-- Logo a la izquierda --}}
                        <div class="flex-shrink-0">
                            @if($cert['image_url'] ?? null)
                                <img src="{{ $cert['image_url'] }}" 
                                     alt="{{ $cert['name'] }}" 
                                     class="w-20 h-20 object-contain rounded-lg border-2 border-gray-200 bg-white p-2 shadow-sm">
                            @else
                                <div class="w-20 h-20 rounded-lg bg-gradient-to-br from-yellow-100 to-amber-100 border-2 border-yellow-200 flex items-center justify-center">
                                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        {{-- Datos a la derecha --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $cert['name'] }}</h3>
                                    @if($cert['provider'] ?? null)
                                        <p class="text-sm text-gray-600 mb-2">{{ $cert['provider'] }}</p>
                                    @endif
                                    <span class="inline-block px-3 py-1 text-xs font-bold rounded-full 
                                        {{ $cert['status'] === 'active' ? 'bg-green-100 text-green-700' : 
                                           ($cert['status'] === 'planned' ? 'bg-blue-100 text-blue-700' : 
                                           ($cert['status'] === 'in_progress' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $cert['status'])) }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 ml-4">
                                    <button wire:click="openCertificationModal({{ $cert['id'] }})" 
                                            class="p-2 text-gray-600 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors"
                                            title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button wire:click="deleteCertification({{ $cert['id'] }})" 
                                            wire:confirm="¿Estás seguro de eliminar esta certificación?"
                                            class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-3">
                                @if($cert['obtained_at'])
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="text-xs text-gray-600">
                                            <span class="font-medium">Obtenida:</span> {{ \Carbon\Carbon::parse($cert['obtained_at'])->format('d/m/Y') }}
                                        </p>
                                    </div>
                                @endif
                                @if($cert['expires_at'])
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-xs {{ \Carbon\Carbon::parse($cert['expires_at'])->isPast() ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                            <span class="font-medium">Expira:</span> {{ \Carbon\Carbon::parse($cert['expires_at'])->format('d/m/Y') }}
                                        </p>
                                    </div>
                                @endif
                                @if($cert['planned_date'])
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        <p class="text-xs text-blue-600 font-semibold">
                                            <span class="font-medium">Planificada:</span> {{ \Carbon\Carbon::parse($cert['planned_date'])->format('d/m/Y') }}
                                        </p>
                                    </div>
                                @endif
                                @if($cert['certificate_number'] ?? null)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                        </svg>
                                        <p class="text-xs text-gray-600">
                                            <span class="font-medium">Número:</span> {{ $cert['certificate_number'] }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-10">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
                <p class="text-sm text-gray-500 mb-2">No tienes certificaciones registradas todavía.</p>
                <p class="text-xs text-gray-400">Haz clic en "Añadir Certificación" para comenzar.</p>
            </div>
            @endif
        </div>
    </div>
    
    {{-- Modal: Añadir/Editar Certificación --}}
    <div x-show="$wire.showCertificationModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-gray-900/40 flex items-center justify-center p-4"
         style="display: none;"
         @click.self="$wire.closeCertificationModal()">
        <div x-show="$wire.showCertificationModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto"
             @click.stop>
            <div class="bg-gradient-to-r from-yellow-600 to-amber-500 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                <h3 class="text-xl font-bold text-white">
                    {{ $userCertificationId ? 'Editar Certificación' : 'Seleccionar Certificación' }}
                </h3>
                <button wire:click="closeCertificationModal" class="text-white/80 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form wire:submit.prevent="saveCertification" class="p-6 space-y-4">
                @if(!$userCertificationId)
                    {{-- Selección de certificación (solo al crear) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Selecciona una Certificación *</label>
                        @if($availableCertifications->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-96 overflow-y-auto p-2 border-2 border-gray-200 rounded-lg">
                                @foreach($availableCertifications as $cert)
                                    <label class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer hover:border-yellow-400 hover:bg-yellow-50 transition-all {{ $userCertificationCertificationId == $cert->id ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200' }}">
                                        <input type="radio" 
                                               wire:model="userCertificationCertificationId" 
                                               value="{{ $cert->id }}"
                                               class="w-4 h-4 text-yellow-600 border-gray-300 focus:ring-yellow-500">
                                        <div class="flex items-center gap-3 flex-1 min-w-0">
                                            @if($cert->image_url)
                                                <img src="{{ $cert->image_url }}" 
                                                     alt="{{ $cert->name }}" 
                                                     class="w-12 h-12 object-contain rounded border border-gray-200 bg-white p-1 flex-shrink-0">
                                            @else
                                                <div class="w-12 h-12 rounded bg-gradient-to-br from-yellow-100 to-amber-100 border border-yellow-200 flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $cert->name }}</p>
                                                @if($cert->provider)
                                                    <p class="text-xs text-gray-500 truncate">{{ $cert->provider }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="p-4 bg-gray-50 border-2 border-gray-200 rounded-lg text-center">
                                <p class="text-sm text-gray-500">No hay certificaciones disponibles para añadir.</p>
                                <p class="text-xs text-gray-400 mt-1">Ya tienes todas las certificaciones disponibles vinculadas.</p>
                            </div>
                        @endif
                        @error('userCertificationCertificationId') <span class="text-xs text-red-600 mt-2 block">{{ $message }}</span> @enderror
                    </div>
                @else
                    {{-- Mostrar certificación actual al editar --}}
                    <div class="p-4 bg-gray-50 border-2 border-gray-200 rounded-lg">
                        <p class="text-xs font-medium text-gray-500 mb-2">Certificación (no se puede cambiar al editar)</p>
                        @if($currentCertification)
                            <div class="flex items-center gap-3">
                                @if($currentCertification->image_url ?? null)
                                    <img src="{{ $currentCertification->image_url }}" 
                                         alt="{{ $currentCertification->name }}" 
                                         class="w-12 h-12 object-contain rounded border border-gray-200 bg-white p-1">
                                @endif
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $currentCertification->name }}</p>
                                    @if($currentCertification->provider ?? null)
                                        <p class="text-xs text-gray-500">{{ $currentCertification->provider }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                     @endif
                     
                     @if($userCertificationCertificationId || $userCertificationId)
                     <div>
                         <label class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                         <select wire:model="userCertificationStatus" 
                                 class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all">
                             <option value="active">Activa</option>
                             <option value="in_progress">En Progreso</option>
                             <option value="planned">Planificada</option>
                             <option value="expired">Vencida</option>
                             <option value="revoked">Revocada</option>
                         </select>
                         @error('userCertificationStatus') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                     </div>
                     
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                         <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Obtención</label>
                             <input type="date" wire:model="userCertificationObtainedAt" 
                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all">
                             @error('userCertificationObtainedAt') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                         </div>
                         <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Vencimiento</label>
                             <input type="date" wire:model="userCertificationExpiresAt" 
                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all">
                             @error('userCertificationExpiresAt') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                         </div>
                         <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Planificada</label>
                             <input type="date" wire:model="userCertificationPlannedDate" 
                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all">
                             @error('userCertificationPlannedDate') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                         </div>
                         <div>
                             <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad (0-5)</label>
                             <input type="number" min="0" max="5" wire:model="userCertificationPriority" 
                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all">
                             @error('userCertificationPriority') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                         </div>
                     </div>
                     
                     <div>
                         <label class="block text-sm font-medium text-gray-700 mb-1">Número de Certificado</label>
                         <input type="text" wire:model="userCertificationCertificateNumber" 
                                placeholder="Ej: CERT-12345"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all">
                         @error('userCertificationCertificateNumber') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                     </div>
                     
                     <div>
                         <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                         <textarea wire:model="userCertificationNotes" rows="3"
                                   placeholder="Notas adicionales sobre esta certificación..."
                                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all resize-none"></textarea>
                         @error('userCertificationNotes') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                     </div>
                     @endif
                     
                     @if(session('error'))
                         <div class="p-3 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                             <p class="text-sm text-red-800">{{ session('error') }}</p>
                         </div>
                     @endif
                     
                     <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                         <button type="button" wire:click="closeCertificationModal" 
                                 class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border border-gray-200 transition-colors">
                             Cancelar
                         </button>
                         @if($userCertificationCertificationId || $userCertificationId)
                         <button type="submit" 
                                 class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-yellow-600 to-amber-500 text-white rounded-xl hover:from-yellow-700 hover:to-amber-600 transition-all shadow-lg hover:shadow-xl">
                             {{ $userCertificationId ? 'Actualizar' : 'Añadir' }} Certificación
                         </button>
                         @endif
                     </div>
                 </form>
             </div>
         </div>
     </div>

    <!-- Tab: Objetivos Profesionales -->
    <div x-show="activeTab === 'goals'" class="mt-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Objetivos Profesionales</h2>
            <form wire:submit.prevent="saveCareerGoals" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">¿Cuáles son tus objetivos profesionales a corto y largo plazo?</label>
                    <textarea wire:model="career_goals" rows="8" 
                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all resize-none"
                              placeholder="Describe tus objetivos profesionales, áreas en las que quieres crecer, certificaciones que quieres obtener, roles a los que aspiras, etc..."></textarea>
                    @error('career_goals') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-500 mt-2">Esta información ayudará a tu manager a planificar tu desarrollo profesional.</p>
                </div>
                <div class="flex items-center justify-end">
                    <button type="submit" 
                            class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all shadow-lg hover:shadow-xl">
                        Guardar objetivos
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tab: Disponibilidad -->
    <div x-show="activeTab === 'availability'" class="mt-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Disponibilidad y Preferencias</h2>
            <form wire:submit.prevent="saveAvailability" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Disponibilidad (%)</label>
                    <div class="flex items-center gap-4">
                        <input type="range" wire:model="availability_percent" min="0" max="100" step="5"
                               class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                        <span class="text-2xl font-bold text-blue-600 w-16 text-right">{{ $availability_percent }}%</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Indica tu disponibilidad actual para nuevos proyectos o tareas.</p>
                    @error('availability_percent') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center justify-end">
                    <button type="submit" 
                            class="px-5 py-2.5 text-sm font-bold bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all shadow-lg hover:shadow-xl">
                        Guardar disponibilidad
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tab: Evaluaciones -->
    <div x-show="activeTab === 'evaluations'" class="mt-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-indigo-500">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Mis Evaluaciones</h2>
            <p class="text-sm text-gray-600 mb-6">Historial de evaluaciones periódicas y seguimiento de objetivos profesionales.</p>
            @livewire('profile.user-evaluations', ['userId' => $user->id], key('evaluations-' . $user->id))
        </div>
    </div>
</div>
