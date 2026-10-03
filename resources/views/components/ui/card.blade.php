@props(['header' => null, 'footer' => null])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm']) }}>
    @if ($header)
        <div class="border-b border-gray-200 px-4 py-3 font-semibold">{{ $header }}</div>
    @endif
    <div class="p-4">{{ $slot }}</div>
    @if ($footer)
        <div class="border-t border-gray-200 bg-gray-50 px-4 py-3">{{ $footer }}</div>
    @endif
</div>
