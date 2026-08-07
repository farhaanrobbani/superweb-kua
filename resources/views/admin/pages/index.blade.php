<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Page</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-6 flex flex-wrap gap-1 border-b border-gray-200">
                    <span class="-mb-px border-b-2 border-teal-700 px-4 py-2 text-sm font-semibold text-teal-700">Pernikahan</span>
                </div>

                <form method="POST" action="{{ route('pages.pernikahan.update') }}" class="mb-8">
                    @csrf
                    @method('PUT')

                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Judul &amp; Deskripsi Halaman</h3>

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
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-teal-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-600">
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Topik Layanan Pernikahan</h3>
                    @include('admin.marriage-services._table', ['marriageServices' => $marriageServices])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
