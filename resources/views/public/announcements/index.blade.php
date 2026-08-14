@extends('layouts.public')

@section('title', ($page->title ?? 'Pengumuman').' — '.kua_setting('instansi', 'Surat Digital KUA'))

@section('content')
    <section class="mx-auto max-w-4xl px-6 pb-16 pt-12">
        <h1 class="text-center text-2xl font-bold">{{ $page->title ?? 'Pengumuman' }}</h1>
        @if ($page?->description)
            <p class="mt-2 text-center text-sm text-[#1b1b1870]">{{ $page->description }}</p>
        @endif

        <form method="GET" action="{{ route('pengumuman.index') }}" class="mx-auto mt-6 flex max-w-md gap-2">
            <input type="text" name="q" value="{{ $q }}"
                   placeholder="Cari pengumuman..."
                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm" />
            <button type="submit"
                    class="shrink-0 rounded-md bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-800">
                Cari
            </button>
        </form>

        @if ($q !== '')
            <p class="mt-4 text-center text-sm text-[#1b1b1870]">
                Hasil pencarian untuk <strong>"{{ $q }}"</strong> —
                <a href="{{ route('pengumuman.index') }}" class="text-teal-700 hover:underline">hapus pencarian</a>
            </p>
        @endif

        <div class="mt-8 space-y-4">
            @forelse ($announcements as $announcement)
                <a href="{{ route('pengumuman.show', $announcement) }}"
                   class="flex gap-4 rounded-lg border border-teal-100 bg-white p-5 shadow-sm transition hover:border-teal-300">
                    @if ($announcement->imageUrl())
                        <img src="{{ $announcement->imageUrl() }}" alt="{{ $announcement->title }}"
                             class="w-24 shrink-0 rounded-md object-cover sm:w-32" />
                    @endif
                    <div class="min-w-0">
                        <h2 class="font-semibold text-teal-900">{{ $announcement->title }}</h2>
                        <p class="mt-1 text-sm text-[#1b1b1870]">{{ str(strip_tags($announcement->content))->limit(140) }}</p>
                        <p class="mt-2 text-xs text-[#1b1b1870]">
                            {{ tanggal_indonesia($announcement->published_at ?? $announcement->created_at, 'd F Y') }}
                        </p>
                    </div>
                </a>
            @empty
                <div class="rounded-lg border border-teal-100 bg-white p-8 text-center text-sm text-[#1b1b1870]">
                    @if ($q !== '')
                        Tidak ada pengumuman yang cocok dengan pencarian <strong>"{{ $q }}"</strong>.
                    @else
                        Belum ada pengumuman.
                    @endif
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $announcements->links() }}</div>
    </section>
@endsection
