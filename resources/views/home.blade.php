@extends('layouts.app')

@section('title','Eventina: event lokal, tiket langsung')

@php
    $formatPrice = function ($subevent) {
        $ticket = $subevent->tickets->sortBy('price')->first();
        if (! $ticket) {
            return null;
        }
        $amount = ($ticket->currency ?? 'IDR') === 'IDR'
            ? 'Rp' . number_format($ticket->price, 0, ',', '.')
            : $ticket->currency . ' ' . number_format($ticket->price, 2);

        return ($subevent->tickets->count() > 1 ? 'mulai ' : '') . $amount;
    };
    $thumbnail = fn ($subevent) => $subevent->event->thumbnail ? \Illuminate\Support\Facades\Storage::url($subevent->event->thumbnail) : null;
    $date = fn ($subevent, $format) => $subevent->start_at->locale('id')->translatedFormat($format);
@endphp

@section('content')
<style>
    /* Condensed, heavy Archivo: the gig-poster voice of the page */
    .poster-type { font-stretch: 72%; letter-spacing: -0.01em; }

    /* Ticket stub: half-circle notches punched top and bottom at --cut, like a real tear-off ticket */
    .ticket-stub {
        --notch: 12px;
        -webkit-mask:
            radial-gradient(circle var(--notch) at var(--cut) 0, #0000 98%, #000) top / 100% 51% no-repeat,
            radial-gradient(circle var(--notch) at var(--cut) 100%, #0000 98%, #000) bottom / 100% 51% no-repeat;
                mask:
            radial-gradient(circle var(--notch) at var(--cut) 0, #0000 98%, #000) top / 100% 51% no-repeat,
            radial-gradient(circle var(--notch) at var(--cut) 100%, #0000 98%, #000) bottom / 100% 51% no-repeat;
    }
    .ticket-row { --cut: calc(100% - 8.5rem); }
    @media (min-width: 768px) { .ticket-row { --cut: calc(100% - 13rem); } }
    .ticket-featured { --cut: 50%; }
    .perforation { border-left: 2px dashed rgb(57 78 106 / 0.35); }
</style>

<div class="w-11/12 max-w-6xl mx-auto">

    {{-- Hero: the slogan as a gig poster, with real concert photos pasted beside it --}}
    <section class="grid gap-12 lg:grid-cols-12 lg:items-center pt-6 pb-16 lg:pt-12 lg:pb-24">
        <div class="lg:col-span-6">
            <h1 class="font-poster poster-type font-black uppercase leading-[0.88] text-[4.5rem] sm:text-[6rem] lg:text-[7.5rem]">
                Scroll,<br>pilih,<br><span class="text-primary">dateng.</span>
            </h1>
            <p class="mt-8 text-lg md:text-xl text-base-content/80 max-w-md leading-relaxed">
                Konser, festival, bazar, pameran, sampai acara komunitas di kota kamu.
                Beli tiketnya di sini, bayar pakai QRIS, invoice langsung masuk akunmu.
            </p>
            <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-4">
                <a href="{{ route('events.index') }}" class="btn border-primary-ink bg-primary-ink text-white hover:bg-[#00449e] hover:border-[#00449e] btn-lg normal-case text-lg">Lihat semua event</a>
                <a href="{{ route('organizers.register') }}" class="link text-primary-ink font-semibold py-3">Mau pasang acara?</a>
            </div>
        </div>

        <div class="lg:col-span-6 relative h-[22rem] sm:h-[28rem] lg:h-[34rem]" aria-hidden="true">
            {{-- Two photos taped to the wall at slight angles: the one place on the page that carries shadow, to read as paper lifted off the wall --}}
            <div class="absolute right-0 top-0 w-[88%] -rotate-2">
                <img src="{{ asset('img/home-stage.jpg') }}" alt="" width="1400" height="933"
                     class="w-full aspect-[3/2] object-cover rounded-md shadow-[0_18px_40px_-18px_rgba(15,23,42,0.55)]">
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 w-24 h-7 bg-white/80 rotate-3"></span>
            </div>
            <div class="absolute left-0 bottom-0 w-[55%] rotate-3">
                <img src="{{ asset('img/home-hands.jpg') }}" alt="" width="900" height="675"
                     class="w-full aspect-[4/3] object-cover rounded-md border-[6px] border-white shadow-[0_18px_40px_-18px_rgba(15,23,42,0.55)]">
            </div>
        </div>
    </section>

    {{-- Upcoming events: real published events only --}}
    <section class="pb-20" aria-labelledby="upcoming-heading">
        <div class="flex flex-wrap items-end justify-between gap-4 border-b-4 border-base-content pb-4">
            <h2 id="upcoming-heading" class="font-poster poster-type font-black uppercase text-5xl md:text-6xl leading-none">Jadwal terdekat</h2>
            <a href="{{ route('events.index') }}" class="link text-primary-ink font-semibold py-3">Semua event</a>
        </div>

        @if($loadFailed)
            <div class="ticket-stub ticket-row mt-10 bg-base-200 rounded-xl p-8 pr-40 md:pr-56" role="alert">
                <p class="text-xl font-bold">Daftar event gagal dimuat.</p>
                <p class="mt-2 text-base-content/80">Coba muat ulang halaman ini. Kalau masih gagal, <a href="{{ route('contact') }}" class="link text-primary-ink">kabari kami</a>.</p>
                <a href="{{ route('home') }}" class="btn border-primary-ink bg-primary-ink text-white hover:bg-[#00449e] hover:border-[#00449e] normal-case mt-6">Muat ulang</a>
            </div>
        @elseif(! $featured)
            <div class="ticket-stub ticket-row mt-10 bg-base-200 rounded-xl p-8 pr-40 md:pr-56">
                <p class="text-xl font-bold">Belum ada event yang dibuka.</p>
                <p class="mt-2 text-base-content/80 max-w-lg">
                    Event muncul di sini begitu penyelenggara menerbitkannya. Sambil nunggu, cek dulu daftar event yang ada.
                </p>
                <div class="mt-6 flex flex-wrap gap-4">
                    <a href="{{ route('events.index') }}" class="btn border-primary-ink bg-primary-ink text-white hover:bg-[#00449e] hover:border-[#00449e] normal-case">Lihat semua event</a>
                    <a href="{{ route('organizers.register') }}" class="btn btn-outline normal-case">Pasang event kamu</a>
                </div>
            </div>
        @else
            {{-- Featured: the closest event gets the poster treatment --}}
            @php $price = $formatPrice($featured); $image = $thumbnail($featured); @endphp
            <article class="relative mt-10 grid lg:grid-cols-12 gap-6 lg:gap-0 lg:items-stretch">
                <div class="lg:col-span-7">
                    @if($image)
                        <img src="{{ $image }}" alt="Poster {{ $featured->event->title }}" class="w-full h-full min-h-[18rem] max-h-[30rem] object-cover rounded-xl lg:rounded-r-none">
                    @else
                        <div class="h-full min-h-[18rem] rounded-xl lg:rounded-r-none bg-primary-ink text-white p-8 flex items-end">
                            <p class="font-poster poster-type font-black uppercase text-6xl md:text-7xl leading-[0.9] break-words">{{ $featured->event->title }}</p>
                        </div>
                    @endif
                </div>
                <div class="lg:col-span-5 bg-base-100 border-2 border-base-content rounded-xl lg:rounded-l-none lg:border-l-0 p-6 md:p-8 flex flex-col">
                    <p class="font-poster poster-type font-black uppercase text-primary-ink text-6xl leading-none">
                        {{ $date($featured, 'd') }} <span class="text-4xl">{{ $date($featured, 'M Y') }}</span>
                    </p>
                    <p class="mt-1 text-base-content/80">{{ ucfirst($date($featured, 'l, H:i')) }}</p>
                    <h3 class="mt-6 text-3xl font-black leading-tight">
                        <a href="{{ route('event.view', ['subevent' => $featured->id]) }}" class="hover:text-primary-ink focus-visible:outline focus-visible:outline-2 focus-visible:outline-primary after:absolute after:inset-0">
                            {{ $featured->event->title }}
                        </a>
                    </h3>
                    @if($featured->title)
                        <p class="mt-1 text-lg">{{ $featured->title }}</p>
                    @endif
                    @if($featured->event->venue?->name)
                        <p class="mt-4 text-base-content/80">{{ $featured->event->venue->name }}</p>
                    @endif
                    <div class="flex-1 min-h-[2rem]"></div>
                    <div class="pt-6 flex flex-wrap items-center justify-between gap-4 border-t-2 border-dashed border-base-content/30">
                        <p class="text-2xl font-black">{{ $price ?? 'Harga belum diumumkan' }}</p>
                        @if($price)
                            <a href="{{ route('tickets.buy.show', ['ticket' => $featured->tickets->sortBy('price')->first()->id]) }}" class="relative z-10 btn border-primary-ink bg-primary-ink text-white hover:bg-[#00449e] hover:border-[#00449e] normal-case text-base">Beli tiket</a>
                        @endif
                    </div>
                </div>
            </article>

            {{-- The rest: tear-off ticket rows --}}
            @if($upcoming->isNotEmpty())
                <ol class="mt-8 space-y-4">
                    @foreach($upcoming as $subevent)
                        @php $price = $formatPrice($subevent); @endphp
                        <li class="ticket-stub ticket-row relative flex bg-base-200 rounded-xl hover:bg-primary/10 transition-colors">
                            <div class="flex items-center gap-4 md:gap-6 p-4 md:p-6 min-w-0 flex-1">
                                <p class="shrink-0 w-14 md:w-16 text-center font-poster poster-type font-black uppercase leading-none">
                                    <span class="block text-4xl md:text-5xl">{{ $date($subevent, 'd') }}</span>
                                    <span class="block text-lg text-primary-ink">{{ $date($subevent, 'M') }}</span>
                                </p>
                                <div class="min-w-0">
                                    <h3 class="text-lg md:text-xl font-bold leading-snug">
                                        <a href="{{ route('event.view', ['subevent' => $subevent->id]) }}" class="hover:text-primary-ink focus-visible:outline focus-visible:outline-2 focus-visible:outline-primary after:absolute after:inset-0">
                                            {{ $subevent->event->title }}
                                        </a>
                                    </h3>
                                    <p class="text-sm md:text-base text-base-content/80 truncate">
                                        {{ collect([$subevent->title, $subevent->event->venue?->name, $date($subevent, 'H:i')])->filter()->implode(' · ') }}
                                    </p>
                                </div>
                            </div>
                            <div class="perforation shrink-0 w-[8.5rem] md:w-[13rem] flex flex-col items-start justify-center gap-2 p-4 md:p-6">
                                <p class="font-bold text-sm md:text-lg leading-tight">{{ $price ?? 'Harga menyusul' }}</p>
                                @if($price)
                                    <a href="{{ route('tickets.buy.show', ['ticket' => $subevent->tickets->sortBy('price')->first()->id]) }}" class="relative z-10 btn border-primary-ink bg-primary-ink text-white hover:bg-[#00449e] hover:border-[#00449e] normal-case min-h-[2.75rem] h-11 px-4">Beli</a>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            @endif
        @endif
    </section>
</div>

{{-- Organizer band: a different composition and a dark ground, so the page changes key before the footer --}}
<section class="bg-slate-900 text-white">
    <div class="w-11/12 max-w-6xl mx-auto py-16 lg:py-20 grid gap-10 lg:grid-cols-12 lg:items-end">
        <div class="lg:col-span-7">
            <h2 class="font-poster poster-type font-black uppercase text-5xl md:text-7xl leading-[0.9]">
                Punya acara?<br>Pasang di sini.
            </h2>
            <p class="mt-6 text-lg text-slate-200 max-w-xl">
                Daftar akun penyelenggara, tunggu disetujui tim Eventina, lalu isi formulir empat tahap.
                Tiketnya langsung bisa dibeli pakai QRIS.
            </p>
        </div>
        <div class="lg:col-span-5">
            <ol class="font-poster poster-type font-black uppercase text-2xl md:text-3xl leading-tight text-slate-300">
                <li><span class="text-white">01</span> Info umum</li>
                <li><span class="text-white">02</span> Sesi &amp; jadwal</li>
                <li><span class="text-white">03</span> Tiket</li>
                <li><span class="text-white">04</span> Kontak bantuan</li>
            </ol>
            <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3">
                <a href="{{ route('organizers.register') }}" class="btn bg-white text-slate-900 border-white hover:bg-slate-200 hover:border-slate-200 normal-case text-base">Daftar jadi penyelenggara</a>
                <a href="{{ route('organizers.login') }}" class="link text-white py-3">Sudah punya akun? Masuk</a>
            </div>
        </div>
    </div>
</section>
@endsection
