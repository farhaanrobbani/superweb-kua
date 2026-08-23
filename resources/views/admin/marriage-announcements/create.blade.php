<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">Tambah Pengumuman Kehendak Nikah</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 p-6">
                <form method="POST" action="{{ route('marriage-announcements.store') }}">
                    @csrf

                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-4">Data Umum</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <x-input-label for="no_pendaftaran" value="Nomor Pendaftaran" />
                            <x-text-input id="no_pendaftaran" name="no_pendaftaran" class="mt-1 block w-full"
                                          placeholder="2026/0123/PKN"
                                          value="{{ old('no_pendaftaran') }}" />
                            <x-input-error :messages="$errors->get('no_pendaftaran')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="tanggal_akad" value="Tanggal Akad *" />
                            <x-text-input id="tanggal_akad" name="tanggal_akad" type="date" class="mt-1 block w-full" required
                                          value="{{ old('tanggal_akad') }}" />
                            <p class="text-xs text-gray-500 mt-1">Pengumuman otomatis disembunyikan setelah tanggal ini.</p>
                            <x-input-error :messages="$errors->get('tanggal_akad')" class="mt-2" />
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="tempat_nikah" value="Tempat Akad" />
                            <x-text-input id="tempat_nikah" name="tempat_nikah" class="mt-1 block w-full"
                                          placeholder="Masjid Nurul Iman, Desa Sukamaju"
                                          value="{{ old('tempat_nikah') }}" />
                            <x-input-error :messages="$errors->get('tempat_nikah')" class="mt-2" />
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="status_wali" value="Status Wali Nikah" />
                            <x-text-input id="status_wali" name="status_wali" class="mt-1 block w-full"
                                          placeholder="Ayah Kandung / Wali Hakim / Wali Nasab"
                                          value="{{ old('status_wali') }}" />
                            <x-input-error :messages="$errors->get('status_wali')" class="mt-2" />
                        </div>
                    </div>

                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide mt-8 mb-4">Calon Mempelai Pria</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <x-input-label for="nama_pria" value="Nama Lengkap *" />
                            <x-text-input id="nama_pria" name="nama_pria" class="mt-1 block w-full" required
                                          placeholder="Ahmad Fauzi, S.Kom."
                                          value="{{ old('nama_pria') }}" />
                            <x-input-error :messages="$errors->get('nama_pria')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="bin_pria" value="Bin (Nama Ayah)" />
                            <x-text-input id="bin_pria" name="bin_pria" class="mt-1 block w-full"
                                          placeholder="Muhammad Ali"
                                          value="{{ old('bin_pria') }}" />
                            <p class="text-xs text-gray-500 mt-1">Tampil sebagai "Ahmad Fauzi bin Muhammad Ali".</p>
                            <x-input-error :messages="$errors->get('bin_pria')" class="mt-2" />
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="alamat_pria" value="Alamat" />
                            <x-text-input id="alamat_pria" name="alamat_pria" class="mt-1 block w-full"
                                          placeholder="Dusun Sukamaju RT 02 RW 01, Kec. Ampelgading"
                                          value="{{ old('alamat_pria') }}" />
                            <x-input-error :messages="$errors->get('alamat_pria')" class="mt-2" />
                        </div>
                    </div>

                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide mt-8 mb-4">Calon Mempelai Wanita</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <x-input-label for="nama_wanita" value="Nama Lengkap *" />
                            <x-text-input id="nama_wanita" name="nama_wanita" class="mt-1 block w-full" required
                                          placeholder="Siti Maryam, S.Pd."
                                          value="{{ old('nama_wanita') }}" />
                            <x-input-error :messages="$errors->get('nama_wanita')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="binti_wanita" value="Binti (Nama Ayah)" />
                            <x-text-input id="binti_wanita" name="binti_wanita" class="mt-1 block w-full"
                                          placeholder="Abdullah"
                                          value="{{ old('binti_wanita') }}" />
                            <p class="text-xs text-gray-500 mt-1">Tampil sebagai "Siti Maryam binti Abdullah".</p>
                            <x-input-error :messages="$errors->get('binti_wanita')" class="mt-2" />
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="alamat_wanita" value="Alamat" />
                            <x-text-input id="alamat_wanita" name="alamat_wanita" class="mt-1 block w-full"
                                          placeholder="Dusun Sidodadi RT 03 RW 02, Kec. Ampelgading"
                                          value="{{ old('alamat_wanita') }}" />
                            <x-input-error :messages="$errors->get('alamat_wanita')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                            <input type="checkbox" name="active" value="1"
                                   class="rounded border-gray-300 text-teal-600 focus:ring-teal-500"
                                   {{ old('active', true) ? 'checked' : '' }}>
                            Aktif (tampilkan di halaman publik)
                        </label>
                    </div>

                    <div class="mt-6 flex items-center gap-4">
                        <x-primary-button>Simpan</x-primary-button>
                        <a href="{{ route('marriage-announcements.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
