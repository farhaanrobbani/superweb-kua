@extends('layouts.public')

@section('title', kua_setting('instansi', 'Surat Digital KUA').' — '.kua_navbar_page_label('wakaf', $page->title ?? 'Layanan Wakaf'))

@section('content')
    <section class="mx-auto max-w-4xl px-6 pb-16 pt-12">
        <h1 class="text-center text-2xl font-bold">{{ $page->title ?? 'Layanan Wakaf' }}</h1>
        <p class="mx-auto mt-2 max-w-2xl text-center text-sm text-[#1b1b1870]">
            {{ $page->description ?? 'Pilih topik di bawah untuk melihat persyaratan, alur, dan prosedur layanan wakaf di KUA.' }}
        </p>

        <div class="mt-8 space-y-4">
            @forelse ($wakafServices as $service)
                <div class="overflow-hidden rounded-lg border border-teal-100 bg-white shadow-sm" x-data="{ open: false }">
                    <button type="button" @click="open = !open"
                            class="flex w-full items-center gap-4 px-5 py-4 text-start transition hover:bg-teal-50/50">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-md bg-teal-50 text-teal-700">
                            @include('partials.service-icon', ['icon' => $service->icon])
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block font-semibold text-teal-900">{{ $service->name }}</span>
                            @if ($service->description)
                                <span class="block text-sm text-[#1b1b1870]">{{ $service->description }}</span>
                            @endif
                        </span>
                        <svg class="h-5 w-5 shrink-0 text-teal-600 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="border-t border-teal-100 px-5 pt-5 pb-7 sm:px-6">
                        @if (trim((string) $service->persyaratan) !== '')
                            <div class="mb-5">
                                <h3 class="text-xs font-bold uppercase tracking-wide text-teal-800">{{ $service->persyaratan_label ?: 'Persyaratan' }}</h3>
                                <div class="konten-pengumuman mt-2 text-sm text-[#1b1b18]">
                                    {!! \App\Support\HtmlSanitizer::normalize($service->persyaratan) !!}
                                </div>
                            </div>
                        @endif

                        @if (trim((string) $service->alur) !== '')
                            <div class="mb-5">
                                <h3 class="text-xs font-bold uppercase tracking-wide text-teal-800">{{ $service->alur_label ?: 'Alur' }}</h3>
                                <div class="konten-pengumuman mt-2 text-sm text-[#1b1b18]">
                                    {!! \App\Support\HtmlSanitizer::normalize($service->alur) !!}
                                </div>
                            </div>
                        @endif

                        @if (trim((string) $service->sop) !== '')
                            <div class="mb-5">
                                <h3 class="text-xs font-bold uppercase tracking-wide text-teal-800">{{ $service->sop_label ?: 'SOP' }}</h3>
                                <div class="konten-pengumuman mt-2 text-sm text-[#1b1b18]">
                                    {!! \App\Support\HtmlSanitizer::normalize($service->sop) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-lg border border-teal-100 bg-white p-8 text-center text-sm text-[#1b1b1870]">
                    Belum ada layanan wakaf yang tersedia.
                </div>
            @endforelse
        </div>
    </section>
@endsection
