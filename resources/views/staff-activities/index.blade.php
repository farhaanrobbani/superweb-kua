<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">Kegiatan Harian</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm dark:bg-green-900/30 dark:border-green-800 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            <form method="GET" action="{{ route('kegiatan.index') }}" class="flex flex-wrap items-end gap-3">
                <div>
                    <x-input-label for="bulan" value="Bulan" />
                    <select name="bulan" id="bulan"
                            class="mt-1 border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}" @selected($m === $month)>{{ tanggal_indonesia(now()->month($m), 'F') }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="tahun" value="Tahun" />
                    <input type="number" name="tahun" id="tahun" value="{{ $year }}" min="2000" max="2100"
                           class="mt-1 block w-28 border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm" />
                </div>
                @if ($users->isNotEmpty())
                    <div>
                        <x-input-label for="user_id" value="Pegawai" />
                        <select name="user_id" id="user_id"
                                class="mt-1 border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">
                            <option value="0">Semua pegawai</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}" @selected($selectedUserId === $u->id)>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <x-primary-button>Tampilkan</x-primary-button>
            </form>

            <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800"
                 x-data="{
                     tanggal: '{{ now()->format('Y-m-d') }}',
                     rows: [{ key: '', kegiatan: '', pekerjaan: '', jumlah: '', save_template: false }],
                     templates: @js($templates),
                     daily: @js($dailyMap),
                     addRow() { this.rows.push({ key: '', kegiatan: '', pekerjaan: '', jumlah: '', save_template: false }); },
                     removeRow(i) { this.rows.splice(i, 1); },
                     onKeyChange(row) {
                         const t = this.templates[row.key];
                         if (t) {
                             if (! row.kegiatan) row.kegiatan = t.kegiatan;
                             if (! row.pekerjaan) row.pekerjaan = t.pekerjaan;
                         }
                         this.syncJumlah(row);
                     },
                     syncJumlah(row) {
                         const d = this.daily[this.tanggal];
                         if (row.key && d && d[row.key] !== undefined) row.jumlah = d[row.key];
                     }
                 }">
                <div class="border-b border-gray-100 dark:border-gray-700 px-6 py-4">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Input Kegiatan</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        Jumlah otomatis terisi dari Master Data Harian jika jenis kegiatan dipilih. Centang
                        <span class="font-medium">Template</span> untuk menyimpan kalimat sebagai template pribadi.
                    </p>
                </div>
                <form method="POST" action="{{ route('kegiatan.store') }}" class="px-6 py-4 space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="tanggal" value="Tanggal" />
                        <input type="date" x-model="tanggal" id="tanggal" required
                               class="mt-1 block w-full sm:w-56 border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm" />
                    </div>

                    <div class="space-y-3">
                        <template x-for="(row, i) in rows" :key="i">
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 space-y-3">
                                <div class="grid grid-cols-1 gap-3 lg:grid-cols-12">
                                    <div class="lg:col-span-3">
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Jenis Kegiatan</label>
                                        <select x-model="row.key" @change="onKeyChange(row)"
                                                :name="'items[' + i + '][activity_type_key]'"
                                                class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">
                                            <option value="">— Lainnya —</option>
                                            @foreach ($columns as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="lg:col-span-4">
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Kegiatan</label>
                                        <textarea x-model="row.kegiatan" :name="'items[' + i + '][kegiatan]'" rows="2" required
                                                  placeholder="Uraian kegiatan yang dilaksanakan"
                                                  class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm"></textarea>
                                    </div>
                                    <div class="lg:col-span-3">
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Pekerjaan</label>
                                        <textarea x-model="row.pekerjaan" :name="'items[' + i + '][pekerjaan]'" rows="2" required
                                                  placeholder="Hasil / pekerjaan yang diselesaikan"
                                                  class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm"></textarea>
                                    </div>
                                    <div class="lg:col-span-1">
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-300">Jumlah</label>
                                        <input type="number" x-model="row.jumlah" :name="'items[' + i + '][total_jumlah]'" min="0"
                                               class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm" />
                                    </div>
                                    <div class="lg:col-span-1 flex items-end justify-between gap-2">
                                        <label class="flex items-center gap-1 text-xs text-gray-600 dark:text-gray-300">
                                            <input type="checkbox" x-model="row.save_template" :name="'items[' + i + '][save_template]'" value="1"
                                                   class="rounded border-gray-300 text-teal-600 dark:text-teal-400 focus:ring-teal-500">
                                            Template
                                        </label>
                                        <button type="button" @click="removeRow(i)" x-show="rows.length > 1"
                                                class="text-red-600 dark:text-red-400 hover:underline text-sm">Hapus</button>
                                    </div>
                                </div>
                                <input type="hidden" :name="'items[' + i + '][tanggal]'" :value="tanggal" />
                            </div>
                        </template>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-primary-button>Simpan Kegiatan</x-primary-button>
                        <button type="button" @click="addRow()" class="text-sm text-teal-700 dark:text-teal-400 font-medium hover:underline">+ Tambah baris</button>
                    </div>
                    @if ($errors->has('items'))
                        <p class="text-xs text-red-600 dark:text-red-400">{{ $errors->first('items') }}</p>
                    @endif
                </form>
            </div>

            @php($grouped = $activities->groupBy(fn ($a) => $a->tanggal))
            @forelse ($grouped as $tanggal => $items)
                <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800">
                    <div class="border-b border-gray-100 dark:border-gray-700 px-6 py-4 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ tanggal_indonesia($tanggal, 'l, d F Y') }}</h3>
                        <span class="text-xs text-teal-700 dark:text-teal-400 font-medium">{{ $items->sum('total_jumlah') }} volume</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/40">
                                <tr>
                                    @if ($users->isNotEmpty())
                                        <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Pegawai</th>
                                    @endif
                                    <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Jenis</th>
                                    <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Kegiatan</th>
                                    <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Pekerjaan</th>
                                    <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Jumlah</th>
                                    <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                                @foreach ($items as $activity)
                                    <tr>
                                        @if ($users->isNotEmpty())
                                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $activity->user->name }}</td>
                                        @endif
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                            {{ $activity->activityLabel() ?? 'Lainnya' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 max-w-md">{{ $activity->kegiatan }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 max-w-md">{{ $activity->pekerjaan }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 text-right">{{ $activity->total_jumlah }}</td>
                                        <td class="px-4 py-3 text-sm space-x-2 whitespace-nowrap">
                                            <a href="{{ route('kegiatan.edit', $activity) }}" class="text-blue-600 dark:text-blue-400 hover:underline">Edit</a>
                                            <form action="{{ route('kegiatan.destroy', $activity) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Hapus kegiatan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800 px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    Belum ada kegiatan pada {{ tanggal_indonesia(now()->month($month)->year($year), 'F Y') }}.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
