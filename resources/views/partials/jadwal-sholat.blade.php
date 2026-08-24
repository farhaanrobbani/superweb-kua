<section x-data="jadwalSholat()" x-init="init()" class="mx-auto max-w-5xl px-6 py-10">
    <div class="relative overflow-hidden rounded-xl border border-teal-800 bg-teal-800 text-teal-50 shadow-sm">
        <img src="https://images.unsplash.com/photo-1587613865763-4b8b0d19e8ab?q=80&w=1200&auto=format&fit=crop" alt="" class="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-[0.07]" aria-hidden="true">
        <div class="absolute inset-0 bg-teal-800/80" aria-hidden="true"></div>
        <div class="relative">
            <div class="px-6 pb-6 pt-7 text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-teal-200/90">Jadwal Sholat</p>
                <p class="mt-1 text-xs font-medium tracking-wide text-teal-100/80" x-text="'Wilayah ' + kotaLabel + ' • Ampelgading'"></p>
            </div>
            <div class="px-6 pb-2">
                <p class="text-xs text-teal-100/70" x-show="loading">Memuat jadwal...</p>
                <p class="text-xs text-red-200" x-show="error" x-text="error"></p>
                <div x-show="!loading && timings" class="overflow-hidden rounded-lg border border-white/15">
                    <div class="grid grid-cols-[2.5rem_1fr_auto] gap-0 border-b border-white/10 bg-white/10 px-4 py-2 text-[11px] font-semibold uppercase tracking-wide text-teal-100">
                        <span>No</span><span>Nama Waktu</span><span class="text-right">Waktu</span>
                    </div>
                    <template x-for="(item, idx) in displayTimings" :key="item.key">
                        <div class="grid grid-cols-[2.5rem_1fr_auto] items-center gap-0 border-b border-white/10 px-4 py-3 text-sm last:border-0" :class="item.isNext ? 'bg-white/10 font-semibold text-white' : 'text-teal-50'">
                            <span class="text-teal-200/80" x-text="idx + 1"></span>
                            <span x-text="item.label"></span>
                            <span class="font-mono font-medium" x-text="item.time"></span>
                        </div>
                    </template>
                </div>
                <p class="mt-3 text-center text-xs text-teal-100/70" x-show="countdown" x-text="'Selanjutnya: ' + countdown"></p>
                <div class="mt-4 flex flex-wrap items-center justify-center gap-2">
                    <button type="button" @click="useGeolocation()" class="rounded-md border border-white/20 bg-white/10 px-4 py-1.5 text-xs font-semibold text-white backdrop-blur hover:bg-white/20">Lokasi Saya</button>
                    <a href="https://bimasislam.kemenag.go.id/jadwalshalat" target="_blank" rel="noopener noreferrer" class="text-xs text-teal-100/70 underline decoration-white/20 underline-offset-4 hover:text-white">bimasislam.kemenag.go.id/jadwalshalat →</a>
                </div>
            </div>
        </div>
    </div>

    <script>
    function jadwalSholat() {
        return {
            kotaLabel: 'Ampelgading',
            timings: null,
            loading: true,
            error: '',
            countdown: '',
            displayTimings: [],
            timer: null,
            init() { this.fetchByCoords(-7.97, 112.63); },
            useGeolocation() {
                if (!navigator.geolocation) { this.error = 'Geolocation tidak didukung.'; return; }
                this.loading = true;
                navigator.geolocation.getCurrentPosition(pos => {
                    const lat = pos.coords.latitude, lon = pos.coords.longitude;
                    this.kotaLabel = 'Lokasi Saya';
                    this.fetchByCoords(lat, lon);
                }, () => { this.error = 'Gagal mengambil lokasi.'; this.loading = false; });
            },
            fetchByCoords(lat, lon) {
                this.loading = true; this.error = ''; this.countdown = '';
                const d = new Date();
                const date = String(d.getDate()).padStart(2,'0')+'-'+String(d.getMonth()+1).padStart(2,'0')+'-'+d.getFullYear();
                fetch(`https://api.aladhan.com/v1/timings/${date}?latitude=${lat}&longitude=${lon}&method=20`)
                    .then(r => r.json()).then(j => {
                        if (j.code !== 200 || !j.data || !j.data.timings) throw new Error('Gagal memuat jadwal.');
                        this.timings = j.data.timings; this.buildDisplay(); this.loading = false;
                    }).catch(e => { this.error = e.message || 'Gagal memuat jadwal.'; this.loading = false; });
            },
            buildDisplay() {
                const order = [{ key: 'Fajr', label: 'Subuh' },{ key: 'Dhuhr', label: 'Dzuhur' },{ key: 'Asr', label: 'Ashar' },{ key: 'Maghrib', label: 'Maghrib' },{ key: 'Isha', label: 'Isya' }];
                const now = new Date(); const nowMin = now.getHours()*60 + now.getMinutes();
                let nextIdx = -1, minDiff = Infinity;
                const items = order.map((o, idx) => {
                    const t = this.timings[o.key] || '--:--'; const [h, m] = t.split(':').map(Number);
                    const mins = (isNaN(h)?9999:h*60+(isNaN(m)?0:m)); const diff = mins - nowMin;
                    if (diff >= 0 && diff < minDiff) { minDiff = diff; nextIdx = idx; }
                    return { key: o.key, label: o.label, time: t, mins, isNext: false };
                });
                if (nextIdx === -1) nextIdx = 0; items[nextIdx].isNext = true;
                this.displayTimings = items; this.updateCountdown(nextIdx, items);
                if (this.timer) clearInterval(this.timer); this.timer = setInterval(() => this.updateCountdown(nextIdx, items), 60000);
            },
            updateCountdown(nextIdx, items) {
                const now = new Date(); const nowMin = now.getHours()*60 + now.getMinutes();
                const next = items[nextIdx]; let diff = next.mins - nowMin; if (diff < 0) diff += 24*60;
                const h = Math.floor(diff/60), m = diff%60; this.countdown = `${next.label} dalam ${h} j ${m} m`;
            }
        }
    }
    </script>
</section>
