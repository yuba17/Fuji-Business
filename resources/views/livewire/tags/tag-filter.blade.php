<div>
    <div class="flex flex-wrap gap-2">
        @foreach($tags as $tag)
            <button type="button"
                    wire:click="toggleTag({{ $tag->id }})"
                    class="px-3 py-1.5 text-sm font-semibold rounded-full transition-all flex items-center gap-2
                           {{ in_array($tag->id, $selectedTags) 
                               ? 'shadow-lg' 
                               : 'hover:shadow-md' }}"
                    style="{{ in_array($tag->id, $selectedTags) 
                        ? 'background-color: ' . ($tag->color ?? '#6366F1') . '; color: white;' 
                        : 'background-color: ' . ($tag->color ?? '#6366F1') . '20; color: ' . ($tag->color ?? '#6366F1') . ';' }}">
                <span>{{ $tag->name }}</span>
                @if(in_array($tag->id, $selectedTags))
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                @endif
            </button>
        @endforeach
    </div>
    
    @if(count($selectedTags) > 0)
        <div class="mt-3">
            <button type="button"
                    wire:click="clearTags"
                    class="text-xs font-medium text-gray-600 hover:text-gray-900 transition-colors">
                Limpiar selección
            </button>
        </div>
    @endif

    @if($tags->count() >= 20 && !$showAll)
        <div class="mt-3">
            <button type="button"
                    wire:click="$set('showAll', true)"
                    class="text-xs font-medium text-teal-600 hover:text-teal-700 transition-colors">
                Ver todas las etiquetas ({{ $tags->count() }})
            </button>
        </div>
    @endif
</div>
