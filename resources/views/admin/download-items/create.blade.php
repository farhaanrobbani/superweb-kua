<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Berkas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm">
                    Periksa kembali isian yang disorot di bawah.
                </div>
            @endif

            <form method="POST" action="{{ route('download-items.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 space-y-5">
                        <div>
                            <x-input-label for="title" value="Judul *" />
                            <x-text-input id="title" name="title" required maxlength="200"
                                          value="{{ old('title') }}" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Deskripsi" />
                            <textarea id="description" name="description" rows="3" maxlength="5000"
                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="category" value="Kategori (opsional)" />
                            <x-text-input id="category" name="category" maxlength="100"
                                          value="{{ old('category') }}" class="mt-1 block w-full"
                                          placeholder="contoh: Formulir, Brosur, Panduan" />
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

                        <div class="border-t border-gray-100 pt-5">
                            <h3 class="text-sm font-semibold text-gray-700">Sumber Berkas</h3>
                            <p class="text-xs text-gray-500 mt-1">Pilih salah satu: unggah file langsung atau isi URL eksternal.</p>

                            <div class="mt-4">
                                <x-input-label for="file" value="Unggah File" />
                                <input type="file" name="file" id="file"
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.webp,.zip"
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-teal-50 file:text-teal-700 file:font-semibold hover:file:bg-teal-100" />
                                <p class="text-xs text-gray-500 mt-1">PDF, Word, Excel, PowerPoint, gambar, ZIP. Maks 10 MB.</p>
                                <x-input-error :messages="$errors->get('file')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="external_url" value="URL Eksternal" />
                                <x-text-input id="external_url" name="external_url" type="url" maxlength="500"
                                              value="{{ old('external_url') }}" class="mt-1 block w-full"
                                              placeholder="https://..." />
                                <x-input-error :messages="$errors->get('external_url')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-4 py-4 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <x-input-label for="active" value="Status" />
                                <select name="active" id="active"
                                        class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">
                                    <option value="1" @selected(old('active', '1') === '1')>Aktif (ditampilkan)</option>
                                    <option value="0" @selected(old('active', '1') === '0')>Nonaktif (draf)</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="sort_order" value="Urutan (angka kecil tampil lebih dulu)" />
                                <x-text-input id="sort_order" name="sort_order" type="number" min="0" max="9999"
                                              value="{{ old('sort_order', 0) }}" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                            </div>
                        </div>

                        <div class="pt-2 border-t border-gray-100 flex items-center gap-3">
                            <x-primary-button>Simpan</x-primary-button>
                            <a href="{{ route('download-items.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
