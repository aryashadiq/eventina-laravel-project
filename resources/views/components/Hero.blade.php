<div {{ $attributes->merge(['class' => 'hero min-h-fit flex items-center justify-center']) }}>
    <div class="bg-cover bg-center w-11/12 rounded-lg shadow-xl h-40 overflow-hidden">
        <img src="{{ $img }}" alt="{{ $alt ?? 'Banner' }}" class="w-full h-full object-cover rounded-lg" />
    </div>
</div>
