<section x-data="jadwalSholat()" x-init="init()" class="bg-teal-900/95">
    <div class="mx-auto max-w-5xl px-6 py-10">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-teal-200/80">Jadwal Sholat</p>
                <h2 class="mt-1 text-xl font-bold text-white">Kecamatan <span x-text="kotaLabel"></span></h2>
                <p class="mt-1 text-xs text-teal-100/70" x-show="hijriLabel" x-text="hijriLabel"></p>
                <p class="mt-2 flex items-center gap-1.5 text-sm font-medium text-teal-100" x-show="countdown">
                    <span class="h-2 w-2 animate-pulse rounded-full bg-emerald-300"></span>
                    <span x-text="countdown"></span>
                </p>
            </div>
            <div class="flex items-center gap-2">
                <select x-model="kotaKey" @change="onKotaChange()"
                        class="rounded-md border border-white/20 bg-white px-3 py-1.5 text-xs font-medium text-teal-900 focus:border-teal-500 focus:ring-teal-500">
                    <template x-for="(v, k) in kotalist" :key="k">
                        <option :value="k" x-text="v.label"></option>
                    </template>
                </select>
                <button type="button" @click="useGeolocation()"
                        class="rounded-md bg-white/10 px-3 py-1.5 text-xs font-semibold text-teal-100 transition hover:bg-white/20">Lokasi Saya</button>
            </div>
        </div>

        <p class="mt-4 text-xs text-teal-200/70" x-show="loading">Memuat jadwal...</p>
        <p class="mt-4 text-xs text-red-200" x-show="error" x-text="error"></p>

        <div x-show="!loading && timings" class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
            <template x-for="item in displayTimings" :key="item.key">
                <div class="relative rounded-xl border-2 p-4 text-center shadow-sm transition"
                     :class="item.isNext ? 'border-emerald-300 bg-emerald-400 text-teal-950 ring-2 ring-emerald-300/50' : 'border-white/15 bg-white/10 text-white backdrop-blur'">
                    <p class="text-[11px] font-semibold uppercase tracking-widest" :class="item.isNext ? 'text-teal-900' : 'text-teal-100/70'" x-text="item.label"></p>
                    <p class="mt-1 text-xl font-bold tabular-nums" x-text="item.time"></p>
                    <span x-show="item.isNext" class="absolute -top-2 left-1/2 -translate-x-1/2 rounded-full bg-emerald-400 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-teal-950">Selanjutnya</span>
                </div>
            </template>
        </div>

        <div class="mt-6 flex justify-center">
            <a href="https://bimasislam.kemenag.go.id/jadwalshalat" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-1.5 text-xs font-medium text-teal-100/70 hover:text-teal-100">Lihat selengkapnya di bimasislam.kemenag.go.id/jadwalshalat <span aria-hidden="true">→</span></a>
        </div>
    </div>

    <script>
    function jadwalSholat() {
        return {
            kotaKey: localStorage.getItem('jadwal_sholat_kota') || 'ampelgading',
            kotaLabel: 'Ampelgading',
            hijriLabel: '',
            kotalist: {
                ampelgading: { label: 'Ampelgading', lat: -7.97, lon: 112.63 },
                malang: { label: 'Malang', lat: -7.98, lon: 112.63 },
                surabaya: { label: 'Surabaya', lat: -7.25, lon: 112.75 },
                jakarta: { label: 'Jakarta', lat: -6.20, lon: 106.84 },
                pasuruan: { label: 'Pasuruan', lat: -7.64, lon: 112.90 },
            },
            timings: null,
            loading: true,
            error: '',
            countdown: '',
            displayTimings: [],
            timer: null,
            init() {
                const k = this.kotalist[this.kotaKey];
                this.kotaLabel = k ? k.label : 'Ampelgading';
                this.fetchTimings();
            },
            onKotaChange() {
                localStorage.setItem('jadwal_sholat_kota', this.kotaKey);
                const k = this.kotalist[this.kotaKey];
                this.kotaLabel = k.label;
                this.fetchTimings();
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
            fetchTimings() {
                const k = this.kotalist[this.kotaKey];
                if (!k) return;
                this.fetchByCoords(k.lat, k.lon);
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
                        const h = j.data.date && j.data.date.hijri;
                        this.hijriLabel = h ? `${h.day} ${h.month.en} ${h.year} H` : '';
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
