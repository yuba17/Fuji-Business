<aside x-show="sidebarOpen || window.innerWidth >= 1024"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="-translate-x-full"
       x-transition:enter-end="translate-x-0"
       x-transition:leave="transition ease-in duration-300"
       x-transition:leave-start="translate-x-0"
       x-transition:leave-end="-translate-x-full"
       class="fixed lg:static inset-y-0 left-0 z-50 lg:z-auto lg:flex lg:flex-shrink-0 hidden lg:flex">
    <div class="flex flex-col w-64 bg-white border-r border-gray-200 h-full">
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 px-4 bg-gradient-to-r from-red-600 to-orange-600">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <h1 class="text-xl font-bold text-white">Strategos</h1>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-4 space-y-2 overflow-y-auto">
            @include('partials.navigation')
        </nav>

        <!-- User Info (Compact) -->
        <div class="p-4 border-t border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-orange-500 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                    {{ auth()->user()->initials() }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>
    </div>
</aside>
