<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Pegawai</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('staff.update', $staff) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="nama" value="Nama Lengkap" />
                        <x-text-input id="nama" name="nama" class="mt-1 block w-full" required maxlength="150"
                                      value="{{ old('nama', $staff->nama) }}" />
                        <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="nip" value="NIP (opsional)" />
                        <x-text-input id="nip" name="nip" class="mt-1 block w-full" maxlength="50"
                                      value="{{ old('nip', $staff->nip) }}" />
                        <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="jabatan" value="Jabatan" />
                        <x-text-input id="jabatan" name="jabatan" class="mt-1 block w-full" required maxlength="150"
                                      value="{{ old('jabatan', $staff->jabatan) }}" placeholder="Kepala KUA, Penghulu, ..." />
                        <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="pangkat_golongan" value="Pangkat/Golongan (opsional)" />
                        <x-text-input id="pangkat_golongan" name="pangkat_golongan" class="mt-1 block w-full" maxlength="100"
                                      value="{{ old('pangkat_golongan', $staff->pangkat_golongan) }}" placeholder="Penata, III/c, ..." />
                        <x-input-error :messages="$errors->get('pangkat_golongan')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="bagian" value="Bagian / Unit (opsional)" />
                        <x-text-input id="bagian" name="bagian" class="mt-1 block w-full" maxlength="150"
                                      value="{{ old('bagian', $staff->bagian) }}" placeholder="Pimpinan, Tata Usaha, Jabatan Fungsional, ..." />
                        <p class="text-xs text-gray-500 mt-1">Dipakai untuk mengelompokkan Struktur Organisasi di halaman publik.</p>
                        <x-input-error :messages="$errors->get('bagian')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="sort_order" value="Urutan (lebih kecil tampil lebih dulu)" />
                        <x-text-input id="sort_order" name="sort_order" type="number" min="0" max="9999"
                                      class="mt-1 block w-full" value="{{ old('sort_order', $staff->sort_order) }}" />
                        <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                    </div>

                    <div class="mt-4" x-data="{ preview: null }">
                        <x-input-label for="foto" value="Foto (opsional, PNG/JPG/WEBP, maks 2MB)" />
                        <input id="foto" name="foto" type="file" accept="image/png,image/jpeg,image/webp"
                               class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:rounded-md file:border-0 file:bg-teal-700 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-teal-800"
                               @change="const f = $event.target.files[0]; if (f) { const r = new FileReader(); r.onload = e => preview = e.target.result; r.readAsDataURL(f); }" />

                        @if ($staff->fotoUrl())
                            <div class="mt-4 flex items-center gap-4">
                                <img src="{{ $staff->fotoUrl() }}" alt="{{ $staff->nama }}"
                                     class="h-20 w-20 rounded-full border border-gray-200 object-cover" />
                                <label class="flex items-center gap-2 text-sm text-gray-600">
                                    <input type="checkbox" name="foto_hapus" value="1"
                                           class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                    Hapus foto
                                </label>
                            </div>
                        @endif

                        <img x-show="preview" :src="preview" alt="Pratinjau foto"
                             class="mt-3 h-32 w-32 rounded-full border border-gray-200 object-cover" />
                        <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="active" value="1"
                                   @checked($staff->active)
                                   class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <span class="ms-2 text-sm text-gray-600">Aktif (tampil di halaman publik)</span>
                        </label>
                    </div>

                    <div class="mt-6 flex items-center gap-4">
                        <x-primary-button>Simpan</x-primary-button>
                        <a href="{{ route('staff.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
