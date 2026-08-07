<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">Tambah Pengumuman</h2>
    </x-slot>

    @push('editor')
        @vite(['resources/js/editor.js'])
    @endpush

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 dark:bg-red-900/30 dark:border-red-800 dark:text-red-300 px-4 py-3 rounded-md text-sm">
                    Periksa kembali isian yang disorot di bawah.
                </div>
            @endif

            <form method="POST" action="{{ route('announcements.store') }}" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-3">
                @csrf

                <div class="space-y-6 lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <div class="p-6">
                            <input type="text" name="title" id="title" required maxlength="200"
                                   value="{{ old('title') }}" placeholder="Tambahkan judul"
                                   class="w-full bg-transparent border-0 border-b-2 border-gray-200 dark:border-gray-700 px-0 py-2 text-2xl font-semibold text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:text-gray-500 focus:border-teal-500 focus:ring-0" />
                        </div>
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm">
                            <textarea id="content" name="content" data-editor rows="16"
                                      data-upload-url="{{ route('announcements.gambar') }}"
                                      placeholder="Tulis isi pengumuman di sini..."
                                      class="block w-full">{{ old('content') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <div class="border-b border-gray-100 dark:border-gray-700 px-4 py-3 flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 dark:text-gray-500">Terbitkan</h3>
                            <span class="text-xs text-gray-400 dark:text-gray-500">Status</span>
                        </div>
                        <div class="px-4 py-4 space-y-4">
                            <div>
                                <x-input-label for="active" value="Status" />
                                <select name="active" id="active"
                                        class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">
                                    <option value="1" @selected(old('active', '1') === '1')>Aktif (ditampilkan)</option>
                                    <option value="0" @selected(old('active', '1') === '0')>Nonaktif (draf)</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="published_at" value="Tanggal terbit (opsional)" />
                                <input type="date" name="published_at" id="published_at"
                                       class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm"
                                       value="{{ old('published_at') }}" />
                                <p class="text-xs text-gray-500 dark:text-gray-400 dark:text-gray-500 mt-1">Kosongkan untuk langsung terbit hari ini.</p>
                            </div>

                            <div class="pt-2 border-t border-gray-100 dark:border-gray-700 flex items-center gap-3">
                                <x-primary-button>Simpan</x-primary-button>
                                <a href="{{ route('announcements.index') }}" class="text-sm text-gray-600 dark:text-gray-300 dark:text-gray-500 hover:underline">Batal</a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800" x-data="{ preview: null, selected: false }">
                        <div class="border-b border-gray-100 dark:border-gray-700 px-4 py-3">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 dark:text-gray-500">Gambar Sampul</h3>
                        </div>
                        <div class="px-4 py-4">
                            <label for="image" class="cursor-pointer block text-center text-sm text-gray-600 dark:text-gray-300 dark:text-gray-500">
                                <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/webp" class="sr-only"
                                       @change="const f = $event.target.files[0]; selected = !!f; if (f) { const r = new FileReader(); r.onload = e => preview = e.target.result; r.readAsDataURL(f); }" />
                                <span class="inline-flex items-center gap-2 rounded-md border border-dashed border-gray-300 px-4 py-3 hover:border-teal-500 hover:text-teal-700 dark:text-teal-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-2-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Pilih gambar sampul
                                </span>
                            </label>
                            <img x-show="preview" :src="preview" alt="Pratinjau sampul"
                                 class="mt-3 w-full rounded-md border border-gray-200 dark:border-gray-700 object-contain" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>