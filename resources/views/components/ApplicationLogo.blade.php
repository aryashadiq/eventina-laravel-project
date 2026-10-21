@props(['primary' => false, 'class' => ''])

@php
    // Choose a logo; fall back to `img/logo.svg` if specific files are not present
    $logoBlue = asset('img/logo.svg');
    $logoPurple = asset('img/logo.svg');
    $src = $primary ? $logoBlue : $logoPurple;
@endphp

<img src="{{ $src }}" {{ $attributes->merge(['class' => $class]) }} alt="Eventina logo" />
