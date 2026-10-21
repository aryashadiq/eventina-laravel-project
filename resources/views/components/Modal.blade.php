@props(['maxWidth' => '2xl'])

@php
    $maxWidthClass = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
    ][$maxWidth] ?? 'sm:max-w-2xl';
@endphp

{{--
    Non-JS modal wrapper. For interactivity use Alpine.js or Livewire.
    Slot: default - modal content
-->
<div id="modal" class="fixed inset-0 flex overflow-y-auto px-4 py-6 sm:px-0 items-center z-50 transform transition-all">
    <div class="absolute inset-0 bg-gray-500/75 dark:bg-gray-900/75"></div>

    <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto {{ $maxWidthClass }}">
        {{ $slot }}
    </div>
</div>
