@extends('layouts.public')

@section('title', (kua_navbar_page_label('video', $page->title ?? 'Video')).' — '.kua_setting('instansi', 'Surat Digital KUA'))

@section('content')
    @php
        $videoUrl = kua_navbar_page_url('video');
    @endphp

    <section class="bg-gradient-to-br from-teal-900 via-teal-950 to-teal-950 py-14 text-white">
        <div class="mx-auto max-w-7xl px-6">
            <p class="text-sm font-semibold text-teal-300">VIDEO</p>
            <h1 class="mt-2 text-3xl font-extrabold md:text-4xl">{{ $page->title ?? 'Galeri Video' }}</h1>
            @if ($page?->description)
                <p class="mt-3 max-w-2xl text-sm text-teal-100/80">{{ $page->description }}</p>
            @endif

            <form method="GET" action="{{ $videoUrl }}" class="mt-6 flex max-w-md gap-2">
                <input type="text" name="q" value="{{ $q }}"
                       placeholder="Cari video..."
                       class="block w-full rounded-md border-0 px-4 py-2 text-sm text-[#1b1b18] shadow-sm focus:ring-2 focus:ring-teal-400" />
                <button type="submit"
                        class="shrink-0 rounded-md bg-teal-600 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-500">
                    Cari
                </button>
            </form>
        </div>
    </section>

    <section class="py-12">
        <div class="mx-auto max-w-7xl px-6">
            @if ($q !== '')
                <p class="mb-6 text-sm text-[#1b1b1870]">
                    Hasil pencarian untuk <strong>"{{ $q }}"</strong> —
                    <a href="{{ $videoUrl }}" class="text-teal-700 hover:underline">hapus pencarian</a>
                </p>
            @endif

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($videos as $video)
                    <a href="{{ kua_navbar_page_url('video').'/'.$video->slug }}"
                       class="group overflow-hidden rounded-2xl border border-teal-100 bg-white shadow-sm transition hover:shadow-md">
                        <div class="relative flex h-44 items-center justify-center bg-teal-900 text-4xl">
                            @if ($video->thumbnailUrl())
                                <img src="{{ $video->thumbnailUrl() }}" alt="{{ $video->title }}"
                                     class="h-full w-full object-cover transition duration-300 group-hover:scale-105" />
                            @endif
                            <span class="absolute inset-0 flex items-center justify-center bg-black/25">
                                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-lg text-teal-700">▶</span>
                            </span>
                        </div>
                        <div class="p-5">
                            <span class="shrink-0 text-xs text-slate-500">{{ tanggal_indonesia($video->published_at ?? $video->created_at, 'd M Y') }}</span>
                            <h2 class="mt-2 font-semibold leading-snug text-slate-900 group-hover:text-teal-700">{{ $video->title }}</h2>
                            @if ($video->excerpt)
                                <p class="mt-2 line-clamp-3 text-sm text-slate-500">{{ $video->excerpt }}</p>
                            @endif
                        </div>
                    </a>
                @empty
                    <p class="col-span-full py-12 text-center text-sm text-[#1b1b1870]">
                        @if ($q !== '')
                            Tidak ada video yang cocok dengan pencarian <strong>"{{ $q }}"</strong>.
                        @else
                            Belum ada video.
                        @endif
                    </p>
                @endforelse
            </div>

            <div class="mt-10">{{ $videos->links() }}</div>
        </div>
    </section>
@endsection
