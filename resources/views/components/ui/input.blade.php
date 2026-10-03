@props(['name', 'label' => null, 'type' => 'text', 'id' => null])

@php
    $id ??= $name;
    $error = $errors->first($name);
@endphp

<div>
    @if ($label)
        <label for="{{ $id }}" class="mb-1 block text-sm font-medium text-gray-700">{{ $label }}</label>
    @endif

    <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" value="{{ old($name, $attributes->get('value')) }}"
           @if ($error) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
           {{ $attributes->except('value')->class([
               'block w-full rounded-lg border bg-white px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2',
               'border-red-500 focus:ring-red-500' => $error,
               'border-gray-300 focus:border-brand-500 focus:ring-brand-500' => ! $error,
           ]) }}>

    @if ($error)
        <p id="{{ $id }}-error" class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
