<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cek Status Permohonan - {{ kua_setting('instansi', 'Surat Digital KUA') }}</title>
    <link rel="icon" href="{{ \App\Models\KuaSetting::logoUrl() ?: asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-teal-50 via-emerald-50 to-white min-h-screen flex flex-col">
    @include('partials.public-header')

    <main class="flex-1 flex items-center justify-center py-10">
        <div class="max-w-md w-full mx-4 bg-white rounded-lg shadow-sm border border-teal-100 p-8 text-center">
            <div class="mx-auto mb-4 h-16 w-16 rounded-full bg-teal-100 flex items-center justify-center">
                <span class="text-2xl">🔍</span>
            </div>
            <h1 class="text-xl font-bold text-gray-800 mb-2">Cek Status Permohonan</h1>
            <p class="text-sm text-gray-500 mb-6">
                Masukkan token yang Anda dapatkan saat mengirim permohonan untuk melihat status terkini.
            </p>

            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm text-left">
                    {{ $errors->first('token') }}
                </div>
            @endif

            <form method="POST" action="{{ route('permohonan.cek.submit') }}">
                @csrf
                <div>
                    <input type="text" name="token" value="{{ old('token') }}" required
                           placeholder="Masukkan token tracking"
                           class="block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-center">
                </div>
                <button type="submit"
                        class="mt-4 w-full inline-flex items-center justify-center px-4 py-2.5 bg-teal-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-600 transition">
                    Lacak
                </button>
            </form>

            <a href="{{ route('permohonan.create') }}" class="mt-4 inline-block text-sm text-teal-700 hover:underline">
                Ajukan permohonan baru
            </a>
        </div>
    </main>

    @include('partials.public-footer')
</body>
</html>
