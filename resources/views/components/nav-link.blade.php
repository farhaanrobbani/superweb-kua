@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-teal-300 text-sm font-medium leading-5 text-white focus:outline-none focus:border-teal-200 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-teal-100 hover:text-white hover:border-teal-400 focus:outline-none focus:text-white focus:border-teal-400 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
