<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Navbar — Edit Layanan</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('services.update', $service) }}">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="name" value="Nama Layanan" />
                        <x-text-input id="name" name="name" class="mt-1 block w-full" required maxlength="150"
                                      value="{{ old('name', $service->name) }}" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="description" value="Deskripsi (opsional)" />
                        <textarea id="description" name="description" rows="3"
                                  class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm"
                                  maxlength="1000">{{ old('description', $service->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="url" value="URL / Tujuan (opsional)" />
                        <x-text-input id="url" name="url" class="mt-1 block w-full" maxlength="255"
                                      value="{{ old('url', $service->url) }}" placeholder="/permohonan" />
                        <p class="text-xs text-gray-500 mt-1">Kosongkan untuk menampilkan layanan tanpa tautan.</p>
                        <x-input-error :messages="$errors->get('url')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="embed_url" value="URL Embed (opsional)" />
                        <x-text-input id="embed_url" name="embed_url" type="url" class="mt-1 block w-full" maxlength="255"
                                      value="{{ old('embed_url', $service->embed_url) }}"
                                      placeholder="https://datastudio.google.com/embed/reporting/..." />
                        <p class="text-xs text-gray-500 mt-1">Tempel URL <code>src</code> dari bagikan Google Looker Studio (laporan/data studio). Jika diisi, layanan akan menampilkan iframe pada halaman khusus.</p>
                        <x-input-error :messages="$errors->get('embed_url')" class="mt-2" />
                    </div>

                    <div class="mt-4">
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

                    <div class="mt-4">
                        <x-input-label for="sort_order" value="Urutan (lebih kecil tampil lebih dulu)" />
                        <x-text-input id="sort_order" name="sort_order" type="number" min="0" max="9999"
                                      class="mt-1 block w-full" value="{{ old('sort_order', $service->sort_order) }}" />
                        <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="active" value="1"
                                   @checked($service->active)
                                   class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <span class="ms-2 text-sm text-gray-600">Aktif</span>
                        </label>
                    </div>

                    <div class="mt-6 flex items-center gap-4">
                        <x-primary-button>Simpan</x-primary-button>
                        <a href="{{ route('navbar.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
