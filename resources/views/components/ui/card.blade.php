@php
    $baseClasses = match($variant) {
        'gradient' => 'bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-md p-4 text-white',
        'compact' => 'bg-white rounded-lg shadow-md p-3',
        default => 'bg-white rounded-2xl shadow-lg p-6',
    };
    
    $borderClasses = $withBorder && $variant === 'default' 
        ? match($borderColor) {
            'red' => 'border-l-4 border-red-500',
            'orange' => 'border-l-4 border-orange-500',
            'blue' => 'border-l-4 border-blue-500',
            'purple' => 'border-l-4 border-purple-500',
            'green' => 'border-l-4 border-green-500',
            default => 'border-l-4 border-red-500',
        }
        : '';
@endphp

<div class="{{ $baseClasses }} {{ $borderClasses }}">
    {{ $slot }}
</div>