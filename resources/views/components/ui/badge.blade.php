@php
    $classes = match($variant) {
        'success' => 'px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full',
        'warning' => 'px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full',
        'error' => 'px-3 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-full',
        'info' => 'px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full',
        'purple' => 'px-3 py-1 bg-purple-100 text-purple-800 text-xs font-bold rounded-full',
        default => 'px-3 py-1 bg-gray-100 text-gray-800 text-xs font-bold rounded-full',
    };
    
    $icons = match($variant) {
        'success' => '✅',
        'warning' => '⚠️',
        'error' => '❌',
        'info' => 'ℹ️',
        'purple' => '🔒',
        default => '',
    };
    
    $displayIcon = $icon ?? $icons;
@endphp

<span class="{{ $classes }}">
    @if($displayIcon)
        {{ $displayIcon }} 
    @endif
    {{ $slot }}
</span>