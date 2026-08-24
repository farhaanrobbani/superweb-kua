<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">Edit Video</h2>
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

            <form method="POST" action="{{ route('videos.update', $video) }}" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-3">
                @csrf
                @method('PUT')

                <div class="space-y-6 lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <div class="p-6">
                            <input type="text" name="title" id="title" required maxlength="200"
                                   value="{{ old('title', $video->title) }}" placeholder="Judul video"
                                   class="w-full bg-transparent border-0 border-b-2 border-gray-200 dark:border-gray-700 px-0 py-2 text-2xl font-semibold text-gray-900 dark:text-gray-100 placeholder:text-gray-400 focus:border-teal-500 focus:ring-0" />
                        </div>
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm">
                            <textarea id="content" name="content" data-editor rows="10"
                                      data-upload-url="{{ route('videos.gambar') }}"
                                      placeholder="Deskripsi video..."
                                      class="block w-full">{{ old('content', $video->content) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <div class="border-b border-gray-100 dark:border-gray-700 px-4 py-3 flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Terbitkan</h3>
                            <span class="text-xs text-gray-400">Status</span>
                        </div>
                        <div class="px-4 py-4 space-y-4">
                            <div>
                                <x-input-label for="video_url" value="URL Video *" />
                                <input type="url" name="video_url" id="video_url" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                       value="{{ old('video_url', $video->video_url) }}" placeholder="https://www.youtube.com/watch?v=..." />
                                <x-input-error :messages="$errors->get('video_url')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="slug" value="Slug" />
                                <input type="text" name="slug" id="slug" maxlength="220"
                                       class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm"
                                       value="{{ old('slug', $video->slug) }}" />
                            </div>
                            <div>
                                <x-input-label for="active" value="Status" />
                                <select name="active" id="active"
                                        class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">
                                    <option value="1" @selected((string) old('active', $video->active ? '1' : '0') === '1')>Aktif (ditampilkan)</option>
                                    <option value="0" @selected((string) old('active', $video->active ? '1' : '0') === '0')>Nonaktif (draf)</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="published_at" value="Tanggal terbit (opsional)" />
                                <input type="date" name="published_at" id="published_at"
                                       class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm"
                                       value="{{ old('published_at', $video->published_at?->format('Y-m-d')) }}" />
                            </div>
                            <div class="pt-2 border-t border-gray-100 dark:border-gray-700 flex items-center gap-3">
                                <x-primary-button>Simpan</x-primary-button>
                                <a href="{{ route('videos.index') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Batal</a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800" x-data="{ preview: null, selected: false }">
                        <div class="border-b border-gray-100 dark:border-gray-700 px-4 py-3">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Thumbnail</h3>
                        </div>
                        <div class="px-4 py-4">
                            <label for="thumbnail" class="cursor-pointer block text-center text-sm text-gray-600 dark:text-gray-300">
                                <input type="file" name="thumbnail" id="thumbnail" accept="image/jpeg,image/png,image/webp" class="sr-only"
                                       @change="const f = $event.target.files[0]; selected = !!f; if (f) { const r = new FileReader(); r.onload = e => preview = e.target.result; r.readAsDataURL(f); }" />
                                <span class="inline-flex items-center gap-2 rounded-md border border-dashed border-gray-300 px-4 py-3 hover:border-teal-500 hover:text-teal-700">
                                    {!! $video->thumbnailUrl() ? 'Ganti thumbnail' : 'Pilih thumbnail' !!}
                                </span>
                            </label>
                            <img x-show="preview" :src="preview" alt="Pratinjau thumbnail"
                                 class="mt-3 w-full rounded-md border border-gray-200 dark:border-gray-700 object-contain" />
                            @if ($video->thumbnailUrl())
                                <img src="{{ $video->thumbnailUrl() }}" alt="Thumbnail saat ini"
                                     class="mt-3 w-full rounded-md border border-gray-200 dark:border-gray-700 object-contain" />
                                <label class="mt-2 flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                    <input type="checkbox" name="thumbnail_hapus" value="1"
                                           class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                    Hapus thumbnail
                                </label>
                            @endif
                            <x-input-error :messages="$errors->get('thumbnail')" class="mt-2" />
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <div class="border-b border-gray-100 dark:border-gray-700 px-4 py-3">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Ringkasan</h3>
                        </div>
                        <div class="px-4 py-4">
                            <textarea name="excerpt" id="excerpt" rows="3" maxlength="500"
                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm"
                                      placeholder="otomatis dari deskripsi">{{ old('excerpt', $video->excerpt) }}</textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
