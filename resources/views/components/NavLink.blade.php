@props(['href' => '#', 'active' => false])

@php
    $base = 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none';
    if ($active) {
        $classes = $base . ' border-indigo-400 text-gray-900';
    } else {
        $classes = $base . ' border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';
    }
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
