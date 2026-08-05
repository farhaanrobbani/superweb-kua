<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Pengumuman</h2>
    </x-slot>

    @push('editor')
        @vite(['resources/js/editor.js'])
    @endpush

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('announcements.update', $announcement) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="title" value="Judul" />
                        <x-text-input id="title" name="title" class="mt-1 block w-full" required maxlength="200"
                                      value="{{ old('title', $announcement->title) }}" />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="content" value="Isi Pengumuman" />
                        <textarea id="content" name="content" data-editor rows="12"
                                  class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">{{ old('content', $announcement->content) }}</textarea>
                        <x-input-error :messages="$errors->get('content')" class="mt-2" />
                    </div>

                    <div class="mt-4" x-data="{ preview: null, selected: false, hapus: false }">
                        <x-input-label for="image" value="Gambar Sampul (opsional)" />
                        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp"
                               class="mt-1 block w-full text-sm text-gray-600 file:mr-3 file:rounded-md file:border-0 file:bg-teal-700 file:px-4 file:py-2 file:text-xs file:font-semibold file:uppercase file:tracking-widest file:text-white hover:file:bg-teal-600"
                               @change="const f = $event.target.files[0]; selected = !!f; if (f) { const r = new FileReader(); r.onload = e => preview = e.target.result; r.readAsDataURL(f); }" />

                        @if ($announcement->imageUrl())
                            <div x-show="!selected">
                                <img src="{{ $announcement->imageUrl() }}" alt="Sampul saat ini"
                                     class="mt-3 max-h-48 rounded-md border border-gray-200 object-contain" />
                                <label class="mt-2 inline-flex items-center">
                                    <input type="checkbox" name="image_hapus" value="1" x-model="hapus"
                                           class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                    <span class="ms-2 text-sm text-gray-600">Hapus gambar sampul ini</span>
                                </label>
                            </div>
                        @endif

                        <img x-show="selected && preview" :src="preview" class="mt-3 max-h-48 rounded-md border border-gray-200 object-contain" />
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="published_at" value="Tanggal Terbit (opsional)" />
                        <x-text-input id="published_at" name="published_at" type="datetime-local"
                                      class="mt-1 block w-full"
                                      value="{{ old('published_at', $announcement->published_at?->format('Y-m-d\TH:i')) }}" />
                        <p class="text-xs text-gray-500 mt-1">Kosongkan untuk langsung terbit, atau atur jadwal terbit di masa mendatang.</p>
                        <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="active" value="1"
                                   @checked($announcement->active)
                                   class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <span class="ms-2 text-sm text-gray-600">Aktif</span>
                        </label>
                    </div>

                    <div class="mt-6 flex items-center gap-4">
                        <x-primary-button>Simpan</x-primary-button>
                        <a href="{{ route('announcements.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
