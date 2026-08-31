<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">Export Laporan Kinerja</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
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

            <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800"
                 x-data="{
                     syncRekap() {
                         ['bulan', 'tahun', 'user_id'].forEach((key) => {
                             const source = document.getElementById('filter-' + key);
                             const target = document.getElementById('rekap-' + key);
                             if (source && target) {
                                 target.value = source.value;
                             }
                         });
                         $dispatch('open-modal', 'export-rekap');
                     },
                     syncLaporan() {
                         ['bulan', 'tahun', 'user_id'].forEach((key) => {
                             const source = document.getElementById('filter-' + key);
                             const target = document.getElementById('laporan-' + key);
                             if (source && target) {
                                 target.value = source.value;
                             }
                         });
                         $dispatch('open-modal', 'export-laporan');
                     }
                 }">
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
                            <select name="bulan" id="filter-bulan"
                                    class="mt-1 border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" @selected($m === $month)>{{ tanggal_indonesia(now()->startOfMonth()->month($m), 'F') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="filter-tahun" value="Tahun" />
                            <input type="number" name="tahun" id="filter-tahun" value="{{ $year }}" min="2000" max="2100"
                                   class="mt-1 block w-28 border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm" />
                        </div>
                        @if ($users->isNotEmpty())
                            <div>
                                <x-input-label for="filter-user_id" value="Pegawai" />
                                <select name="user_id" id="filter-user_id" required
                                        class="mt-1 border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">
                                    <option value="">Pilih pegawai (wajib)</option>
                                    @foreach ($users as $u)
                                        <option value="{{ $u->id }}" @selected($selectedUserId === $u->id)>{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <button type="button" @click="syncLaporan()"
                                class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-500 focus:bg-teal-500 active:bg-teal-700 transition ease-in-out duration-150">
                            Export Laporan Kinerja
                        </button>
                        <button type="button" @click="syncRekap()"
                                class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-500 focus:bg-teal-500 active:bg-teal-700 transition ease-in-out duration-150">
                            Export Rekap
                        </button>
                    </div>
                </form>
            </div>

            <x-modal name="export-rekap" maxWidth="md">
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
                                    Isi jumlah kehadiran pegawai pada bulan {{ tanggal_indonesia(now()->startOfMonth()->month($month), 'F') }} {{ $year }}.
                                </p>
                            </div>
                            <button type="button" @click="$dispatch('close-modal', 'export-rekap')"
                                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl leading-none">&times;</button>
                        </div>

                        <div class="mt-4">
                            <x-input-label value="Jumlah Kehadiran (Hari)" />
                            <input type="number" name="total_hari_kerja" value="22" min="0" max="31" required
                                   class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm" />
                        </div>

                        <div class="mt-4">
                            <x-input-label value="Tanggal Tanda Tangan (opsional)" />
                            <input type="text" name="tanggal_ttd" placeholder="31 Agustus 2026"
                                   maxlength="100"
                                   class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm" />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Kosongkan untuk memakai tanggal terakhir bulan. Kota tetap otomatis dari pengaturan.
                            </p>
                        </div>

                        <div class="mt-4 flex items-center justify-end gap-3">
                            <button type="button" @click="$dispatch('close-modal', 'export-rekap')"
                                    class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Batal</button>
                            <button type="submit" name="format" value="word"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-500 active:bg-blue-700 transition ease-in-out duration-150">
                                Export Word
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-500 focus:bg-teal-500 active:bg-teal-700 transition ease-in-out duration-150">
                                Export PDF
                            </button>
                        </div>
                    </div>
                </form>
            </x-modal>

            <x-modal name="export-laporan" maxWidth="md">
                <form method="GET" action="{{ route('kegiatan.export.laporan') }}">
                    <input type="hidden" name="bulan" id="laporan-bulan" value="{{ $month }}" />
                    <input type="hidden" name="tahun" id="laporan-tahun" value="{{ $year }}" />
                    @if ($users->isNotEmpty())
                        <input type="hidden" name="user_id" id="laporan-user_id" value="{{ $selectedUserId ?? 0 }}" />
                    @endif
                    <div class="px-6 py-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-100">Export Laporan Kinerja</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    Pilih format file laporan kinerja bulan {{ tanggal_indonesia(now()->startOfMonth()->month($month), 'F') }} {{ $year }}.
                                </p>
                            </div>
                            <button type="button" @click="$dispatch('close-modal', 'export-laporan')"
                                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl leading-none">&times;</button>
                        </div>

                        <div class="mt-4">
                            <x-input-label value="Tanggal Dicetak (opsional)" />
                            <input type="text" name="tanggal_ttd" placeholder="31 Agustus 2026"
                                   maxlength="100"
                                   class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm" />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Kosongkan untuk memakai tanggal terakhir bulan.
                            </p>
                        </div>

                        <div class="mt-4 flex items-center justify-end gap-3">
                            <button type="button" @click="$dispatch('close-modal', 'export-laporan')"
                                    class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Batal</button>
                            <button type="submit" name="format" value="word"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-500 active:bg-blue-700 transition ease-in-out duration-150">
                                Export Word
                            </button>
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
