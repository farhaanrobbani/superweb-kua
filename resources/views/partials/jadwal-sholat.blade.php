<section x-data="jadwalSholat()" x-init="init()" class="mx-auto max-w-5xl px-6 py-10">
    <div class="rounded-lg border border-teal-100 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-teal-100 bg-teal-50/60 px-5 py-3">
            <div class="flex items-center gap-2">
                <p class="text-xs font-semibold uppercase tracking-wide text-teal-800">Jadwal Sholat</p>
                <span class="rounded-full bg-teal-700 px-2 py-0.5 text-xs font-semibold text-white" x-text="kotaLabel"></span>
            </div>
            <button type="button" @click="useGeolocation()"
                    class="shrink-0 rounded-md border border-teal-200 bg-white px-3 py-1.5 text-xs font-semibold text-teal-700 transition hover:bg-teal-50">Lokasi Saya</button>
        </div>
        <div x-show="countdown" class="px-5 pt-3">
            <p class="text-xs text-[#1b1b1870]" x-text="'• ' + countdown"></p>
        </div>

        <div class="px-5 py-6">
            <p class="text-xs text-[#1b1b1870]" x-show="loading">Memuat jadwal...</p>
            <p class="text-xs text-red-600" x-show="error" x-text="error"></p>

            <div x-show="!loading && timings" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <template x-for="item in displayTimings" :key="item.key">
                    <div class="flex items-center gap-3 rounded-lg border border-teal-50 bg-teal-50/40 p-4 transition hover:border-teal-200 hover:bg-teal-50/70"
                         :class="item.isNext ? 'border-teal-700 bg-teal-50' : ''">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-md bg-teal-700 text-white">
                            <span class="text-xs font-bold leading-none sm:text-sm" x-text="item.time"></span>
                        </div>
                        <div class="min-w-0 text-left">
                            <p class="truncate text-sm font-semibold text-teal-900" x-text="item.label + ' — ' + item.time"></p>
                            <p class="text-xs text-[#1b1b1870]" x-show="item.isNext">Selanjutnya</p>
                        </div>
                    </div>
                </template>
            </div>

            <div class="mt-3 text-center">
                <a href="https://bimasislam.kemenag.go.id/jadwalshalat" target="_blank" rel="noopener noreferrer"
                   class="text-xs text-teal-700 hover:underline">Lihat selengkapnya di bimasislam.kemenag.go.id/jadwalshalat →</a>
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
            init() {
                this.fetchByCoords(-7.97, 112.63);
            },
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
                    .then(r => r.json())
                    .then(j => {
                        if (j.code !== 200 || !j.data || !j.data.timings) throw new Error('Gagal memuat jadwal.');
                        this.timings = j.data.timings;
                        this.buildDisplay();
                        this.loading = false;
                    })
                    .catch(e => { this.error = e.message || 'Gagal memuat jadwal.'; this.loading = false; });
            },
            buildDisplay() {
                const order = [
                    { key: 'Fajr', label: 'Subuh' },
                    { key: 'Dhuhr', label: 'Dzuhur' },
                    { key: 'Asr', label: 'Ashar' },
                    { key: 'Maghrib', label: 'Maghrib' },
                    { key: 'Isha', label: 'Isya' },
                ];
                const now = new Date();
                const nowMin = now.getHours()*60 + now.getMinutes();
                let nextIdx = -1, minDiff = Infinity;
                const items = order.map((o, idx) => {
                    const t = this.timings[o.key] || '--:--';
                    const [h, m] = t.split(':').map(Number);
                    const mins = (isNaN(h)?9999:h*60+(isNaN(m)?0:m));
                    const diff = mins - nowMin;
                    if (diff >= 0 && diff < minDiff) { minDiff = diff; nextIdx = idx; }
                    return { key: o.key, label: o.label, time: t, mins, isNext: false };
                });
                if (nextIdx === -1) nextIdx = 0;
                items[nextIdx].isNext = true;
                this.displayTimings = items;
                this.updateCountdown(nextIdx, items);
                if (this.timer) clearInterval(this.timer);
                this.timer = setInterval(() => this.updateCountdown(nextIdx, items), 60000);
            },
            updateCountdown(nextIdx, items) {
                const now = new Date();
                const nowMin = now.getHours()*60 + now.getMinutes();
                const next = items[nextIdx];
                let diff = next.mins - nowMin;
                if (diff < 0) diff += 24*60;
                const h = Math.floor(diff/60), m = diff%60;
                this.countdown = `${next.label} dalam ${h} j ${m} m`;
            }
        }
    }
    </script>
</section>
