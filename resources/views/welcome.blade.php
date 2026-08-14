<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $kua['instansi'] ?? config('app.name', 'Surat Digital KUA') }} — {{ $kua['kecamatan'] ? 'Kecamatan '.$kua['kecamatan'] : 'Layanan Surat Online' }}</title>

        <link rel="icon" href="{{ \App\Models\KuaSetting::logoUrl() ?: asset('favicon.ico') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gradient-to-br from-teal-50 via-emerald-50 to-white text-[#1b1b18] font-sans antialiased">
        @include('partials.public-header')

        <main>
            @php($hasBg = ! empty($kua['bg_url']))
            <section @if ($hasBg) style="background-image: url('{{ $kua['bg_url'] }}')" @endif
                     class="relative @if ($hasBg) bg-cover bg-center @else bg-gradient-to-br from-teal-50 via-emerald-50 to-white @endif">
                @if ($hasBg)
                    <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/50 to-black/70"></div>
                @endif
                <div class="relative mx-auto max-w-5xl px-6 pb-16 pt-16 text-center sm:pt-20">
                    <p class="text-sm font-medium uppercase tracking-widest {{ $hasBg ? 'text-teal-100' : 'text-teal-700' }}">
                        {{ $kua['kecamatan'] ? 'Kantor Urusan Agama Kecamatan '.$kua['kecamatan'] : 'Kantor Urusan Agama' }}
                    </p>
                    <h1 class="mx-auto mt-3 max-w-3xl text-4xl font-bold leading-tight sm:text-5xl {{ $hasBg ? 'text-white' : '' }}">
                        {!! ! empty($kua['hero_judul']) ? nl2br(e($kua['hero_judul'])) : 'Layanan Surat Digital<br>Tanpa Antre, Kapan Saja' !!}
                    </h1>
                    <p class="mx-auto mt-4 max-w-xl text-base leading-relaxed {{ $hasBg ? 'text-white/85' : 'text-[#1b1b1870]' }}">
                        {{ ! empty($kua['hero_subjudul']) ? $kua['hero_subjudul'] : 'Ajukan permohonan surat keterangan dan surat pengantar secara online.
Pihak KUA akan memverifikasi, menerbitkan, dan menandatangani surat Anda secara digital.' }}
                    </p>
                </div>
            </section>

            @if (! empty($kua['hero_url']))
                <img src="{{ $kua['hero_url'] }}" alt="Banner {{ $kua['instansi'] ?? 'KUA' }}"
                     class="mx-auto mt-8 max-h-72 w-full max-w-4xl rounded-xl border border-teal-100 object-cover shadow-sm" />
            @endif

            @if ($services->isNotEmpty())
                <section class="mx-auto max-w-5xl px-6 pb-16">
                    <h2 class="text-center text-xl font-bold">Layanan Kami</h2>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($services as $service)
                            <a href="{{ $service->url ? url($service->url) : '#' }}"
                               class="rounded-lg border border-teal-100 bg-white p-5 shadow-sm transition hover:border-teal-300">
                                <div class="flex items-center gap-3">
                                    <span class="text-teal-700">@include('partials.service-icon', ['icon' => $service->icon, 'class' => 'h-7 w-7'])</span>
                                    <h3 class="font-semibold text-teal-900">{{ $service->label }}</h3>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($announcements->isNotEmpty())
                <section class="mx-auto max-w-5xl px-6 pb-16">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold">Pengumuman Terbaru</h2>
                        <a href="{{ route('pengumuman.index') }}" class="text-sm font-medium text-teal-700 hover:underline">
                            Lihat Semua Pengumuman
                        </a>
                    </div>
                    <div class="mt-6 space-y-3">
                        @foreach ($announcements as $announcement)
                            <a href="{{ route('pengumuman.show', $announcement) }}"
                               class="flex gap-4 rounded-lg border border-teal-100 bg-white p-5 shadow-sm transition hover:border-teal-300">
                                @if ($announcement->imageUrl())
                                    <img src="{{ $announcement->imageUrl() }}" alt="{{ $announcement->title }}"
                                         class="h-24 w-32 shrink-0 rounded-md object-cover" />
                                @endif
                                <div class="min-w-0">
                                    <h3 class="font-semibold text-teal-900">{{ $announcement->title }}</h3>
                                    <p class="mt-1 text-sm leading-relaxed text-[#1b1b1870]">{{ str(strip_tags($announcement->content))->limit(130) }}</p>
                                    <p class="mt-2 text-xs text-[#1b1b1870]">
                                        {{ tanggal_indonesia($announcement->published_at ?? $announcement->created_at, 'd F Y') }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </main>

        @include('partials.public-footer')
    </body>
</html>
