@php
    $baseClasses = 'px-5 py-2.5 text-sm font-bold rounded-xl transition-all flex items-center justify-center gap-2';
    
    $variantClasses = match($variant) {
        'primary' => 'bg-gradient-to-r from-red-600 to-orange-600 text-white hover:from-red-700 hover:to-orange-700 shadow-lg hover:shadow-xl',
        'secondary' => 'px-4 py-2.5 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 border border-gray-200 shadow-sm hover:shadow',
        'gray' => 'bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200',
        default => 'bg-gradient-to-r from-red-600 to-orange-600 text-white hover:from-red-700 hover:to-orange-700 shadow-lg hover:shadow-xl',
    };
    
    $disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed' : '';
    $classes = "$baseClasses $variantClasses $disabledClasses";
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ $classes }}" @if($disabled) onclick="return false;" @endif>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" class="{{ $classes }}" @if($disabled) disabled @endif>
        {{ $slot }}
    </button>
@endif