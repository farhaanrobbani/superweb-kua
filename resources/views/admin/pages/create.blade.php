<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Page</h2>
    </x-slot>

    @push('editor')
        @vite(['resources/js/editor.js'])
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('pages.index') }}"
                   class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 hover:text-teal-700">
                    &larr; Kembali
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('pages.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="key" value="Kunci Halaman *" />
                        <x-text-input id="key" name="key" class="mt-1 block w-full" required maxlength="100"
                                      value="{{ old('key') }}" placeholder="mis. pernikahan" />
                        <p class="text-xs text-gray-500 mt-1">Slug unik yang menghubungkan halaman ini ke halaman publik.</p>
                        <x-input-error :messages="$errors->get('key')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="title" value="Judul Halaman *" />
                        <x-text-input id="title" name="title" class="mt-1 block w-full" required maxlength="200"
                                      value="{{ old('title') }}" />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Deskripsi (opsional)" />
                        <textarea id="description" name="description" rows="3" maxlength="1000"
                                  class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="content" value="Konten (opsional)" />
                        <textarea id="content" name="content" data-editor rows="10"
                                  class="block w-full">{{ old('content') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Isi utama halaman. Kosongkan jika konten diambil dari data lain.</p>
                        <x-input-error :messages="$errors->get('content')" class="mt-2" />
                    </div>

                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="active" value="1" @checked(old('active', true))
                                   class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <span class="ms-2 text-sm text-gray-600">Aktif (tampilkan di halaman publik)</span>
                        </label>
                    </div>

                    <div class="mt-6 flex items-center gap-4">
                        <x-primary-button>Simpan</x-primary-button>
                        <a href="{{ route('pages.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>