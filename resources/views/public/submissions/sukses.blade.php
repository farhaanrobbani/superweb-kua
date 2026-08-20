<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Permohonan Terkirim - {{ kua_setting('instansi', 'Surat Digital KUA') }}</title>
    <link rel="icon" href="{{ \App\Models\KuaSetting::logoUrl() ?: asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-teal-50 via-emerald-50 to-white min-h-screen flex flex-col">
    @include('partials.public-header')

    <main class="flex-1 flex items-center justify-center py-10">
        <div class="max-w-lg w-full mx-4 bg-white rounded-lg shadow-sm border border-teal-100 p-10 text-center">
            <div class="mx-auto mb-4 h-16 w-16 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h1 class="text-xl font-bold text-gray-800">Permohonan Terkirim</h1>
            <p class="text-sm text-gray-500 mt-3">
                Terima kasih. Permohonan Anda telah kami terima dan akan diproses oleh petugas KUA.
                Silakan datang ke kantor KUA dengan membawa dokumen persyaratan atau menunggu
                konfirmasi dari petugas melalui kontak yang Anda berikan.
            </p>
            @if (session('permohonan_unduh'))
                <div class="mt-6 space-y-3">
                    <div class="bg-teal-50 border border-teal-200 rounded-lg p-4 text-sm">
                        <div class="font-medium text-teal-800 mb-1">🔗 Link Tracking Permohonan</div>
                        <p class="text-teal-700 mb-2">Simpan link ini untuk mengecek status permohonan Anda kapan saja:</p>
                        <div class="flex items-center gap-2">
                            <code class="flex-1 text-xs bg-white border border-teal-200 rounded px-3 py-2 text-teal-800 break-all" id="trackUrl">{{ route('permohonan.track', session('permohonan_unduh')) }}</code>
                            <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('trackUrl').textContent).then(()=>this.textContent='✓ Tersalin')" class="shrink-0 px-3 py-2 text-xs font-medium bg-teal-600 text-white rounded hover:bg-teal-700 transition">
                                Salin
                            </button>
                        </div>
                    </div>
                    <a href="{{ route('permohonan.download', session('permohonan_unduh')) }}"
                       class="inline-block rounded-md bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700">
                        Download Surat Permohonan (PDF)
                    </a>
                </div>
            @endif
            <a href="{{ route('permohonan.create') }}" class="mt-6 inline-block text-sm text-teal-700 hover:underline">
                Ajukan permohonan lain
            </a>
        </div>
    </main>

    @include('partials.public-footer')
</body>
</html>
