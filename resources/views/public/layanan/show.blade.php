@extends('layouts.public')

@section('title', $service->name.' — '.kua_setting('instansi', 'Surat Digital KUA'))

@section('content')
    <div class="mx-auto max-w-6xl px-6 pb-16 pt-12">
        <a href="{{ route('welcome') }}" class="text-sm text-teal-700 hover:underline">&larr; Beranda</a>
        <h1 class="mt-4 text-2xl font-bold">{{ $service->name }}</h1>
        @if ($service->description)
            <p class="mt-2 text-sm text-[#1b1b1870]">{{ $service->description }}</p>
        @endif

        <div class="mt-6 overflow-hidden rounded-lg border border-teal-100 bg-white shadow-sm">
            <iframe src="{{ $service->embed_url }}" width="100%" frameborder="0"
                    class="h-[85vh] min-h-[480px]" style="border:0" allowfullscreen
                    sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox"></iframe>
        </div>
    </div>
@endsection
