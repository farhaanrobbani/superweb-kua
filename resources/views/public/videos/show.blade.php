@extends('layouts.public')

@section('title', $video->title.' — '.kua_setting('instansi', 'Surat Digital KUA'))

@section('metaDescription', $video->excerpt ?: $video->title)

@php
    $shareUrl = url(kua_navbar_page_url('video').'/'.$video->slug);
    $shareText = $video->title;
    $embedUrl = \App\Support\VideoEmbed::url($video->video_url);
@endphp

@push('head')
    <meta property="og:title" content="{{ $video->title }}">
    <meta property="og:description" content="{{ $video->excerpt ?: $video->title }}">
    <meta property="og:type" content="video.other">
    <meta property="og:url" content="{{ $shareUrl }}">
    <meta property="og:site_name" content="{{ kua_setting('instansi', 'Surat Digital KUA') }}">
    @if ($video->thumbnailUrl())
        <meta property="og:image" content="{{ $video->thumbnailUrl() }}">
        <meta name="twitter:card" content="summary_large_image">
    @else
        <meta name="twitter:card" content="summary">
    @endif
    <meta name="twitter:title" content="{{ $video->title }}">
    <meta name="twitter:description" content="{{ $video->excerpt ?: $video->title }}">
@endpush

@section('content')
    <section class="bg-gradient-to-br from-teal-900 via-teal-950 to-teal-950 py-14 text-white">
        <div class="mx-auto max-w-4xl px-6">
            <nav class="text-sm text-teal-200/80">
                <a href="{{ kua_navbar_page_url('video') }}" class="hover:text-white">Semua Video</a>
                <span class="mx-2">/</span>
                <span>{{ $video->title }}</span>
            </nav>
            <h1 class="mt-3 text-3xl font-extrabold md:text-4xl">{{ $video->title }}</h1>
            <p class="mt-4 text-sm text-teal-100/80">
                {{ tanggal_indonesia($video->published_at ?? $video->created_at, 'd F Y') }}
                @if ($video->author) — oleh {{ $video->author->name }} @endif
            </p>
        </div>
    </section>

    <section class="py-12">
        <div class="mx-auto max-w-4xl px-6">
            @if ($embedUrl)
                <div class="aspect-video overflow-hidden rounded-2xl border border-teal-100 bg-black shadow-sm">
                    <iframe src="{{ $embedUrl }}" title="{{ $video->title }}" class="h-full w-full" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>
                </div>
            @endif

            @if ($video->excerpt)
                <p class="mt-6 text-slate-600">{{ $video->excerpt }}</p>
            @endif

            @if ($video->content)
                <div class="konten-pengumuman mt-6 leading-relaxed">{!! \App\Support\HtmlSanitizer::sanitize($video->content) !!}</div>
            @endif

            <a href="{{ kua_navbar_page_url('video') }}"
               class="mt-10 inline-block rounded-lg bg-teal-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-teal-600">
                &larr; Semua Video
            </a>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="border-t border-teal-100 bg-white py-12">
            <div class="mx-auto max-w-7xl px-6">
                <h2 class="text-2xl font-bold text-slate-900">Video Lainnya</h2>
                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $item)
                        <a href="{{ kua_navbar_page_url('video').'/'.$item->slug }}"
                           class="group relative overflow-hidden rounded-2xl border border-teal-100 bg-white shadow-sm transition hover:shadow-md">
                            <div class="relative flex h-32 items-center justify-center bg-teal-900">
                                @if ($item->thumbnailUrl())
                                    <img src="{{ $item->thumbnailUrl() }}" alt="{{ $item->title }}" class="h-full w-full object-cover" />
                                @endif
                                <span class="absolute inset-0 flex items-center justify-center bg-black/25">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/90 text-teal-700">▶</span>
                                </span>
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-semibold leading-snug text-slate-900 group-hover:text-teal-700">{{ $item->title }}</h3>
                                <p class="mt-1 text-xs text-slate-500">{{ tanggal_indonesia($item->published_at ?? $item->created_at, 'd M Y') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
