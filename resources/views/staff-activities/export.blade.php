<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">Export Laporan Kinerja</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6"
             x-data="{
                 bulan: {{ $month }},
                 tahun: {{ $year }},
                 userId: '{{ $users->isNotEmpty() ? ($selectedUserId ?: '') : '' }}',
                 hasUsers: {{ $users->isNotEmpty() ? 'true' : 'false' }},
                 rekapPreviewUrl: '{{ route('kegiatan.export.rekap', ['bulan' => $month, 'tahun' => $year, 'total_hari_kerja' => 22, 'preview' => 1]) }}',
                 previewError: '',

                 get srcLaporan() {
                     if (this.hasUsers && ! this.userId) {
                         return '';
                     }
                     const p = new URLSearchParams({ bulan: this.bulan, tahun: this.tahun, preview: '1' });
                     if (this.hasUsers) {
                         p.set('user_id', this.userId);
                     }
                     return '{{ route('kegiatan.export.laporan') }}?' + p.toString();
                 },

                 rebuildRekapUrl() {
                     const p = new URLSearchParams({ bulan: this.bulan, tahun: this.tahun, preview: '1' });
                     if (this.hasUsers) {
                         p.set('user_id', this.userId);
                     }
                     const hari = document.getElementById('rekap-hari');
                     p.set('total_hari_kerja', (hari && hari.value) ? hari.value : '22');
                     const tanggal = document.getElementById('rekap-tanggal');
                     if (tanggal && tanggal.value.trim()) {
                         p.set('tanggal_ttd', tanggal.value.trim());
                     }
                     this.rekapPreviewUrl = '{{ route('kegiatan.export.rekap') }}?' + p.toString();
                 },

                 syncRekap() {
                     if (this.hasUsers && ! this.userId) {
                         this.previewError = 'Pilih pegawai terlebih dahulu untuk melihat preview rekap.';
                         return;
                     }
                     this.previewError = '';
                     [['rekap-bulan', this.bulan], ['rekap-tahun', this.tahun], ['rekap-user_id', this.userId]].forEach(([id, value]) => {
                         const el = document.getElementById(id);
                         if (el) {
                             el.value = value;
                         }
                     });
                     this.rebuildRekapUrl();
                     $dispatch('open-modal', 'export-rekap');
                 }
             }">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm dark:bg-green-900/30 dark:border-green-800 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm dark:bg-red-900/30 dark:border-red-800 dark:text-red-300">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800">
                <div class="flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-100">Export Laporan Kinerja &amp; Rekap</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            Pilih periode dan pegawai untuk membuat laporan kinerja dalam format PDF.
                        </p>
                    </div>
                </div>

                <form method="GET" action="{{ route('kegiatan.export.laporan') }}" class="px-6 py-5">
                    <div class="flex flex-wrap items-end gap-3">
                        <div>
                            <x-input-label for="filter-bulan" value="Bulan" />
                            <select name="bulan" id="filter-bulan" x-model="bulan"
                                    class="mt-1 border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" @selected($m === $month)>{{ tanggal_indonesia(now()->month($m), 'F') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="filter-tahun" value="Tahun" />
                            <input type="number" name="tahun" id="filter-tahun" value="{{ $year }}" min="2000" max="2100"
                                   @change="tahun = parseInt($el.value) || {{ $year }}"
                                   class="mt-1 block w-28 border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm" />
                        </div>
                        @if ($users->isNotEmpty())
                            <div>
                                <x-input-label for="filter-user_id" value="Pegawai" />
                                <select name="user_id" id="filter-user_id" x-model="userId" required
                                        @change="previewError = ''"
                                        class="mt-1 border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">
                                    <option value="">Pilih pegawai (wajib)</option>
                                    @foreach ($users as $u)
                                        <option value="{{ $u->id }}" @selected($selectedUserId === $u->id)>{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-500 focus:bg-teal-500 active:bg-teal-700 transition ease-in-out duration-150">
                            Export Laporan Kinerja
                        </button>
                        <button type="button" @click="syncRekap()"
                                class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-500 focus:bg-teal-500 active:bg-teal-700 transition ease-in-out duration-150">
                            Export Rekap
                        </button>
                    </div>
                    <p x-show="previewError" x-cloak class="mt-3 text-xs text-red-600 dark:text-red-400" x-text="previewError"></p>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800">
                <div class="flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-100">Preview PDF</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            Preview Laporan Kinerja akan diperbarui otomatis mengikuti pilihan periode dan pegawai.
                        </p>
                    </div>
                </div>
                <div class="p-6">
                    <iframe x-show="srcLaporan" :src="srcLaporan" title="Preview laporan kinerja"
                            class="w-full rounded-md border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800"
                            style="border:0; height:70vh; min-height:500px;"></iframe>
                    <div x-show="! srcLaporan" x-cloak
                         class="flex items-center justify-center rounded-md border border-dashed border-gray-300 dark:border-gray-600"
                         style="height:70vh; min-height:500px;">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pilih pegawai terlebih dahulu untuk melihat preview.</p>
                    </div>
                </div>
            </div>

            <x-modal name="export-rekap" maxWidth="2xl">
                <form method="GET" action="{{ route('kegiatan.export.rekap') }}">
                    <input type="hidden" name="bulan" id="rekap-bulan" value="{{ $month }}" />
                    <input type="hidden" name="tahun" id="rekap-tahun" value="{{ $year }}" />
                    @if ($users->isNotEmpty())
                        <input type="hidden" name="user_id" id="rekap-user_id" value="{{ $selectedUserId ?? 0 }}" />
                    @endif
                    <div class="px-6 py-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-100">Export Rekap Laporan Kinerja</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    Isi jumlah kehadiran pegawai pada bulan {{ tanggal_indonesia(now()->month($month), 'F') }} {{ $year }}.
                                </p>
                            </div>
                            <button type="button" @click="$dispatch('close-modal', 'export-rekap')"
                                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl leading-none">&times;</button>
                        </div>

                        <iframe :src="rekapPreviewUrl" title="Preview rekap laporan kinerja"
                                class="mt-4 w-full rounded-md border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800"
                                style="border:0; height:55vh; min-height:400px;"></iframe>

                        <div class="mt-4">
                            <x-input-label value="Jumlah Kehadiran (Hari)" />
                            <input type="number" name="total_hari_kerja" id="rekap-hari" value="22" min="0" max="31" required
                                   @input.debounce.500ms="rebuildRekapUrl()"
                                   class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm" />
                        </div>

                        <div class="mt-4">
                            <x-input-label value="Tanggal Tanda Tangan (opsional)" />
                            <input type="text" name="tanggal_ttd" id="rekap-tanggal" placeholder="31 Agustus 2026"
                                   maxlength="100"
                                   @input.debounce.500ms="rebuildRekapUrl()"
                                   class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm" />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Kosongkan untuk memakai tanggal terakhir bulan. Kota tetap otomatis dari pengaturan.
                            </p>
                        </div>

                        <div class="mt-4 flex items-center justify-end gap-3">
                            <button type="button" @click="$dispatch('close-modal', 'export-rekap')"
                                    class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Batal</button>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-500 focus:bg-teal-500 active:bg-teal-700 transition ease-in-out duration-150">
                                Export PDF
                            </button>
                        </div>
                    </div>
                </form>
            </x-modal>
        </div>
    </div>
</x-app-layout>
