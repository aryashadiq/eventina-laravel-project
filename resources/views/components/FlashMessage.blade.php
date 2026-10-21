@props(['message' => '', 'type' => 'info'])

@php
    $alertClasses = [
        'success' => 'bg-green-100 border-green-500 text-green-700',
        'error' => 'bg-red-100 border-red-500 text-red-700',
        'info' => 'bg-yellow-100 border-yellow-400 text-yellow-800',
    ];
    $classes = $alertClasses[$type] ?? $alertClasses['info'];
@endphp

@if($message)
    <div class="max-w-7xl mx-auto my-3 sm:px-6 lg:px-8 rounded-md p-4 border-l-4 {{ $classes }} border-r-4">
        <div class="flex">
            <div class="py-1">
                {{-- Icon can be added here if desired --}}
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium">{{ $message }}</p>
            </div>
        </div>
    </div>
@endif
