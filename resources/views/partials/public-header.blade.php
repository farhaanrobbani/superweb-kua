@php($headerServices = $services ?? kua_services())
<header class="sticky top-0 z-10 border-b border-[#19140012] bg-white/90 backdrop-blur">
    <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-3">
        <div class="flex items-center gap-2">
            @if (\App\Models\KuaSetting::logoUrl())
                <img src="{{ \App\Models\KuaSetting::logoUrl() }}" alt="Logo {{ kua_setting('instansi', 'KUA') }}"
                     class="h-9 w-9 rounded-md object-contain" />
            @else
                <div class="flex h-8 w-8 items-center justify-center rounded-md bg-teal-700 text-sm font-bold text-white">K</div>
            @endif
            <span class="text-sm font-semibold tracking-wide">{{ kua_setting('instansi', 'Surat Digital KUA') }}</span>
        </div>
        <nav x-data="{ layanan: false }" @click.outside="layanan = false" class="hidden items-center gap-2 text-sm font-medium sm:flex">
            <a href="{{ url('/') }}" class="rounded-md px-3 py-1.5 text-teal-800 transition-colors duration-150 hover:bg-teal-50 hover:text-teal-700">Beranda</a>
            <div class="relative" @mouseenter="layanan = true" @mouseleave="layanan = false">
                <button type="button" @click="layanan = !layanan"
                        class="inline-flex items-center gap-1 rounded-md px-3 py-1.5 text-teal-800 transition-colors duration-150 hover:bg-teal-50 hover:text-teal-700"
                        :aria-expanded="layanan">
                    Layanan
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="layanan"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     x-cloak
                     class="absolute start-1/2 mt-2 w-72 -translate-x-1/2 rounded-lg border border-teal-100 bg-white p-2 shadow-lg">
                    @forelse ($headerServices as $service)
                        <a href="{{ $service->url ? url($service->url) : '#' }}"
                           class="flex items-start gap-3 rounded-md px-3 py-2.5 hover:bg-teal-50">
                            <span class="mt-0.5 text-teal-700">@include('partials.service-icon', ['icon' => $service->icon])</span>
                            <span>
                                <span class="block text-sm font-semibold text-[#1b1b18]">{{ $service->name }}</span>
                                @if ($service->description)
                                    <span class="block text-xs leading-relaxed text-[#1b1b1870]">{{ $service->description }}</span>
                                @endif
                            </span>
                        </a>
                    @empty
                        <span class="block px-3 py-2 text-sm text-[#1b1b1870]">Belum ada layanan.</span>
                    @endforelse
                </div>
            </div>
            <a href="{{ route('pengumuman.index') }}" class="rounded-md px-3 py-1.5 text-teal-800 transition-colors duration-150 hover:bg-teal-50 hover:text-teal-700">Pengumuman</a>
        </nav>
        <a href="{{ route('permohonan.create') }}"
           class="rounded-md bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-800 sm:hidden">
            Ajukan Surat
        </a>
    </div>
    <nav x-data="{ layanan: false }" class="flex flex-wrap items-center gap-x-2 gap-y-1 border-t border-[#19140012] px-6 py-2 text-sm font-medium sm:hidden">
        <a href="{{ url('/') }}" class="rounded-md px-3 py-1.5 text-teal-800 transition-colors duration-150 hover:bg-teal-50 hover:text-teal-700">Beranda</a>
        <button type="button" @click="layanan = !layanan" class="inline-flex items-center gap-1 rounded-md px-3 py-1.5 text-teal-800 transition-colors duration-150 hover:bg-teal-50 hover:text-teal-700" :aria-expanded="layanan">
            Layanan
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        <a href="{{ route('pengumuman.index') }}" class="rounded-md px-3 py-1.5 text-teal-800 transition-colors duration-150 hover:bg-teal-50 hover:text-teal-700">Pengumuman</a>
        @if ($headerServices->isNotEmpty())
            <div x-show="layanan"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 x-cloak
                 class="w-full border-t border-teal-100 pt-1">
                @foreach ($headerServices as $service)
                    <a href="{{ $service->url ? url($service->url) : '#' }}"
                       class="flex items-start gap-3 rounded-md px-3 py-2.5 hover:bg-teal-50">
                        <span class="mt-0.5 text-teal-700">@include('partials.service-icon', ['icon' => $service->icon])</span>
                        <span>
                            <span class="block text-sm font-semibold text-[#1b1b18]">{{ $service->name }}</span>
                            @if ($service->description)
                                <span class="block text-xs leading-relaxed text-[#1b1b1870]">{{ $service->description }}</span>
                            @endif
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </nav>
    <nav class="border-t border-teal-100 bg-teal-700">
        <div class="mx-auto flex max-w-5xl flex-wrap items-center gap-2 px-6 py-2 text-sm font-medium">
            <a href="{{ route('pegawai.index') }}"
               class="rounded-md px-3 py-1.5 text-teal-50 transition-colors duration-150 hover:bg-teal-600 hover:text-white">Daftar Pegawai</a>
            <a href="{{ route('kritik-saran.create') }}"
               class="rounded-md px-3 py-1.5 text-teal-50 transition-colors duration-150 hover:bg-teal-600 hover:text-white">Kritik & Saran</a>
        </div>
    </nav>
</header>
