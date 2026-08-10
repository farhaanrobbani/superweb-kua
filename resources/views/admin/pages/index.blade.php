<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">Page</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 dark:bg-green-900/30 dark:border-green-800 dark:text-green-300 px-4 py-3 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm dark:bg-gray-800 sm:rounded-lg p-6">
                <div class="mb-6 flex flex-wrap gap-1 border-b border-gray-200 dark:border-gray-700">
                    @foreach ($pages as $tab)
                        <a href="{{ route('pages.index', ['tab' => $tab->key]) }}"
                           class="-mb-px border-b-2 px-4 py-2 text-sm font-semibold {{ $page->key === $tab->key ? 'border-teal-700 text-teal-700 dark:text-teal-400' : 'border-transparent text-gray-500 dark:text-gray-400 dark:text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:text-gray-500' }}">
                            {{ $tab->navbar_label ?? $tab->title }}
                        </a>
                    @endforeach
                </div>

                <form method="POST" action="{{ route('pages.update', ['key' => $page->key]) }}" class="mb-8">
                    @csrf
                    @method('PUT')

                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Judul &amp; Deskripsi Halaman</h3>

                    <div class="space-y-4">
                        <div>
                            <x-input-label for="title" :value="__('Judul')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                          value="{{ old('title', $page->title) }}" />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea id="description" name="description" rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">{{ old('description', $page->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="embed_url" :value="__('URL Embed (opsional)')" />
                            <x-text-input id="embed_url" name="embed_url" type="url" class="mt-1 block w-full" maxlength="255"
                                          value="{{ old('embed_url', $page->embed_url) }}"
                                          placeholder="https://datastudio.google.com/embed/reporting/..." />
                            <p class="text-xs text-gray-500 dark:text-gray-400 dark:text-gray-500 mt-1">Tempel URL <code>src</code> dari bagikan Google Looker Studio (laporan/data studio) yang akan ditampilkan di halaman ini.</p>
                            <x-input-error :messages="$errors->get('embed_url')" class="mt-2" />
                        </div>

                        <div>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-teal-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-600">
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>

                @if ($page->key === 'pernikahan')
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Topik Layanan Pernikahan</h3>
                        @include('admin.marriage-services._table', ['marriageServices' => $marriageServices])
                    </div>
                @endif

                @if ($page->key === 'keagamaan')
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Topik Layanan Keagamaan</h3>
                        @include('admin.religious-services._table', ['religiousServices' => $religiousServices])
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
