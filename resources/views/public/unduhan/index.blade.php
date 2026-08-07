@extends('layouts.public')

@section('title', kua_setting('instansi', 'Surat Digital KUA').' — Download Center')

@section('content')
    <section class="mx-auto max-w-4xl px-6 pb-16 pt-12">
        <h1 class="text-center text-2xl font-bold">Download Center</h1>
        <p class="mt-2 text-center text-sm text-[#1b1b1870]">{{ $total }} berkas tersedia untuk diunduh.</p>

        @forelse ($categories as $category => $items)
            <div class="mt-8">
                <h2 class="flex items-center gap-2 text-sm font-bold uppercase tracking-wide text-teal-800">
                    {{ $category }}
                    <span class="rounded-full bg-teal-100 px-2 py-0.5 text-xs font-semibold text-teal-700">{{ $items->count() }}</span>
                </h2>

                <div class="mt-3 space-y-3">
                    @foreach ($items as $item)
                        <div class="flex items-center gap-4 rounded-lg border border-teal-100 bg-white p-4 shadow-sm">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-md bg-teal-50 text-teal-700">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $item->title }}</h3>
                                @if ($item->description)
                                    <p class="mt-0.5 text-sm text-[#1b1b1870]">{{ $item->description }}</p>
                                @endif
                                <p class="mt-1 text-xs text-[#1b1b1870]">
                                    @if ($item->file)
                                        {{ $item->fileName() }} · {{ $item->fileSize() ?? '—' }}
                                    @else
                                        Link eksternal
                                    @endif
                                </p>
                            </div>
                            @if ($item->file)
                                <a href="{{ route('unduhan.unduh', $item) }}"
                                   class="shrink-0 rounded-md bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-800">
                                    Unduh
                                </a>
                            @else
                                <a href="{{ $item->external_url }}" target="_blank" rel="noopener noreferrer"
                                   class="shrink-0 rounded-md bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-800">
                                    Buka
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="mt-8 rounded-lg border border-teal-100 bg-white p-8 text-center text-sm text-[#1b1b1870]">
                Belum ada berkas yang tersedia.
            </div>
        @endforelse
    </section>
@endsection
