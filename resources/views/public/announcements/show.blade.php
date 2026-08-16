@extends('layouts.public')

@section('title', $announcement->title.' — '.kua_setting('instansi', 'Surat Digital KUA'))

@section('metaDescription', $announcement->excerpt())

@php
    $shareUrl = route('pengumuman.show', $announcement);
    $shareText = $announcement->title;
@endphp

@push('head')
    <meta property="og:title" content="{{ $announcement->title }}">
    <meta property="og:description" content="{{ $announcement->excerpt() }}">
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
    <meta name="twitter:description" content="{{ $announcement->excerpt() }}">
@endpush

@section('content')
    <section class="bg-gradient-to-br from-teal-900 via-teal-950 to-teal-950 py-14 text-white">
        <div class="mx-auto max-w-4xl px-6">
            <nav class="text-sm text-teal-200/80">
                <a href="{{ route('pengumuman.index') }}" class="hover:text-white">Semua Pengumuman</a>
                <span class="mx-2">/</span>
                <span>{{ $announcement->category?->label() ?? 'Pengumuman' }}</span>
            </nav>
            <h1 class="mt-3 text-3xl font-extrabold md:text-4xl">{{ $announcement->title }}</h1>
            <p class="mt-4 text-sm text-teal-100/80">
                {{ tanggal_indonesia($announcement->published_at ?? $announcement->created_at, 'd F Y') }}
                @if ($announcement->author)
                    — oleh {{ $announcement->author->name }}
                @endif
            </p>
        </div>
    </section>

    <section class="py-12">
        <div class="mx-auto max-w-4xl px-6">
            @if ($announcement->imageUrl())
                <div class="overflow-hidden rounded-2xl border border-teal-100 shadow-sm">
                    <img src="{{ $announcement->imageUrl() }}" alt="{{ $announcement->title }}" class="h-80 w-full object-cover" />
                </div>
            @endif

            <div class="mt-6 flex flex-wrap items-center gap-2">
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

            @if ($announcement->excerpt)
                <p class="mt-8 text-lg font-medium leading-relaxed text-[#1b1b18]/90">{{ $announcement->excerpt }}</p>
            @endif

            <div class="konten-pengumuman mt-6 leading-relaxed">{!! \App\Support\HtmlSanitizer::sanitize($announcement->content) !!}</div>

            <a href="{{ route('pengumuman.index') }}"
               class="mt-10 inline-block rounded-lg bg-teal-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-teal-600">
                &larr; Semua Pengumuman
            </a>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="border-t border-teal-100 bg-white py-12">
            <div class="mx-auto max-w-7xl px-6">
                <h2 class="text-2xl font-bold text-slate-900">Pengumuman Lainnya</h2>
                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $item)
                        <a href="{{ route('pengumuman.show', $item) }}"
                           class="group overflow-hidden rounded-2xl border border-teal-100 bg-white shadow-sm transition hover:shadow-md">
                            <div class="flex h-32 items-center justify-center bg-teal-50 text-3xl">
                                @if ($item->imageUrl())
                                    <img src="{{ $item->imageUrl() }}" alt="{{ $item->title }}" class="h-full w-full object-cover" />
                                @else
                                    <svg class="h-8 w-8 text-teal-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 9l-3.75 3.75m0 0L11.25 16.5m-3.75-3.75h3m-6.75 2.25a3.75 3.75 0 013.75-3.75H5.25a3.75 3.75 0 013.75-3.75h4.5a3.75 3.75 0 013.75 3.75v2.25a3.75 3.75 0 01-3.75 3.75H9.75a3.75 3.75 0 01-3.75-3.75z" />
                                    </svg>
                                @endif
                            </div>
                            <div class="p-4">
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $item->category?->color() ?? 'bg-teal-100 text-teal-800' }}">{{ $item->category?->label() ?? 'Pengumuman' }}</span>
                                <h3 class="mt-2 text-sm font-semibold leading-snug text-slate-900 group-hover:text-teal-700">{{ $item->title }}</h3>
                                <p class="mt-1 text-xs text-slate-500">{{ tanggal_indonesia($item->published_at ?? $item->created_at, 'd M Y') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
