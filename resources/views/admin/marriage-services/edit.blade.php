<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Topik Pernikahan</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm">
                    Periksa kembali isian yang disorot di bawah.
                </div>
            @endif

            <form method="POST" action="{{ route('marriage-services.update', $marriageService) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 space-y-5">
                        <div>
                            <x-input-label for="name" value="Nama Topik *" />
                            <x-text-input id="name" name="name" required maxlength="150"
                                          value="{{ old('name', $marriageService->name) }}" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Deskripsi" />
                            <textarea id="description" name="description" rows="2" maxlength="2000"
                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">{{ old('description', $marriageService->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <x-input-label for="target_url" value="URL Tombol Ajukan" />
                                <x-text-input id="target_url" name="target_url" maxlength="255"
                                              value="{{ old('target_url', $marriageService->target_url) }}" class="mt-1 block w-full" />
                                <p class="text-xs text-gray-500 mt-1">Kosongkan jika topik belum punya alur permohonan online.</p>
                                <x-input-error :messages="$errors->get('target_url')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="icon" value="Ikon" />
                                <select name="icon" id="icon"
                                        class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">
                                    @foreach (\App\Http\Controllers\Admin\MarriageServiceController::ICONS as $key => $label)
                                        <option value="{{ $key }}" @selected(old('icon', $marriageService->icon ?: 'heart') === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('icon')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 space-y-5">
                        <div class="grid gap-4 sm:grid-cols-3">
                            <div>
                                <x-input-label for="persyaratan_label" value="Judul Persyaratan" />
                                <x-text-input id="persyaratan_label" name="persyaratan_label" maxlength="50"
                                              value="{{ old('persyaratan_label', $marriageService->persyaratan_label) }}" class="mt-1 block w-full"
                                              placeholder="Persyaratan" />
                                <x-input-error :messages="$errors->get('persyaratan_label')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="alur_label" value="Judul Alur" />
                                <x-text-input id="alur_label" name="alur_label" maxlength="50"
                                              value="{{ old('alur_label', $marriageService->alur_label) }}" class="mt-1 block w-full"
                                              placeholder="Alur" />
                                <x-input-error :messages="$errors->get('alur_label')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="sop_label" value="Judul SOP" />
                                <x-text-input id="sop_label" name="sop_label" maxlength="50"
                                              value="{{ old('sop_label', $marriageService->sop_label) }}" class="mt-1 block w-full"
                                              placeholder="SOP" />
                                <x-input-error :messages="$errors->get('sop_label')" class="mt-2" />
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 -mt-3">Kosongkan untuk memakai judul bawaan.</p>

                        <div>
                            <x-input-label for="persyaratan" value="Persyaratan (satu per baris)" />
                            <textarea id="persyaratan" name="persyaratan" rows="7" maxlength="5000"
                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm font-mono">{{ old('persyaratan', $marriageService->persyaratan) }}</textarea>
                            <x-input-error :messages="$errors->get('persyaratan')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="alur" value="Alur / Langkah (satu per baris)" />
                            <textarea id="alur" name="alur" rows="7" maxlength="5000"
                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm font-mono">{{ old('alur', $marriageService->alur) }}</textarea>
                            <x-input-error :messages="$errors->get('alur')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="sop" value="SOP / Prosedur Petugas (satu per baris)" />
                            <textarea id="sop" name="sop" rows="6" maxlength="5000"
                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm font-mono">{{ old('sop', $marriageService->sop) }}</textarea>
                            <x-input-error :messages="$errors->get('sop')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-4 py-4 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <x-input-label for="active" value="Status" />
                                <select name="active" id="active"
                                        class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">
                                    <option value="1" @selected(old('active', $marriageService->active ? '1' : '0') === '1')>Aktif (ditampilkan)</option>
                                    <option value="0" @selected(old('active', $marriageService->active ? '1' : '0') === '0')>Nonaktif (draf)</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="sort_order" value="Urutan (angka kecil tampil lebih dulu)" />
                                <x-text-input id="sort_order" name="sort_order" type="number" min="0" max="9999"
                                              value="{{ old('sort_order', $marriageService->sort_order) }}" class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                            </div>
                        </div>

                        <div class="pt-2 border-t border-gray-100 flex items-center gap-3">
                            <x-primary-button>Simpan</x-primary-button>
                            <a href="{{ route('marriage-services.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
