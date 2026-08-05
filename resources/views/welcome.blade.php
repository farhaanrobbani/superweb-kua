<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $kua['instansi'] ?? config('app.name', 'Surat Digital KUA') }} — {{ $kua['kecamatan'] ? 'Kecamatan '.$kua['kecamatan'] : 'Layanan Surat Online' }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gradient-to-br from-teal-50 via-emerald-50 to-white text-[#1b1b18] font-sans antialiased">
        <header class="sticky top-0 z-10 border-b border-[#19140012] bg-white/90 backdrop-blur">
            <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-3">
                <div class="flex items-center gap-2">
                    @if ($kua['logo_url'])
                        <img src="{{ $kua['logo_url'] }}" alt="Logo {{ $kua['instansi'] ?? 'KUA' }}"
                             class="h-9 w-9 rounded-md object-contain" />
                    @else
                        <div class="flex h-8 w-8 items-center justify-center rounded-md bg-teal-700 text-sm font-bold text-white">K</div>
                    @endif
                    <span class="text-sm font-semibold tracking-wide">{{ $kua['instansi'] ?? 'Surat Digital KUA' }}</span>
                </div>
                <nav x-data="{ layanan: false }" @click.outside="layanan = false" class="hidden items-center gap-6 text-sm font-medium sm:flex">
                    <a href="{{ url('/') }}" class="text-teal-800 hover:text-teal-600">Beranda</a>
                    <div class="relative">
                        <button type="button" @click="layanan = !layanan"
                                class="inline-flex items-center gap-1 text-teal-800 hover:text-teal-600"
                                :aria-expanded="layanan">
                            Layanan
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="layanan" x-transition x-cloak
                             class="absolute start-1/2 mt-2 w-72 -translate-x-1/2 rounded-lg border border-teal-100 bg-white p-2 shadow-lg">
                            @forelse ($services as $service)
                                <a href="{{ $service->url ? url($service->url) : '#' }}"
                                   class="flex items-start gap-3 rounded-md px-3 py-2.5 hover:bg-teal-50">
                                    <span class="mt-0.5 text-teal-700">@include('partials.service-icon', ['icon' => $service->icon])</span>
                                    <span>
                                        <span class="block text-sm font-semibold text-[#1b1b18]">{{ $service->name }}</span>
                                        @if ($service->description)
                                            <span class="block text-xs leading-relaxed text-[#1b1b1870]">{{ $service->description }}</span>
                                        @endif
                                    </span>
                                </a>
                            @empty
                                <span class="block px-3 py-2 text-sm text-[#1b1b1870]">Belum ada layanan.</span>
                            @endforelse
                        </div>
                    </div>
                    <a href="{{ route('pengumuman.index') }}" class="text-teal-800 hover:text-teal-600">Pengumuman</a>
                </nav>
                <a href="{{ route('permohonan.create') }}"
                   class="rounded-md bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-800 sm:hidden">
                    Ajukan Surat
                </a>
            </div>
            <nav x-data="{ layanan: false }" class="flex items-center gap-5 border-t border-[#19140012] px-6 py-2 text-sm font-medium sm:hidden">
                <a href="{{ url('/') }}" class="text-teal-800 hover:text-teal-600">Beranda</a>
                <button type="button" @click="layanan = !layanan" class="inline-flex items-center gap-1 text-teal-800 hover:text-teal-600">
                    Layanan
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <a href="{{ route('pengumuman.index') }}" class="text-teal-800 hover:text-teal-600">Pengumuman</a>
            </nav>
        </header>

        <main>
            <section class="mx-auto max-w-5xl px-6 pb-14 pt-16 text-center">
                <p class="text-sm font-medium uppercase tracking-widest text-teal-700">
                    {{ $kua['kecamatan'] ? 'Kantor Urusan Agama Kecamatan '.$kua['kecamatan'] : 'Kantor Urusan Agama' }}
                </p>
                <h1 class="mx-auto mt-3 max-w-3xl text-4xl font-bold leading-tight sm:text-5xl">
                    Layanan Surat Digital<br>Tanpa Antre, Kapan Saja
                </h1>
                <p class="mx-auto mt-4 max-w-xl text-base leading-relaxed text-[#1b1b1870]">
                    Ajukan permohonan surat keterangan dan surat pengantar secara online.
                    Pihak KUA akan memverifikasi, menerbitkan, dan menandatangani surat Anda secara digital.
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('permohonan.create') }}" class="rounded-md bg-teal-700 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-teal-800">
                        Ajukan Permohonan Surat
                    </a>
                </div>
            </section>

            @if ($services->isNotEmpty())
                <section class="mx-auto max-w-5xl px-6 pb-16">
                    <h2 class="text-center text-xl font-bold">Layanan Kami</h2>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($services as $service)
                            <a href="{{ $service->url ? url($service->url) : '#' }}"
                               class="rounded-lg border border-teal-100 bg-white p-5 shadow-sm transition hover:border-teal-300">
                                <div class="flex items-center gap-3">
                                    <span class="text-teal-700">@include('partials.service-icon', ['icon' => $service->icon, 'class' => 'h-7 w-7'])</span>
                                    <h3 class="font-semibold text-teal-900">{{ $service->name }}</h3>
                                </div>
                                @if ($service->description)
                                    <p class="mt-2 text-sm leading-relaxed text-[#1b1b1870]">{{ $service->description }}</p>
                                @endif
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
                               class="block rounded-lg border border-teal-100 bg-white p-5 shadow-sm transition hover:border-teal-300">
                                <h3 class="font-semibold text-teal-900">{{ $announcement->title }}</h3>
                                <p class="mt-1 text-sm leading-relaxed text-[#1b1b1870]">{{ str(strip_tags($announcement->content))->limit(130) }}</p>
                                <p class="mt-2 text-xs text-[#1b1b1870]">
                                    {{ tanggal_indonesia($announcement->published_at ?? $announcement->created_at, 'd F Y') }}
                                </p>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </main>

        <footer class="border-t border-[#19140012]">
            <div class="mx-auto grid max-w-5xl gap-6 px-6 py-10 text-sm sm:grid-cols-3">
                <div>
                    <p class="font-semibold">{{ $kua['instansi'] ?? 'Kantor Urusan Agama' }}</p>
                    <p class="mt-2 leading-relaxed text-[#1b1b1870]">{{ $kua['kecamatan'] ? 'Kecamatan '.$kua['kecamatan'].($kua['kabupaten'] ? ', '.$kua['kabupaten'] : '') : '' }} {{ $kua['kode_pos'] ? '('. $kua['kode_pos'] .')' : '' }}</p>
                </div>
                <div>
                    <p class="font-semibold">Kontak</p>
                    <p class="mt-2 leading-relaxed text-[#1b1b1870]">
                        @if ($kua['alamat']) {{ $kua['alamat'] }}<br>@endif
                        @if ($kua['telepon']) Telepon: {{ $kua['telepon'] }}<br>@endif
                        @if ($kua['email']) Email: {{ $kua['email'] }}@endif
                    </p>
                </div>
                <div>
                    <p class="font-semibold">Jam Layanan</p>
                    <p class="mt-2 leading-relaxed text-[#1b1b1870]">
                        Senin – Jumat<br>08.00 – 15.00 WIB
                    </p>
                </div>
            </div>
            <div class="border-t border-[#19140012] py-4 text-center text-xs text-[#1b1b1870]">
                &copy; {{ date('Y') }} {{ $kua['instansi'] ?? 'Kantor Urusan Agama' }}. Seluruh hak cipta dilindungi.
            </div>
        </footer>
    </body>
</html>
