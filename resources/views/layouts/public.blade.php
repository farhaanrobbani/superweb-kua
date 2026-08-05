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
        @include('partials.public-header')

        <main>
            @yield('content')
        </main>

        @include('partials.public-footer')
    </body>
</html>
