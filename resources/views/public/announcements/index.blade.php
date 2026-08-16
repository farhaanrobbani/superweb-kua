@extends('layouts.public')

@section('title', (kua_navbar_page_label('pengumuman', $page->title ?? 'Pengumuman')).' — '.kua_setting('instansi', 'Surat Digital KUA'))

@section('metaDescription', $page?->description)

@section('content')
    @php
        $pengumumanUrl = kua_navbar_page_url('pengumuman');
        $semuaUrl = $pengumumanUrl.($q !== '' ? '?'.http_build_query(['q' => $q]) : '');
        $kategoriUrl = fn (string $value) => $pengumumanUrl.'?'.http_build_query(array_filter([
            'category' => $value,
            'q' => $q !== '' ? $q : null,
        ]));
        $hapusUrl = $pengumumanUrl.($category ? '?'.http_build_query(['category' => $category]) : '');
    @endphp

    <section class="bg-gradient-to-br from-teal-900 via-teal-950 to-teal-950 py-14 text-white">
        <div class="mx-auto max-w-7xl px-6">
            <p class="text-sm font-semibold text-teal-300">PENGUMUMAN & BERITA</p>
            <h1 class="mt-2 text-3xl font-extrabold md:text-4xl">{{ $page->title ?? 'Kabar Terbaru KUA' }}</h1>
            @if ($page?->description)
                <p class="mt-3 max-w-2xl text-sm text-teal-100/80">{{ $page->description }}</p>
            @endif

            <form method="GET" action="{{ $pengumumanUrl }}" class="mt-6 flex max-w-md gap-2">
                @if ($category)
                    <input type="hidden" name="category" value="{{ $category }}">
                @endif
                <input type="text" name="q" value="{{ $q }}"
                       placeholder="Cari pengumuman..."
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
            <div class="mb-8 flex flex-wrap gap-3">
                <a href="{{ $semuaUrl }}"
                   class="rounded-full border px-4 py-1.5 text-sm font-medium {{ ! $category ? 'border-teal-700 bg-teal-700 text-white' : 'border-teal-200 text-teal-800 hover:border-teal-400 hover:text-teal-700' }}">
                    Semua
                </a>
                @foreach ($categories as $item)
                    <a href="{{ $kategoriUrl($item->value) }}"
                       class="rounded-full border px-4 py-1.5 text-sm font-medium {{ $category === $item->value ? 'border-teal-700 bg-teal-700 text-white' : 'border-teal-200 text-teal-800 hover:border-teal-400 hover:text-teal-700' }}">
                        {{ $item->label() }}
                    </a>
                @endforeach
            </div>

            @if ($q !== '')
                <p class="mb-6 text-sm text-[#1b1b1870]">
                    Hasil pencarian untuk <strong>"{{ $q }}"</strong> —
                    <a href="{{ $hapusUrl }}" class="text-teal-700 hover:underline">hapus pencarian</a>
                </p>
            @endif

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($announcements as $announcement)
                    <a href="{{ $pengumumanUrl.'/'.$announcement->slug }}"
                       class="group overflow-hidden rounded-2xl border border-teal-100 bg-white shadow-sm transition hover:shadow-md">
                        <div class="flex h-44 items-center justify-center bg-teal-50 text-4xl">
                            @if ($announcement->imageUrl())
                                <img src="{{ $announcement->imageUrl() }}" alt="{{ $announcement->title }}"
                                     class="h-full w-full object-cover transition duration-300 group-hover:scale-105" />
                            @else
                                <svg class="h-10 w-10 text-teal-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 9l-3.75 3.75m0 0L11.25 16.5m-3.75-3.75h3m-6.75 2.25a3.75 3.75 0 013.75-3.75H5.25a3.75 3.75 0 013.75-3.75h4.5a3.75 3.75 0 013.75 3.75v2.25a3.75 3.75 0 01-3.75 3.75H9.75a3.75 3.75 0 01-3.75-3.75z" />
                                </svg>
                            @endif
                        </div>
                        <div class="p-5">
                            <div class="flex items-center justify-between gap-2">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $announcement->category?->color() ?? 'bg-teal-100 text-teal-800' }}">{{ $announcement->category?->label() ?? 'Pengumuman' }}</span>
                                <span class="shrink-0 text-xs text-slate-500">{{ tanggal_indonesia($announcement->published_at ?? $announcement->created_at, 'd M Y') }}</span>
                            </div>
                            <h2 class="mt-3 font-semibold leading-snug text-slate-900 group-hover:text-teal-700">{{ $announcement->title }}</h2>
                            <p class="mt-2 line-clamp-3 text-sm text-slate-500">{{ $announcement->excerpt() }}</p>
                        </div>
                    </a>
                @empty
                    <p class="col-span-full py-12 text-center text-sm text-[#1b1b1870]">
                        @if ($q !== '')
                            Tidak ada pengumuman yang cocok dengan pencarian <strong>"{{ $q }}"</strong>.
                        @else
                            Belum ada pengumuman.
                        @endif
                    </p>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $announcements->links() }}
            </div>
        </div>
    </section>
@endsection
