@props(['href' => '#', 'active' => false])

@php
    $base = 'w-full flex items-start pl-3 pr-4 py-2 border-l-4 text-base font-medium focus:outline-none transition duration-150 ease-in-out';
    if ($active) {
        $classes = $base . ' border-indigo-400 text-indigo-700 bg-indigo-50';
    } else {
        $classes = $base . ' border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300';
    }
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
