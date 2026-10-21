<div class="flex justify-center items-center mt-10 mb-5">
    <div class="flex flex-col lg:flex-row justify-around items-center bg-primary rounded-xl w-11/12 p-10">
        <div class="flex flex-col justify-center items-center mb-10 lg:mb-0 lg:w-3/5 lg:items-start">
            <h1 class="text-3xl md:text-4xl font-black p-1 text-white text-center lg:text-left">Cari Event Kalcer lu di Eventina</h1>
            <div class="flex flex-col p-1 items-center lg:items-start">
                <p class="text-md md:text-md text-white text-center lg:text-left"></p>
                <p class="text-md md:text-md text-white font-bold">Langsung aja disikat!</p>
            </div>
            <a class="btn btn-white mt-5 w-fit" href="{{ route('events.index') }}">Sikat</a>
        </div>
        <div class="lg:w-2/6">
            <img src="{{ asset('img/call_to_action.png') }}" class="h-64 object-contain" alt="CTA" />
        </div>
    </div>
</div>
