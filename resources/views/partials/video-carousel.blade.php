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
                       class="group relative aspect-[9/16] w-[35%] shrink-0 snap-start overflow-hidden bg-base-300 md:w-[11%]">
                        <div class="relative h-full w-full overflow-hidden">
                            @if ($video->thumbnailUrl())
                                <img src="{{ $video->thumbnailUrl() }}" alt="{{ $video->title }}"
                                     class="absolute inset-0 h-full w-full object-cover transition-transform duration-300 group-hover:opacity-90" />
                            @endif
                            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="size-12 text-white opacity-60 group-hover:opacity-100"><circle cx="12" cy="12" r="10" fill="white" opacity="0.3"></circle><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"></path></svg>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 h-3/4 bg-gradient-to-t from-black/90 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-3">
                                <p class="line-clamp-2 text-sm font-medium text-white group-hover:text-primary-300">{{ $video->title }}</p>
                            </div>
                        </div>
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
