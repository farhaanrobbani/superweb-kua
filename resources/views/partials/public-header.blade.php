@php($navbarItems = kua_navbar())
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
        <nav class="hidden items-center gap-2 text-sm font-medium sm:flex">
            @foreach ($navbarItems as $item)
                @php($activeChildren = $item->has_submenu ? $item->children->where('active', true) : collect())
                @if ($item->has_submenu && $activeChildren->isNotEmpty())
                    <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button type="button" @click="open = !open"
                                class="inline-flex items-center gap-1 rounded-md px-3 py-1.5 text-teal-800 transition-colors duration-150 hover:bg-teal-50 hover:text-teal-700"
                                :aria-expanded="open">
                            {{ $item->label }}
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             x-cloak
                             class="absolute start-1/2 mt-2 w-56 -translate-x-1/2 rounded-lg border border-teal-100 bg-white p-2 shadow-lg">
                            @foreach ($activeChildren as $child)
                                <a href="{{ $child->url ? url($child->url) : '#' }}"
                                   class="flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium text-[#1b1b18] hover:bg-teal-50">
                                    @if ($child->icon)
                                        <span class="text-teal-700">@include('partials.service-icon', ['icon' => $child->icon])</span>
                                    @endif
                                    {{ $child->label }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @elseif (! $item->has_submenu)
                    <a href="{{ $item->url ? url($item->url) : '#' }}" class="rounded-md px-3 py-1.5 text-teal-800 transition-colors duration-150 hover:bg-teal-50 hover:text-teal-700">{{ $item->label }}</a>
                @endif
            @endforeach
        </nav>
    </div>
    <nav class="flex flex-wrap items-center gap-x-2 gap-y-1 border-t border-[#19140012] px-6 py-2 text-sm font-medium sm:hidden">
        @foreach ($navbarItems as $item)
            @php($activeChildren = $item->has_submenu ? $item->children->where('active', true) : collect())
            @if ($item->has_submenu && $activeChildren->isNotEmpty())
                <div class="w-full" x-data="{ open: false }">
                    <button type="button" @click="open = !open" class="inline-flex items-center gap-1 rounded-md px-3 py-1.5 text-teal-800 transition-colors duration-150 hover:bg-teal-50 hover:text-teal-700" :aria-expanded="open">
                        {{ $item->label }}
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         x-cloak
                         class="w-full border-t border-teal-100 pt-1">
                        @foreach ($activeChildren as $child)
                            <a href="{{ $child->url ? url($child->url) : '#' }}"
                               class="flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium text-[#1b1b18] hover:bg-teal-50">
                                @if ($child->icon)
                                    <span class="text-teal-700">@include('partials.service-icon', ['icon' => $child->icon])</span>
                                @endif
                                {{ $child->label }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @elseif (! $item->has_submenu)
                <a href="{{ $item->url ? url($item->url) : '#' }}" class="rounded-md px-3 py-1.5 text-teal-800 transition-colors duration-150 hover:bg-teal-50 hover:text-teal-700">{{ $item->label }}</a>
            @endif
        @endforeach
    </nav>
</header>
