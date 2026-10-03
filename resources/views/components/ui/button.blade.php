@props(['variant' => 'primary', 'href' => null, 'type' => 'button'])

@php
    $classes = 'inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600 disabled:cursor-not-allowed disabled:opacity-50 '.match ($variant) {
        'secondary' => 'border border-gray-300 bg-white text-gray-800 hover:bg-gray-50',
        'ghost' => 'text-brand-700 hover:bg-brand-50',
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
        default => 'bg-brand-600 text-white hover:bg-brand-700',
    };
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
