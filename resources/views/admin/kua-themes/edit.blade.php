<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-100">
            {{ isset($theme->id) ? 'Edit Tema Pekerjaan' : 'Tambah Tema Pekerjaan' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div>
                <a href="{{ route('kua-daily.index') }}"
                   class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:bg-gray-700/40 dark:text-teal-400">
                    &larr; Kembali
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800">
                <form method="POST" action="{{ isset($theme->id) ? route('kua-themes.update', $theme) : route('kua-themes.store') }}"
                      class="px-6 py-6 space-y-4">
                    @csrf
                    @if (isset($theme->id))
                        @method('PUT')
                    @endif

                    <div>
                        <x-input-label for="label" value="Nama Tema Pekerjaan" />
                        <input type="text" name="label" id="label" required
                               value="{{ old('label', $theme->label) }}"
                               class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm" />
                        <x-input-error :messages="$errors->get('label')" class="mt-2" />
                        <p class="text-xs text-gray-500 mt-1 dark:text-gray-400">Judul kolom yang tampil di Master Data Harian.</p>
                    </div>

                    <div>
                        <x-input-label for="key" value="Key (identitas unik)" />
                        <input type="text" name="key" id="key"
                               value="{{ old('key', $theme->key) }}" placeholder="diisi otomatis dari nama (huruf kecil, tanpa spasi)"
                               class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm" />
                        <x-input-error :messages="$errors->get('key')" class="mt-2" />
                        <p class="text-xs text-gray-500 mt-1 dark:text-gray-400">
                            Contoh: <code>pelaksanaan_bimwin</code>. Biarkan kosong untuk otomatis dibuat dari nama.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <x-input-label for="sort_order" value="Urutan" />
                            <input type="number" name="sort_order" id="sort_order" min="0"
                                   value="{{ old('sort_order', isset($theme->id) ? $theme->sort_order : $nextOrder ?? 0) }}"
                                   class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm" />
                            <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                            <p class="text-xs text-gray-500 mt-1 dark:text-gray-400">Urutan tampil kolom. Bisa juga pakai tombol ↑ ↓ di daftar.</p>
                        </div>

                        <div>
                            <x-input-label for="active" value="Status" />
                            <select name="active" id="active"
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">
                                <option value="1" @selected(old('active', $theme->active))>Aktif (tampil di form)</option>
                                <option value="0" @selected(old('active', $theme->active) === false || old('active', $theme->active) === '0')>Nonaktif (sembunyikan sementara)</option>
                            </select>
                            <x-input-error :messages="$errors->get('active')" class="mt-2" />
                        </div>
                    </div>

                    <div class="pt-2 border-t border-gray-100 flex items-center gap-3 dark:border-gray-700">
                        <x-primary-button>Simpan</x-primary-button>
                        <a href="{{ route('kua-themes.index') }}" class="text-sm text-gray-600 hover:underline dark:text-gray-400">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
