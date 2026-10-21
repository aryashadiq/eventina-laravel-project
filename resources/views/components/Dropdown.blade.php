@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white dark:bg-gray-700'])

{{--
    Simple Blade dropdown placeholder: for full interactivity use Alpine.js or Livewire.
    Usage:
    <x-dropdown>
        <x-slot name="trigger">...trigger markup...</x-slot>
        <x-slot name="content">...links...</x-slot>
    </x-dropdown>
--}}

<div class="relative inline-block text-left">
    <div>
        {{-- Trigger slot --}}
        @isset($trigger)
            {{ $trigger }}
        @endisset
    </div>

    <div class="absolute z-50 mt-2 rounded-md shadow-lg {{ $align === 'left' ? 'left-0' : 'right-0' }} {{ $width === '48' ? 'w-48' : '' }}">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}"> 
            @isset($content)
                {{ $content }}
            @endisset
        </div>
    </div>
</div>
