@php
    $user = auth()->user();
@endphp

<div class="relative" x-data="{ open: false }">
    <!-- User Menu Button -->
    <button @click="open = !open" 
            @click.away="open = false"
            class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 transition-colors">
        <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-orange-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
            {{ $user->initials() }}
        </div>
        <div class="hidden md:block text-left">
            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
            <p class="text-xs text-gray-500">
                @if($user->isDirector())
                    Director
                @elseif($user->isManager())
                    Manager
                @elseif($user->isTecnico())
                    Técnico
                @else
                    Visualización
                @endif
            </p>
        </div>
        <svg class="w-4 h-4 text-gray-500 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.away="open = false"
         class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50"
         style="display: none;">
        
        <!-- User Info -->
        <div class="px-4 py-3 border-b border-gray-200">
            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
            <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
        </div>

        <!-- Menu Items -->
        <div class="py-2">
            <a href="{{ route('profile.edit') }}" 
               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Mi Perfil
            </a>
            
            <a href="{{ route('appearance.edit') }}" 
               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                </svg>
                Apariencia
            </a>
        </div>

        <div class="border-t border-gray-200 py-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="flex items-center gap-3 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</div>
