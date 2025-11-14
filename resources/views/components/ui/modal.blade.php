<div x-data="{ {{ $name }}: false }" 
     x-show="{{ $name }}" 
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 bg-gray-900/40 flex items-center justify-center p-4"
     @keydown.escape.window="{{ $name }} = false">
    <div x-show="{{ $name }}"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
         @click.away="{{ $name }} = false">
        
        @if($title || $showCloseButton)
            <div class="bg-gradient-to-r from-red-600 to-orange-600 px-6 py-4 flex items-center justify-between rounded-t-2xl">
                @if($title)
                    <h3 class="text-xl font-bold text-white">{{ $title }}</h3>
                @else
                    <div></div>
                @endif
                @if($showCloseButton)
                    <button @click="{{ $name }} = false" 
                            class="text-white/80 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                @endif
            </div>
        @endif
        
        <div class="p-6">
            {{ $slot }}
        </div>
        
        @isset($footer)
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl flex items-center justify-end gap-3">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>