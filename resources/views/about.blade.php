@extends('layouts.app')

@section('title','About')

@section('content')
    <div class="w-11/12 max-w-6xl mx-auto">

        {{-- Intro --}}
        <section class="grid gap-10 lg:grid-cols-12 lg:items-center py-10 lg:py-16">
            <div class="lg:col-span-6">
                <h1 class="text-4xl md:text-5xl font-black leading-tight">
                    Event seru di sekitar kamu, <span class="text-primary">gampang dicari.</span>
                </h1>
                <p class="mt-6 text-base md:text-lg text-base-content/80 leading-relaxed">
                    Eventina adalah platform web yang memudahkan masyarakat menemukan, membagikan, dan mengikuti
                    event lokal: konser, festival, bazar, pameran, sampai kegiatan komunitas di sekitar mereka.
                </p>
                <p class="mt-4 text-base md:text-lg text-base-content/80 leading-relaxed">
                    Kami percaya setiap acara lokal punya cerita dan energi sendiri. Acara seperti itu mempererat
                    hubungan antarwarga, mendukung pelaku kreatif, dan menghidupkan lagi semangat komunitas di berbagai daerah.
                </p>
            </div>
            <div class="lg:col-span-6">
                <img src="{{ asset('img/about-crowd.jpg') }}"
                     alt="Penonton mengangkat tangan di depan panggung konser"
                     class="w-full aspect-[4/3] object-cover rounded-2xl"
                     loading="lazy" width="1600" height="1200">
            </div>
        </section>

        {{-- How it works, for both sides of the platform --}}
        <section class="py-10 lg:py-14 border-t border-base-300">
            <h2 class="text-2xl md:text-3xl font-bold">Cara kerjanya</h2>
            <p class="mt-2 text-base-content/80 max-w-2xl">
                Eventina dipakai dua pihak: kamu yang mau datang ke event, dan penyelenggara yang bikin eventnya.
            </p>

            <div class="mt-8 grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-base-300 bg-base-100 p-6 md:p-8">
                    <h3 class="text-xl font-bold">Buat yang mau datang</h3>
                    <ol class="mt-6 space-y-5">
                        <li class="flex gap-4">
                            <span class="shrink-0 w-8 h-8 rounded-full bg-primary/10 text-primary-ink font-bold flex items-center justify-center">1</span>
                            <p class="pt-1">Cari event di halaman Events, lengkap dengan jadwal, lokasi, dan harga tiket.</p>
                        </li>
                        <li class="flex gap-4">
                            <span class="shrink-0 w-8 h-8 rounded-full bg-primary/10 text-primary-ink font-bold flex items-center justify-center">2</span>
                            <p class="pt-1">Beli tiket dan bayar pakai QRIS.</p>
                        </li>
                        <li class="flex gap-4">
                            <span class="shrink-0 w-8 h-8 rounded-full bg-primary/10 text-primary-ink font-bold flex items-center justify-center">3</span>
                            <p class="pt-1">Invoice dan status pembayaranmu tersimpan di dashboard akun.</p>
                        </li>
                        <li class="flex gap-4">
                            <span class="shrink-0 w-8 h-8 rounded-full bg-primary/10 text-primary-ink font-bold flex items-center justify-center">4</span>
                            <p class="pt-1">Mau ikut bantu di balik layar? Daftar jadi volunteer langsung dari halaman event.</p>
                        </li>
                    </ol>
                    <a href="{{ route('events.index') }}" class="btn btn-primary mt-8">Lihat event</a>
                </div>

                <div class="rounded-2xl bg-primary-ink text-white p-6 md:p-8">
                    <h3 class="text-xl font-bold">Buat penyelenggara</h3>
                    <ol class="mt-6 space-y-5">
                        <li class="flex gap-4">
                            <span class="shrink-0 w-8 h-8 rounded-full bg-white/15 font-bold flex items-center justify-center">1</span>
                            <p class="pt-1">Daftar akun penyelenggara.</p>
                        </li>
                        <li class="flex gap-4">
                            <span class="shrink-0 w-8 h-8 rounded-full bg-white/15 font-bold flex items-center justify-center">2</span>
                            <p class="pt-1">Tim Eventina memeriksa akunmu. Kamu dapat email begitu akunnya disetujui.</p>
                        </li>
                        <li class="flex gap-4">
                            <span class="shrink-0 w-8 h-8 rounded-full bg-white/15 font-bold flex items-center justify-center">3</span>
                            <p class="pt-1">Buat event lewat formulir bertahap: info umum, sesi dan jadwal, tiket, lalu kontak bantuan.</p>
                        </li>
                        <li class="flex gap-4">
                            <span class="shrink-0 w-8 h-8 rounded-full bg-white/15 font-bold flex items-center justify-center">4</span>
                            <p class="pt-1">Terima atau tolak pendaftar volunteer untuk eventmu.</p>
                        </li>
                    </ol>
                    <a href="{{ route('organizers.register') }}" class="btn mt-8 bg-white text-primary-ink border-white hover:bg-white/90 hover:border-white">
                        Daftar jadi penyelenggara
                    </a>
                </div>
            </div>
        </section>

        <p class="pb-6 text-base-content/80">
            Ada pertanyaan? <a href="{{ route('contact') }}" class="link text-primary-ink font-semibold">Hubungi tim Eventina</a>.
        </p>
    </div>
@endsection
