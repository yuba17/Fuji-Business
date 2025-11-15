<header class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm">
    <div class="flex items-center justify-between h-16 px-4">
        <!-- Mobile Menu Button -->
        <button @click="sidebarOpen = !sidebarOpen" 
                class="lg:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <!-- Breadcrumbs -->
        <div class="flex-1 ml-4 lg:ml-0 min-w-0">
            @hasSection('breadcrumbs')
                <div class="overflow-x-auto">
                    @yield('breadcrumbs')
                </div>
            @else
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-red-600">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                                <span class="truncate">Dashboard</span>
                            </a>
                        </li>
                    </ol>
                </nav>
            @endif
        </div>

        <!-- Header Actions & User Menu -->
        <div class="flex items-center gap-4">
            @hasSection('header-actions')
                @yield('header-actions')
            @endif
            
            <x-layout.user-menu />
        </div>
    </div>
</header>
