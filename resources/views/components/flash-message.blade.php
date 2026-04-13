@props([
    'message' => null,
    'type' => 'success',
    ])

@php
    $styles = [
        'success' => 'bg-green-50 border-green-200 text-green-800',
        'error' => 'bg-red-50 border-red-200 text-red-800'
    ];
    $style = $styles[$type];
@endphp


@if ($message)
    <div class="mb-4 rounded-lg border px-4 py-3 text-sm {{ $style }}">
       {{ $message }}
    </div>
@endif
