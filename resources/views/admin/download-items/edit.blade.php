<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">Edit Berkas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 dark:bg-red-900/30 dark:border-red-800 dark:text-red-300 px-4 py-3 rounded-md text-sm">
                    Periksa kembali isian yang disorot di bawah.
                </div>
            @endif

            <form method="POST" action="{{ route('download-items.update', $downloadItem) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800">
                    <div class="p-6 space-y-5">
                        <div>
                            <x-input-label for="title" value="Judul *" />
                            <x-text-input id="title" name="title" required maxlength="200"
                                          value="{{ old('title', $downloadItem->title) }}" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Deskripsi" />
                            <textarea id="description" name="description" rows="3" maxlength="5000"
                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">{{ old('description', $downloadItem->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="category" value="Kategori (opsional)" />
                            <x-text-input id="category" name="category" maxlength="100"
                                          value="{{ old('category', $downloadItem->category) }}" class="mt-1 block w-full"
                                          placeholder="contoh: Formulir, Brosur, Panduan" />
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

                        <div class="border-t border-gray-100 dark:border-gray-700 pt-5">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 dark:text-gray-500">Sumber Berkas</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 dark:text-gray-500 mt-1">Pilih salah satu: unggah file langsung atau isi URL eksternal.</p>

                            <div class="mt-4">
                                <x-input-label for="file" value="Unggah File Baru (kosongkan jika tidak diubah)" />
                                @if ($downloadItem->file)
                                    <div class="mt-2 flex items-center gap-3 rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/40 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 dark:text-gray-500">
                                        <span class="truncate">{{ $downloadItem->fileName() }} ({{ $downloadItem->fileSize() ?? '—' }})</span>
                                    </div>
                                    <label class="mt-3 flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300 dark:text-gray-500">
                                        <input type="checkbox" name="file_hapus" value="1" class="rounded border-gray-300 text-teal-600 dark:text-teal-400 focus:ring-teal-500" />
                                        Hapus file ini
                                    </label>
                                @endif
                                <input type="file" name="file" id="file"
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.webp,.zip"
                                       class="mt-2 block w-full text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-teal-50 file:text-teal-700 dark:text-teal-400 file:font-semibold hover:file:bg-teal-100" />
                                <p class="text-xs text-gray-500 dark:text-gray-400 dark:text-gray-500 mt-1">PDF, Word, Excel, PowerPoint, gambar, ZIP. Maks 10 MB.</p>
                                <x-input-error :messages="$errors->get('file')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="external_url" value="URL Eksternal" />
                                <x-text-input id="external_url" name="external_url" type="url" maxlength="500"
                                              value="{{ old('external_url', $downloadItem->external_url) }}" class="mt-1 block w-full"
                                              placeholder="https://..." />
                                <x-input-error :messages="$errors->get('external_url')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800">
                    <div class="px-4 py-4 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <x-input-label for="active" value="Status" />
                                <select name="active" id="active"
                                        class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">
                                    <option value="1" @selected(old('active', $downloadItem->active ? '1' : '0') === '1')>Aktif (ditampilkan)</option>
                                    <option value="0" @selected(old('active', $downloadItem->active ? '1' : '0') === '0')>Nonaktif (draf)</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="sort_order" value="Urutan (angka kecil tampil lebih dulu)" />
                                <x-text-input id="sort_order" name="sort_order" type="number" min="0" max="9999"
                                              value="{{ old('sort_order', $downloadItem->sort_order) }}" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                            </div>
                        </div>

                        <div class="pt-2 border-t border-gray-100 dark:border-gray-700 flex items-center gap-3">
                            <x-primary-button>Simpan</x-primary-button>
                            <a href="{{ route('download-items.index') }}" class="text-sm text-gray-600 dark:text-gray-300 dark:text-gray-500 hover:underline">Batal</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
