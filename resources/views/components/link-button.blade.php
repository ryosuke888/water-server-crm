@props([
    'color' => 'blue',
    'href' => '#',
])

@php
    $colors = [
        'blue' => 'text-white bg-blue-500 hover:bg-blue-700',
        'emerald' => 'text-white bg-emerald-600 hover:bg-emerald-700',
        'gray' => 'text-white bg-gray-600 hover:bg-gray-700',
        'white' => 'border border-gray-200 text-gray-700 bg-white hover:bg-gray-50',
    ];
    $colorClass = $colors[$color] ?? $colors['primary'];
@endphp

<a href="{{ $href }}"
    {{ $attributes->merge([
        'class' => "inline-flex items-center px-5 py-2 rounded-lg text-sm font-medium {$colorClass}"
    ]) }}>
    {{ $slot }}
</a>
