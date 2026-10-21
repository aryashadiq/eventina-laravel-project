@props(['title' => '', 'description' => '', 'icon' => ''])

<div class="flex gap-4 w-full items-center">
    <div class="bg-primary rounded-lg p-2 h-fit">
        {{-- icon can be an emoji or raw HTML passed via the attribute --}}
        {!! $icon !!}
    </div>

    <div class="flex flex-col justify-between">
        <div>
            <h1 class="font-semibold text-lg lg:text-xl">{{ $title }}</h1>
        </div>
        <div>
            <p class="text-md lg:text-lg">{{ $description }}</p>
        </div>
    </div>
</div>
