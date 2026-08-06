<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Halaman</h2>
    </x-slot>

    @push('editor')
        @vite(['resources/js/editor.js'])
    @endpush

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm">
                    Periksa kembali isian yang disorot di bawah.
                </div>
            @endif

            <form method="POST" action="{{ route('pages.store') }}" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-3">
                @csrf

                <div class="space-y-6 lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-6">
                            <input type="text" name="title" id="title" required maxlength="200"
                                   value="{{ old('title') }}" placeholder="Tambahkan judul halaman"
                                   class="w-full bg-transparent border-0 border-b-2 border-gray-200 px-0 py-2 text-2xl font-semibold text-gray-900 placeholder:text-gray-400 focus:border-teal-500 focus:ring-0" />
                        </div>
                        <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                            <textarea id="content" name="content" data-editor rows="16"
                                      data-upload-url="{{ route('pages.gambar') }}"
                                      placeholder="Tulis isi halaman di sini..."
                                      class="block w-full">{{ old('content') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="border-b border-gray-100 px-4 py-3 flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-700">Terbitkan</h3>
                            <span class="text-xs text-gray-400">Status</span>
                        </div>
                        <div class="px-4 py-4 space-y-4">
                            <div>
                                <x-input-label for="active" value="Status" />
                                <select name="active" id="active"
                                        class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">
                                    <option value="1" @selected(old('active', true))>Aktif (ditampilkan)</option>
                                    <option value="0" @selected(! old('active', true))>Nonaktif (draf)</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="slug" value="Slug (opsional)" />
                                <input type="text" name="slug" id="slug" maxlength="200"
                                       value="{{ old('slug') }}" placeholder="tentang-kami"
                                       class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm" />
                                <p class="text-xs text-gray-500 mt-1">Alamat halaman: <code>/halaman/slug</code>. Kosongkan untuk dibuat otomatis dari judul.</p>
                                <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                            </div>

                            <div class="pt-2 border-t border-gray-100 flex items-center gap-3">
                                <x-primary-button>Simpan</x-primary-button>
                                <a href="{{ route('pages.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
