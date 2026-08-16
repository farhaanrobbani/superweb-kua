@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link flex items-center gap-3 px-4 py-2.5 rounded-md text-sm font-medium text-white bg-teal-800/70 focus:outline-none transition duration-150 ease-in-out'
            : 'nav-link flex items-center gap-3 px-4 py-2.5 rounded-md text-sm font-medium text-teal-100 hover:text-white hover:bg-teal-800/40 focus:outline-none focus:text-white focus:bg-teal-800/40 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
