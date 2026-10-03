{{-- Abrir: $dispatch('open-modal', 'nombre') · Cerrar: $dispatch('close-modal', 'nombre') o Esc --}}
@props(['name', 'title' => null])

<div x-data="{ open: false }"
     x-on:open-modal.window="if ($event.detail === @js($name)) open = true"
     x-on:close-modal.window="if ($event.detail === @js($name)) open = false"
     x-on:keydown.escape.window="open = false"
     x-show="open" x-cloak
     class="fixed inset-0 z-50 flex items-end justify-center p-4 sm:items-center"
     role="dialog" aria-modal="true" @if ($title) aria-labelledby="modal-{{ $name }}-titulo" @endif>
    <div class="fixed inset-0 bg-gray-900/50" x-on:click="open = false" aria-hidden="true"></div>

    <div x-trap.noscroll="open" {{ $attributes->merge(['class' => 'relative w-full max-w-lg rounded-xl bg-white p-6 shadow-xl']) }}>
        @if ($title)
            <h2 id="modal-{{ $name }}-titulo" class="mb-4 text-lg font-semibold">{{ $title }}</h2>
        @endif
        {{ $slot }}
        <button type="button" class="absolute right-3 top-3 rounded p-1 text-gray-400 hover:text-gray-600" x-on:click="open = false" aria-label="Cerrar">&times;</button>
    </div>
</div>
