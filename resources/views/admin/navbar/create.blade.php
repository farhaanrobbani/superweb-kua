<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Item Navbar</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('navbar.index') }}"
                   class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 hover:text-teal-700">
                    &larr; Kembali
                </a>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('navbar.store') }}">
                    @csrf

                    <div>
                        <x-input-label for="label" value="Label" />
                        <x-text-input id="label" name="label" class="mt-1 block w-full" required maxlength="100"
                                      value="{{ old('label') }}" />
                        <x-input-error :messages="$errors->get('label')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="description" value="Deskripsi (opsional)" />
                        <textarea id="description" name="description" rows="3"
                                  class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm"
                                  maxlength="1000">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="url" value="URL (opsional)" />
                        <x-text-input id="url" name="url" class="mt-1 block w-full" maxlength="255"
                                      value="{{ old('url') }}" placeholder="/halaman" />
                        <p class="text-xs text-gray-500 mt-1">Tujuan tautan. Jika item tampil sebagai sub menu, URL tidak dipakai.</p>
                        <x-input-error :messages="$errors->get('url')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="embed_url" value="URL Embed (opsional)" />
                        <x-text-input id="embed_url" name="embed_url" type="url" class="mt-1 block w-full" maxlength="255"
                                      value="{{ old('embed_url') }}"
                                      placeholder="https://datastudio.google.com/embed/reporting/..." />
                        <p class="text-xs text-gray-500 mt-1">Tempel URL <code>src</code> dari bagikan Google Looker Studio (laporan/data studio).</p>
                        <x-input-error :messages="$errors->get('embed_url')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="icon" value="Ikon" />
                        <select id="icon" name="icon"
                                class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">
                            <option value="">Tanpa ikon</option>
                            @foreach (\App\Http\Controllers\Admin\NavbarController::icons() as $key => $label)
                                <option value="{{ $key }}" @selected(old('icon') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('icon')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="has_submenu" value="1"
                                   @checked(old('has_submenu'))
                                   class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <span class="ms-2 text-sm text-gray-600">Tampilkan sebagai sub menu (dropdown)</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1">Jika dicentang, tabel sub menu untuk item ini muncul di menu Navbar.</p>
                    </div>

                    <div class="mt-4">
                        <x-input-label for="sort_order" value="Urutan (angka kecil tampil lebih dulu)" />
                        <x-text-input id="sort_order" name="sort_order" type="number" min="0" max="9999"
                                      class="mt-1 block w-full" value="{{ old('sort_order', 0) }}" />
                        <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="active" value="1"
                                   @checked(old('active', true))
                                   class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <span class="ms-2 text-sm text-gray-600">Tampilkan di navbar publik</span>
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
