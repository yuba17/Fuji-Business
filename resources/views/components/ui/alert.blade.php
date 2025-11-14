@php
    $classes = match($variant) {
        'success' => 'p-4 bg-green-50 border-l-4 border-green-400 rounded-r-lg',
        'warning' => 'mb-4 p-3 bg-amber-50 border-l-3 border-amber-400 rounded-r-lg',
        'error' => 'p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg',
        'info' => 'p-4 bg-blue-50 border-l-4 border-blue-400 rounded-r-lg',
        default => 'p-4 bg-gray-50 border-l-4 border-gray-400 rounded-r-lg',
    };
    
    $iconClasses = match($variant) {
        'success' => 'w-5 h-5 text-green-600',
        'warning' => 'w-4 h-4 text-amber-600',
        'error' => 'w-5 h-5 text-red-600',
        'info' => 'w-5 h-5 text-blue-600',
        default => 'w-5 h-5 text-gray-600',
    };
    
    $textClasses = match($variant) {
        'success' => 'text-sm text-green-800',
        'warning' => 'text-xs text-amber-800',
        'error' => 'text-sm text-red-800',
        'info' => 'text-sm text-blue-800',
        default => 'text-sm text-gray-800',
    };
    
    $icons = match($variant) {
        'success' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>',
        'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        'error' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>',
        'info' => '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>',
        default => '',
    };
@endphp

<div class="{{ $classes }}">
    <div class="flex items-start gap-2">
        @if($icons)
            <svg class="{{ $iconClasses }} flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                {!! $icons !!}
            </svg>
        @endif
        <div class="flex-1">
            @if($title)
                <h4 class="font-semibold {{ $textClasses }} mb-1">{{ $title }}</h4>
            @endif
            <p class="{{ $textClasses }}">{{ $slot }}</p>
        </div>
    </div>
</div>