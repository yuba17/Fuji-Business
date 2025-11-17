@props(['collapsed' => false])

<aside x-show="sidebarOpen || window.innerWidth >= 1024"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="-translate-x-full"
       x-transition:enter-end="translate-x-0"
       x-transition:leave="transition ease-in duration-300"
       x-transition:leave-start="translate-x-0"
       x-transition:leave-end="-translate-x-full"
       :class="sidebarCollapsed ? 'w-20' : 'w-64'"
       class="fixed lg:static inset-y-0 left-0 z-50 lg:z-auto lg:flex lg:flex-shrink-0 hidden lg:flex transition-all duration-300">
    <div class="flex flex-col bg-white border-r border-gray-200 h-full shadow-lg">
        <!-- Logo -->
        <div class="flex items-center justify-between h-16 px-4 bg-gradient-to-r from-red-600 via-orange-600 to-red-600 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-white/20 via-transparent to-transparent"></div>
            <div class="relative z-10 flex items-center justify-between w-full">
                <a href="{{ route('dashboard') }}" class="flex items-center flex-1 min-w-0">
                    <h1 x-show="!sidebarCollapsed" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="text-xl font-bold text-white whitespace-nowrap">Strategos</h1>
                </a>
                <!-- Botón de colapsar -->
                <button @click="toggleSidebarCollapse()" 
                        class="hidden lg:flex p-2 rounded-lg text-white/80 hover:bg-white/20 hover:text-white transition-all flex-shrink-0">
                    <svg x-show="!sidebarCollapsed"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 rotate-180"
                         x-transition:enter-end="opacity-100 rotate-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 rotate-0"
                         x-transition:leave-end="opacity-0 rotate-180"
                         class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                    </svg>
                    <svg x-show="sidebarCollapsed"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -rotate-180"
                         x-transition:enter-end="opacity-100 rotate-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 rotate-0"
                         x-transition:leave-end="opacity-0 -rotate-180"
                         class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>

        @php
            $viewMode = session('view_mode', 'individual');
            $user = auth()->user();
        @endphp

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-4 overflow-y-auto">
            <!-- Selector de vista: Individual / Equipo (solo cuando no está colapsado) -->
            <div class="px-2">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm mt-2"
                     x-show="!sidebarCollapsed"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-x-2"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 translate-x-0"
                     x-transition:leave-end="opacity-0 -translate-x-2">
                    <div class="flex overflow-hidden rounded-2xl bg-gray-100">
                        <!-- Individual -->
                        <a href="{{ route('view-mode.set', 'individual') }}"
                           class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 text-[11px] font-semibold transition-all
                           {{ $viewMode === 'individual' 
                                ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-inner' 
                                : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                            <svg class="w-4 h-4 {{ $viewMode === 'individual' ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12a4 4 0 100-8 4 4 0 000 8zm0 2c-3.333 0-5 1.333-5 4v1h10v-1c0-2.667-1.667-4-5-4z" />
                            </svg>
                            <span x-show="!sidebarCollapsed">Individual</span>
                        </a>
                        <!-- Equipo -->
                        <a href="{{ route('view-mode.set', 'team') }}"
                           class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 text-[11px] font-semibold transition-all border-l border-gray-200
                           {{ $viewMode === 'team' 
                                ? 'bg-gradient-to-r from-purple-600 to-blue-500 text-white shadow-inner' 
                                : 'bg-gray-100 text-gray-600 hover:bg-white' }}">
                            <svg class="w-4 h-4 {{ $viewMode === 'team' ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 14a4 4 0 10-8 0v1a4 4 0 108 0v-1zM12 7a3 3 0 110-6 3 3 0 010 6zM5 21a3 3 0 116 0H5zm8 0a3 3 0 116 0h-6z" />
                            </svg>
                            <span x-show="!sidebarCollapsed">Equipo</span>
                        </a>
                    </div>
                </div>

                @if($viewMode === 'team')
                    <!-- Opciones de equipo, con el mismo formato que el resto del menú -->
                    <div class="mt-4 space-y-1">
                        <!-- Título solo en modo expandido para que colapsado sea tan limpio como Individual -->
                        <p x-show="!sidebarCollapsed"
                           class="px-4 text-[10px] font-semibold text-gray-400 uppercase tracking-wide">
                            Equipo
                        </p>
                        <a href="{{ route('teams.my') }}" 
                           :class="sidebarCollapsed ? 'justify-center px-3' : 'px-4'"
                           class="group flex items-center gap-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('teams.my') ? 'bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-600 text-white shadow-lg shadow-purple-500/30' : 'text-gray-700 hover:bg-gradient-to-r hover:from-purple-50 hover:via-indigo-50 hover:to-purple-50 hover:text-purple-700' }}"
                           :title="sidebarCollapsed ? 'Mi equipo' : ''">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A4 4 0 017 11h10a4 4 0 011.879 6.804M12 11a4 4 0 100-8 4 4 0 000 8z" />
                            </svg>
                            <span x-show="!sidebarCollapsed" 
                                  x-transition:enter="transition ease-out duration-200"
                                  x-transition:enter-start="opacity-0"
                                  x-transition:enter-end="opacity-100"
                                  x-transition:leave="transition ease-in duration-150"
                                  x-transition:leave-start="opacity-100"
                                  x-transition:leave-end="opacity-0"
                                  class="whitespace-nowrap">Mi equipo</span>
                        </a>
                        <a href="{{ route('teams.calendar') }}" 
                           :class="sidebarCollapsed ? 'justify-center px-3' : 'px-4'"
                           class="group flex items-center gap-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('teams.calendar') ? 'bg-gradient-to-r from-blue-600 via-cyan-600 to-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:via-cyan-50 hover:to-blue-50 hover:text-blue-700' }}"
                           :title="sidebarCollapsed ? 'Calendario equipo' : ''">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5A2 2 0 003 7v10a2 2 0 002 2z"/>
                            </svg>
                            <span x-show="!sidebarCollapsed" 
                                  x-transition:enter="transition ease-out duration-200"
                                  x-transition:enter-start="opacity-0"
                                  x-transition:enter-end="opacity-100"
                                  x-transition:leave="transition ease-in duration-150"
                                  x-transition:leave-start="opacity-100"
                                  x-transition:leave-end="opacity-0"
                                  class="whitespace-nowrap">Calendario de equipo</span>
                        </a>
                        @if($user && $user->isDirector())
                            <a href="{{ route('teams.index') }}" 
                               :class="sidebarCollapsed ? 'justify-center px-3' : 'px-4'"
                               class="group flex items-center gap-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('teams.index') ? 'bg-gradient-to-r from-emerald-600 via-green-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/30' : 'text-gray-700 hover:bg-gradient-to-r hover:from-emerald-50 hover:via-green-50 hover:to-emerald-50 hover:text-emerald-700' }}"
                               :title="sidebarCollapsed ? 'Todos los equipos' : ''">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M12 11a4 4 0 110-8 4 4 0 010 8z" />
                                </svg>
                                <span x-show="!sidebarCollapsed" 
                                      x-transition:enter="transition ease-out duration-200"
                                      x-transition:enter-start="opacity-0"
                                      x-transition:enter-end="opacity-100"
                                      x-transition:leave="transition ease-in duration-150"
                                      x-transition:leave-start="opacity-100"
                                      x-transition:leave-end="opacity-0"
                                      class="whitespace-nowrap">Todos los equipos</span>
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            @if ($viewMode === 'individual')
                @include('partials.navigation')
            @endif
        </nav>

        <!-- User Info (Compact) -->
        <div class="p-3 border-t border-gray-200">
            <div class="flex items-center gap-3" :class="sidebarCollapsed ? 'justify-center' : ''">
                <div class="w-10 h-10 bg-gradient-to-br from-red-500 via-orange-500 to-red-600 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0 shadow-lg ring-2 ring-red-400/50">
                    {{ auth()->user()->initials() }}
                </div>
                <div x-show="!sidebarCollapsed" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>
    </div>
</aside>
