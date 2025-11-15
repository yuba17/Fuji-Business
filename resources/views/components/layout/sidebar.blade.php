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
                <button @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)" 
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

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-2 overflow-y-auto">
            @include('partials.navigation')
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
