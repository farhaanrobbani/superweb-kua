@extends('layouts.public')

@section('title', $announcement->title.' — '.kua_setting('instansi', 'Surat Digital KUA'))

@php
    $shareUrl = route('pengumuman.show', $announcement);
    $shareText = $announcement->title;
@endphp

@push('head')
    <meta property="og:title" content="{{ $announcement->title }}">
    <meta property="og:description" content="{{ str(strip_tags($announcement->content))->limit(160) }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ $shareUrl }}">
    <meta property="og:site_name" content="{{ kua_setting('instansi', 'Surat Digital KUA') }}">
    @if ($announcement->imageUrl())
        <meta property="og:image" content="{{ $announcement->imageUrl() }}">
        <meta name="twitter:card" content="summary_large_image">
    @else
        <meta name="twitter:card" content="summary">
    @endif
    <meta name="twitter:title" content="{{ $announcement->title }}">
    <meta name="twitter:description" content="{{ str(strip_tags($announcement->content))->limit(160) }}">
@endpush

@section('content')
    <article class="mx-auto max-w-3xl px-6 pb-16 pt-12">
        <a href="{{ route('pengumuman.index') }}" class="text-sm text-teal-700 hover:underline">&larr; Semua Pengumuman</a>
        <h1 class="mt-4 text-2xl font-bold">{{ $announcement->title }}</h1>
        <p class="mt-2 text-sm text-[#1b1b1870]">
            {{ tanggal_indonesia($announcement->published_at ?? $announcement->created_at, 'd F Y') }}
        </p>

        <div class="mt-4 flex flex-wrap items-center gap-2">
            <span class="text-sm font-medium text-[#1b1b1870]">Bagikan:</span>
            <a href="https://wa.me/?text={{ urlencode($shareText.' '.$shareUrl) }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-1.5 rounded-md border border-teal-100 bg-white px-3 py-1.5 text-sm font-medium text-teal-800 transition hover:bg-teal-50"
               aria-label="Bagikan ke WhatsApp">
                @include('partials.sosmed-icon', ['platform' => 'whatsapp', 'class' => 'h-4 w-4'])
                WhatsApp
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-1.5 rounded-md border border-teal-100 bg-white px-3 py-1.5 text-sm font-medium text-teal-800 transition hover:bg-teal-50"
               aria-label="Bagikan ke Facebook">
                @include('partials.sosmed-icon', ['platform' => 'facebook', 'class' => 'h-4 w-4'])
                Facebook
            </a>
            <button type="button" x-data="{ copied: false }"
                    @click="navigator.clipboard.writeText('{{ $shareUrl }}').then(() => { copied = true; setTimeout(() => copied = false, 2000); }).catch(() => {})"
                    class="inline-flex items-center gap-1.5 rounded-md border border-teal-100 bg-white px-3 py-1.5 text-sm font-medium text-teal-800 transition hover:bg-teal-50"
                    aria-label="Salin tautan">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                </svg>
                <span x-text="copied ? 'Tersalin!' : 'Salin Tautan'"></span>
            </button>
            <button type="button" x-data="{ copied: false }"
                    @click="if (navigator.share) { navigator.share({ title: {{ json_encode($shareText) }}, url: '{{ $shareUrl }}' }).catch(() => {}); } else { navigator.clipboard.writeText('{{ $shareUrl }}').then(() => { copied = true; setTimeout(() => copied = false, 2000); }).catch(() => {}); }"
                    class="inline-flex items-center gap-1.5 rounded-md border border-teal-100 bg-white px-3 py-1.5 text-sm font-medium text-teal-800 transition hover:bg-teal-50"
                    aria-label="Bagikan">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" />
                </svg>
                <span x-text="copied ? 'Tersalin!' : 'Bagikan'"></span>
            </button>
        </div>

        @if ($announcement->imageUrl())
            <img src="{{ $announcement->imageUrl() }}" alt="{{ $announcement->title }}"
                 class="mt-6 w-full rounded-lg border border-teal-100 object-contain" />
        @endif
        <div class="mt-6 leading-relaxed konten-pengumuman">{!! \App\Support\HtmlSanitizer::sanitize($announcement->content) !!}</div>
    </article>
@endsection
