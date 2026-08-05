<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Permohonan Terkirim</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-lg w-full mx-4 bg-white rounded-lg shadow-sm border border-gray-200 p-10 text-center">
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
        <a href="{{ route('permohonan.create') }}" class="inline-block mt-6 text-sm text-blue-600 hover:underline">
            Ajukan permohonan lain
        </a>
    </div>
</body>
</html>
