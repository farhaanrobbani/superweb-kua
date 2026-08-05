<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $kua['instansi'] ?? config('app.name', 'Surat Digital KUA') }} — {{ $kua['kecamatan'] ? 'Kecamatan '.$kua['kecamatan'] : 'Layanan Surat Online' }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#FDFDFC] text-[#1b1b18] font-sans antialiased">
        <header class="sticky top-0 z-10 border-b border-[#19140012] bg-white/90 backdrop-blur">
            <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-3">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-md bg-emerald-700 text-sm font-bold text-white">K</div>
                    <span class="text-sm font-semibold tracking-wide">{{ $kua['instansi'] ?? 'Surat Digital KUA' }}</span>
                </div>
                <nav class="flex items-center gap-3 text-sm">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="rounded-md bg-emerald-700 px-4 py-1.5 font-medium text-white hover:bg-emerald-800">Dashboard</a>
                    @else
                        <a href="{{ route('permohonan.create') }}" class="rounded-md px-3 py-1.5 hover:bg-emerald-50 hover:text-emerald-800">Permohonan</a>
                        <a href="{{ route('login') }}" class="rounded-md border border-emerald-700 px-4 py-1.5 font-medium text-emerald-700 hover:bg-emerald-50">Login Staf</a>
                    @endauth
                </nav>
            </div>
        </header>

        <main>
            <section class="mx-auto max-w-5xl px-6 pb-14 pt-16 text-center">
                <p class="text-sm font-medium uppercase tracking-widest text-emerald-700">
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
                    <a href="{{ route('permohonan.create') }}" class="rounded-md bg-emerald-700 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-emerald-800">
                        Ajukan Permohonan Surat
                    </a>
                    @guest
                        <a href="{{ route('login') }}" class="rounded-md border border-[#19140035] px-6 py-3 text-sm font-semibold hover:border-[#1915014a]">
                            Login Staf KUA
                        </a>
                    @endguest
                </div>
            </section>

            @if ($letterTypes->isNotEmpty())
                <section class="mx-auto max-w-5xl px-6 pb-16">
                    <h2 class="text-center text-xl font-bold">Jenis Surat yang Dilayani</h2>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($letterTypes as $type)
                            <div class="rounded-lg border border-[#19140012] p-5">
                                <h3 class="font-semibold text-emerald-800">{{ $type->name }}</h3>
                                <p class="mt-1 text-sm leading-relaxed text-[#1b1b1870]">{{ $type->description }}</p>
                            </div>
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
