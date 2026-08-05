<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', kua_setting('instansi', 'Surat Digital KUA'))</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gradient-to-br from-teal-50 via-emerald-50 to-white text-[#1b1b18] font-sans antialiased">
        <header class="sticky top-0 z-10 border-b border-teal-100 bg-white/90 backdrop-blur">
            <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-3">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    @if (\App\Models\KuaSetting::logoUrl())
                        <img src="{{ \App\Models\KuaSetting::logoUrl() }}" alt="Logo {{ kua_setting('instansi', 'KUA') }}"
                             class="h-9 w-9 rounded-md object-contain" />
                    @else
                        <div class="flex h-8 w-8 items-center justify-center rounded-md bg-teal-700 text-sm font-bold text-white">K</div>
                    @endif
                    <span class="text-sm font-semibold tracking-wide">{{ kua_setting('instansi', 'Surat Digital KUA') }}</span>
                </a>
                <nav class="flex items-center gap-4 text-sm">
                    <a href="{{ route('permohonan.create') }}" class="rounded-md px-3 py-1.5 hover:bg-teal-50 hover:text-teal-800">Permohonan</a>
                    <a href="{{ route('pengumuman.index') }}" class="rounded-md px-3 py-1.5 hover:bg-teal-50 hover:text-teal-800">Pengumuman</a>
                </nav>
            </div>
        </header>

        <main>
            @yield('content')
        </main>

        <footer class="border-t border-teal-100 bg-white/60">
            <div class="mx-auto max-w-5xl px-6 py-6 text-center text-xs text-[#1b1b1870]">
                &copy; {{ date('Y') }} {{ kua_setting('instansi', 'Kantor Urusan Agama') }}. Seluruh hak cipta dilindungi.
            </div>
        </footer>
    </body>
</html>
