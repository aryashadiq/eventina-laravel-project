@props(['logoUrl' => url('/')])

<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-base-100 dark:bg-base-100">
    <div>
        <a href="{{ $logoUrl }}">
            <x-ApplicationLogo class="w-20 h-20 fill-current text-gray-500" />
        </a>
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-base-100 overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
</div>
