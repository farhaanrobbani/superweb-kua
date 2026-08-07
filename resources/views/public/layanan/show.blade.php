@extends('layouts.public')

@section('title', $service->name.' — '.kua_setting('instansi', 'Surat Digital KUA'))

@section('content')
    @php
        $page = kua_page('layanan');
    @endphp
    <div class="mx-auto max-w-6xl px-6 pb-16 pt-12">
        <h1 class="mt-4 text-2xl font-bold">{{ $service->name }}</h1>
        @if ($service->description)
            <p class="mt-2 text-sm text-[#1b1b1870]">{{ $service->description }}</p>
        @endif

        <div class="mt-6 overflow-hidden rounded-lg border border-teal-100 bg-white shadow-sm">
            <iframe src="{{ $service->embed_url }}" width="100%" height="600" frameborder="0"
                    style="border:0" allowfullscreen
                    sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox"></iframe>
        </div>

        @if ($page && $page->active && trim((string) $page->content) !== '')
            <div class="mt-10 prose-sm text-sm text-[#1b1b18]">
                {!! \App\Support\HtmlSanitizer::normalize($page->content) !!}
            </div>
        @endif
    </div>
@endsection
