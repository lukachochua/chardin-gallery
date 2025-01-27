@props(['active'])

@php
    $classes =
        'inline-flex items-center px-1 py-2 text font-light tracking-wide transition-smooth ' .
        ($active ?? false
            ? 'text-black border-b-2 border-black'
            : 'text-gray-600 hover:text-black border-b-2 border-transparent hover:border-gray-300');
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
