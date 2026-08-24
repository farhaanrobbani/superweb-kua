@if ($videos->isNotEmpty())
    <section class="mx-auto max-w-5xl px-6 pt-12 pb-8">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold">Video</h2>
            <a href="{{ kua_navbar_page_url('video') }}" class="text-sm font-medium text-teal-700 hover:underline">Lihat Semua Video</a>
        </div>
        <div x-data="videoCarousel()" class="relative mt-6">
            <div x-ref="track" class="flex snap-x snap-mandatory gap-4 overflow-x-auto pb-3 scroll-smooth">
                @foreach ($videos as $video)
                    <a href="{{ kua_navbar_page_url('video').'/'.$video->slug }}"
                       class="group relative w-64 shrink-0 snap-start overflow-hidden rounded-xl border border-teal-100 bg-white shadow-sm transition hover:shadow-md">
                        <div class="relative flex h-36 items-center justify-center bg-teal-900">
                            @if ($video->thumbnailUrl())
                                <img src="{{ $video->thumbnailUrl() }}" alt="{{ $video->title }}" class="h-full w-full object-cover transition group-hover:scale-105" />
                            @endif
                            <span class="absolute inset-0 flex items-center justify-center bg-black/20">
                                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-white/90 text-xl text-teal-700">▶</span>
                            </span>
                        </div>
                        <p class="p-4 text-sm font-semibold leading-snug text-slate-900 group-hover:text-teal-700">{{ $video->title }}</p>
                    </a>
                @endforeach
            </div>
            <button type="button" @click="scrollBy(-280)"
                    class="absolute left-0 top-1/2 hidden -translate-y-1/2 rounded-full border border-teal-100 bg-white p-2 shadow md:block" aria-label="Sebelumnya">‹</button>
            <button type="button" @click="scrollBy(280)"
                    class="absolute right-0 top-1/2 hidden -translate-y-1/2 rounded-full border border-teal-100 bg-white p-2 shadow md:block" aria-label="Selanjutnya">›</button>
        </div>
    </section>
    <script>
        function videoCarousel() {
            return {
                scrollBy(offset) {
                    this.$refs.track.scrollBy({ left: offset, behavior: 'smooth' });
                }
            };
        }
    </script>
@endif
