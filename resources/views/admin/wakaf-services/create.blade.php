<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">Tambah Topik Wakaf</h2>
    </x-slot>

    @push('editor')
        @vite(['resources/js/editor.js'])
    @endpush

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 dark:bg-red-900/30 dark:border-red-800 dark:text-red-300 px-4 py-3 rounded-md text-sm">
                    Periksa kembali isian yang disorot di bawah.
                </div>
            @endif

            <form method="POST" action="{{ route('wakaf-services.store') }}" class="space-y-6">
                @csrf

                <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800">
                    <div class="p-6 space-y-5">
                        <div>
                            <x-input-label for="name" value="Nama Topik *" />
                            <x-text-input id="name" name="name" required maxlength="150"
                                          value="{{ old('name') }}" class="mt-1 block w-full"
                                          placeholder="contoh: Pendaftaran Akta Ikrar Wakaf" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Deskripsi" />
                            <textarea id="description" name="description" rows="2" maxlength="2000"
                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="icon" value="Ikon" />
                            <select name="icon" id="icon"
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">
                                @foreach (\App\Http\Controllers\Admin\WakafServiceController::ICONS as $key => $label)
                                    <option value="{{ $key }}" @selected(old('icon', 'document') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('icon')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800">
                    <div class="p-6 space-y-5">
                        <div class="grid gap-4 sm:grid-cols-3">
                            <div>
                                <x-input-label for="persyaratan_label" value="Judul Persyaratan" />
                                <x-text-input id="persyaratan_label" name="persyaratan_label" maxlength="50"
                                              value="{{ old('persyaratan_label') }}" class="mt-1 block w-full"
                                              placeholder="Persyaratan" />
                                <x-input-error :messages="$errors->get('persyaratan_label')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="alur_label" value="Judul Alur" />
                                <x-text-input id="alur_label" name="alur_label" maxlength="50"
                                              value="{{ old('alur_label') }}" class="mt-1 block w-full"
                                              placeholder="Alur" />
                                <x-input-error :messages="$errors->get('alur_label')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="sop_label" value="Judul SOP" />
                                <x-text-input id="sop_label" name="sop_label" maxlength="50"
                                              value="{{ old('sop_label') }}" class="mt-1 block w-full"
                                              placeholder="SOP" />
                                <x-input-error :messages="$errors->get('sop_label')" class="mt-2" />
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 dark:text-gray-500 -mt-3">Kosongkan untuk memakai judul bawaan.</p>

                        <div>
                            <x-input-label for="persyaratan" value="Persyaratan" />
                            <textarea id="persyaratan" name="persyaratan" data-editor rows="8"
                                      data-upload-url="{{ route('announcements.gambar') }}"
                                      class="block w-full">{{ old('persyaratan') }}</textarea>
                            <x-input-error :messages="$errors->get('persyaratan')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="alur" value="Alur / Langkah" />
                            <textarea id="alur" name="alur" data-editor rows="8"
                                      data-upload-url="{{ route('announcements.gambar') }}"
                                      class="block w-full">{{ old('alur') }}</textarea>
                            <x-input-error :messages="$errors->get('alur')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="sop" value="SOP / Prosedur Petugas" />
                            <textarea id="sop" name="sop" data-editor rows="8"
                                      data-upload-url="{{ route('announcements.gambar') }}"
                                      class="block w-full">{{ old('sop') }}</textarea>
                            <x-input-error :messages="$errors->get('sop')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800">
                    <div class="px-4 py-4 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <x-input-label for="active" value="Status" />
                                <select name="active" id="active"
                                        class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">
                                    <option value="1" @selected(old('active', '1') === '1')>Aktif (ditampilkan)</option>
                                    <option value="0" @selected(old('active', '1') === '0')>Nonaktif (draf)</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="sort_order" value="Urutan (angka kecil tampil lebih dulu)" />
                                <x-text-input id="sort_order" name="sort_order" type="number" min="0" max="9999"
                                              value="{{ old('sort_order', 0) }}" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                            </div>
                        </div>

                        <div class="pt-2 border-t border-gray-100 dark:border-gray-700 flex items-center gap-3">
                            <x-primary-button>Simpan</x-primary-button>
                            <a href="{{ route('pages.index', ['tab' => 'wakaf']) }}" class="text-sm text-gray-600 dark:text-gray-300 dark:text-gray-500 hover:underline">Batal</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
