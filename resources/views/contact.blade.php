@extends('layouts.app')

@section('title','Contact')

@php
    $channels = [
        [
            'label' => 'Email',
            'value' => 'sem@et.com',
            'href' => 'mailto:sem@et.com',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 6.75A1.75 1.75 0 0 1 4.75 5h14.5A1.75 1.75 0 0 1 21 6.75v10.5A1.75 1.75 0 0 1 19.25 19H4.75A1.75 1.75 0 0 1 3 17.25V6.75Z"/><path stroke-linecap="round" stroke-linejoin="round" d="m3.5 6.5 8.5 6.5 8.5-6.5"/>',
        ],
        [
            'label' => 'Telepon',
            'value' => '0812-3618-2636',
            'href' => 'tel:+6281236182636',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 4h3.5l1.75 4.5-2.25 1.5a11 11 0 0 0 6 6l1.5-2.25L20 15.5V19a1.5 1.5 0 0 1-1.5 1.5A15.5 15.5 0 0 1 3.5 5.5 1.5 1.5 0 0 1 5 4Z"/>',
        ],
        [
            'label' => 'Instagram',
            'value' => '@eventina.id',
            'href' => 'https://www.instagram.com/eventina.id/',
            'icon' => '<rect x="3.5" y="3.5" width="17" height="17" rx="4.5"/><circle cx="12" cy="12" r="4"/><circle cx="17.25" cy="6.75" r="0.75" fill="currentColor" stroke="none"/>',
        ],
    ];
@endphp

@section('content')
    <div class="w-11/12 max-w-6xl mx-auto py-10 lg:py-16">
        <div class="grid gap-12 lg:grid-cols-12">

            <section class="lg:col-span-7">
                <h1 class="text-4xl md:text-5xl font-black leading-tight">
                    Ngobrol sama <span class="text-primary">tim Eventina</span>
                </h1>
                <p class="mt-4 text-base md:text-lg text-base-content/80 max-w-xl">
                    Ada pertanyaan soal tiket, pembayaran, atau mau bikin event sendiri? Hubungi kami lewat salah satu jalur ini.
                </p>

                <ul class="mt-10 divide-y divide-base-300 border-y border-base-300">
                    @foreach($channels as $channel)
                        <li>
                            <a href="{{ $channel['href'] }}"
                               @if(str_starts_with($channel['href'], 'http')) target="_blank" rel="noopener" @endif
                               class="group flex items-center gap-5 py-5 px-2 -mx-2 rounded-lg hover:bg-base-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-primary">
                                <span class="shrink-0 w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">{!! $channel['icon'] !!}</svg>
                                </span>
                                <span class="flex flex-col min-w-0">
                                    <span class="text-sm text-base-content/80">{{ $channel['label'] }}</span>
                                    <span class="text-lg md:text-xl font-semibold break-words group-hover:text-primary">{{ $channel['value'] }}</span>
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>

            <aside class="lg:col-span-5">
                <div class="rounded-2xl bg-base-200 p-6 md:p-8">
                    <h2 class="text-xl font-bold">Biar cepat dibantu</h2>
                    <dl class="mt-6 space-y-6">
                        <div>
                            <dt class="font-semibold">Masalah pembayaran atau tiket</dt>
                            <dd class="mt-1 text-base-content/80">
                                Sertakan nomor invoice. Nomornya ada di halaman invoice, bisa dibuka dari
                                <a href="{{ route('dashboard') }}" class="link text-primary-ink">dashboard</a> akunmu.
                            </dd>
                        </div>
                        <div>
                            <dt class="font-semibold">Akun penyelenggara belum disetujui</dt>
                            <dd class="mt-1 text-base-content/80">
                                Kirim email yang kamu pakai waktu
                                <a href="{{ route('organizers.register') }}" class="link text-primary-ink">daftar sebagai penyelenggara</a>.
                            </dd>
                        </div>
                        <div>
                            <dt class="font-semibold">Mau jadi volunteer</dt>
                            <dd class="mt-1 text-base-content/80">
                                Tidak perlu hubungi kami. Pilih eventnya di
                                <a href="{{ route('events.index') }}" class="link text-primary-ink">halaman Events</a>,
                                lalu daftar dari halaman event itu.
                            </dd>
                        </div>
                    </dl>
                </div>
            </aside>
        </div>
    </div>
@endsection
