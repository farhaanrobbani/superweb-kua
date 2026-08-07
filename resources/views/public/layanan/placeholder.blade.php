@extends('layouts.public')

@section('title', ($page->title ?? 'Layanan').' — '.kua_setting('instansi', 'Surat Digital KUA'))

@section('content')
    @if (($page->description ?? null) || ($page->embed_url ?? null))
        <div class="mx-auto max-w-6xl px-6 pb-16 pt-12">
            @if ($page?->description)
                <p class="mt-2 text-sm text-[#1b1b1870]">{{ $page->description }}</p>
            @endif

            @if ($page?->embed_url)
                <div class="mt-6 overflow-hidden rounded-lg border border-teal-100 bg-white shadow-sm">
                    <iframe src="{{ $page->embed_url }}" width="100%" height="600" frameborder="0"
                            style="border:0" allowfullscreen
                            sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox"></iframe>
                </div>
            @endif
        </div>
    @endif
@endsection
