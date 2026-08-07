<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Layanan</h2>
    </x-slot>

    @push('editor')
        @vite(['resources/js/editor.js'])
    @endpush

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm">
                    Periksa kembali isian yang disorot di bawah.
                </div>
            @endif

            <form method="POST" action="{{ route('services.update', $service) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 space-y-5">
                        <div>
                            <x-input-label for="name" value="Nama Layanan *" />
                            <x-text-input id="name" name="name" required maxlength="150"
                                          value="{{ old('name', $service->name) }}" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Deskripsi (opsional)" />
                            <textarea id="description" name="description" rows="2" maxlength="1000"
                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">{{ old('description', $service->description) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Ditampilkan sebagai sub judul pada halaman layanan.</p>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="content" value="Konten Halaman" />
                            <textarea id="content" name="content" data-editor rows="10"
                                      data-upload-url="{{ route('announcements.gambar') }}"
                                      class="block w-full">{{ old('content', $service->content) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Konten page ini tampil di halaman publik <code>/layanan/{{ $service->slug }}</code>. Layanan dengan konten otomatis ditautkan ke halaman ini di navbar &amp; beranda.</p>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 space-y-5">
                        <div>
                            <x-input-label for="url" value="URL / Tujuan (opsional)" />
                            <x-text-input id="url" name="url" class="mt-1 block w-full" maxlength="255"
                                          value="{{ old('url', $service->url) }}" placeholder="/permohonan" />
                            <p class="text-xs text-gray-500 mt-1">Dipakai jika layanan belum punya konten. Kosongkan untuk menampilkan layanan tanpa tautan.</p>
                            <x-input-error :messages="$errors->get('url')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="embed_url" value="URL Embed (opsional)" />
                            <x-text-input id="embed_url" name="embed_url" type="url" class="mt-1 block w-full" maxlength="255"
                                          value="{{ old('embed_url', $service->embed_url) }}"
                                          placeholder="https://datastudio.google.com/embed/reporting/..." />
                            <p class="text-xs text-gray-500 mt-1">Tempel URL <code>src</code> dari bagikan Google Looker Studio (laporan/data studio).</p>
                            <x-input-error :messages="$errors->get('embed_url')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="icon" value="Ikon" />
                            <select id="icon" name="icon"
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">
                                <option value="">Tanpa ikon</option>
                                @foreach (\App\Http\Controllers\Admin\ServiceController::icons() as $key => $label)
                                    <option value="{{ $key }}" @selected(old('icon', $service->icon) === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('icon')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="sort_order" value="Urutan (angka kecil tampil lebih dulu)" />
                            <x-text-input id="sort_order" name="sort_order" type="number" min="0" max="9999"
                                          class="mt-1 block w-full" value="{{ old('sort_order', $service->sort_order) }}" />
                            <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                        </div>

                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="active" value="1"
                                       @checked($service->active)
                                       class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                <span class="ms-2 text-sm text-gray-600">Aktif</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-4 py-4 flex items-center gap-3">
                        <x-primary-button>Simpan</x-primary-button>
                        <a href="{{ route('services.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
