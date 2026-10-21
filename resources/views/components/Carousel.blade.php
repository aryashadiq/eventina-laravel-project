<div id="default-carousel" class="relative w-full max-w-8xl" data-carousel="slide">
    <!-- Carousel wrapper -->
    <div class="relative h-[32rem] md:h-[38rem] overflow-hidden rounded-2xl hover:scale-105" style="box-shadow: 0 20px 50px rgba(0,0,0,0.45);">
        <!-- Shadow overlay on top of images -->
        <div class="absolute inset-0 pointer-events-none rounded-2xl" style="box-shadow: inset 0 0 60px rgb(0, 0, 0), inset 0 0 30px rgb(0, 0, 0);"></div>
         <!-- Item 1 -->
        <div class="duration-700 ease-in-out" data-carousel-item>
            <img src="{{ asset('img/photo_1.jpg') }}" class="absolute inset-0 w-full h-full object-cover" alt="Slide 1">
        </div>
        <!-- Item 2 -->
        <div class="hidden duration-700 ease-in-out" data-carousel-item>
            <a href="/events"><img src="{{ asset('img/banner_events/the_strokes.jpg') }}" class="absolute inset-0 w-full h-full object-cover" alt="Slide 2"></a>
        </div>
        <!-- Item 3 -->
        <div class="hidden duration-700 ease-in-out" data-carousel-item>
            <a href="/events"><img src="{{ asset('img/banner_events/slipknot.webp') }}" class="absolute inset-0 w-full h-full object-cover" alt="Slide 3"></a>
        </div>
         <!-- Item 4 -->
        <div class="hidden duration-700 ease-in-out" data-carousel-item>
            <a href="/events"><img src="{{ asset('img/banner_events/tsp.jpg') }}" class="absolute inset-0 w-full h-full object-cover" alt="Slide 4"></a>
        </div>
    </div>
    <!-- Slider indicators -->
    <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
        <button type="button" class="w-3 h-3 rounded-base" aria-current="true" aria-label="Slide 1" data-carousel-slide-to="0"></button>
        <button type="button" class="w-3 h-3 rounded-base" aria-current="false" aria-label="Slide 2" data-carousel-slide-to="1"></button>
        <button type="button" class="w-3 h-3 rounded-base" aria-current="false" aria-label="Slide 3" data-carousel-slide-to="2"></button>
        <button type="button" class="w-3 h-3 rounded-base" aria-current="false" aria-label="Slide 4" data-carousel-slide-to="3"></button>
    </div>
</div>
