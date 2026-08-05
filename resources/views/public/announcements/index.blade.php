@extends('layouts.public')

@section('title', kua_setting('instansi', 'Surat Digital KUA').' — Pengumuman')

@section('content')
    <section class="mx-auto max-w-4xl px-6 pb-16 pt-12">
        <h1 class="text-center text-2xl font-bold">Pengumuman</h1>

        <div class="mt-8 space-y-4">
            @forelse ($announcements as $announcement)
                <a href="{{ route('pengumuman.show', $announcement) }}"
                   class="block rounded-lg border border-teal-100 bg-white p-5 shadow-sm transition hover:border-teal-300">
                    <h2 class="font-semibold text-teal-900">{{ $announcement->title }}</h2>
                    <p class="mt-1 text-sm text-[#1b1b1870]">{{ str(strip_tags($announcement->content))->limit(140) }}</p>
                    <p class="mt-2 text-xs text-[#1b1b1870]">
                        {{ tanggal_indonesia($announcement->published_at ?? $announcement->created_at, 'd F Y') }}
                    </p>
                </a>
            @empty
                <div class="rounded-lg border border-teal-100 bg-white p-8 text-center text-sm text-[#1b1b1870]">
                    Belum ada pengumuman.
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $announcements->links() }}</div>
    </section>
@endsection
