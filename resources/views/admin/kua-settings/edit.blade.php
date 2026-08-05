<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pengaturan KUA</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('kua-settings.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Gambar Hero Beranda</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <x-input-label for="hero" value="Gambar Hero/Banner Beranda (PNG/JPG/WEBP, maks 3MB)" />
                            <input id="hero" name="hero" type="file" accept="image/png,image/jpeg,image/webp"
                                   class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:rounded-md file:border-0 file:bg-teal-700 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-teal-800" />
                            <p class="text-xs text-gray-500 mt-1">Gambar lebar (mis. rasio 21:9) yang tampil di bagian atas beranda. Kosongkan untuk menyembunyikan banner.</p>
                            <x-input-error :messages="$errors->get('hero')" class="mt-2" />

                            @if (! empty($settings['hero_path']['value']) && \Illuminate\Support\Facades\Storage::disk('public')->exists($settings['hero_path']['value']))
                                <div class="mt-4 flex items-start gap-4">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($settings['hero_path']['value']) }}"
                                         alt="Hero Beranda" class="w-full max-w-md rounded-md border border-gray-200 object-cover" />
                                    <label class="flex items-center gap-2 text-sm text-gray-600">
                                        <input type="checkbox" name="hero_hapus" value="1"
                                               class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                        Hapus gambar
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-800 mt-8 mb-4">Logo KUA</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <x-input-label for="logo" value="Logo KUA (PNG/JPG/WEBP, maks 2MB)" />
                            <input id="logo" name="logo" type="file" accept="image/png,image/jpeg,image/webp"
                                   class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:rounded-md file:border-0 file:bg-teal-700 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-teal-800" />
                            <p class="text-xs text-gray-500 mt-1">Gunakan PNG dengan latar transparan untuk hasil terbaik. Logo tampil di beranda, halaman login, dan kop surat PDF.</p>
                            <x-input-error :messages="$errors->get('logo')" class="mt-2" />

                            @if (! empty($settings['logo_path']['value']) && \Illuminate\Support\Facades\Storage::disk('public')->exists($settings['logo_path']['value']))
                                <div class="mt-4 flex items-center gap-4">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($settings['logo_path']['value']) }}"
                                         alt="Logo KUA" class="h-20 w-20 rounded-md border border-gray-200 object-contain p-1 bg-gray-50" />
                                    <label class="flex items-center gap-2 text-sm text-gray-600">
                                        <input type="checkbox" name="logo_hapus" value="1"
                                               class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                        Hapus logo
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-800 mt-8 mb-4">Data Instansi</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <x-input-label for="instansi" value="Nama Instansi" />
                            <x-text-input id="instansi" name="instansi" class="mt-1 block w-full" required
                                          value="{{ old('instansi', $settings['instansi']['value']) }}" />
                            <x-input-error :messages="$errors->get('instansi')" class="mt-2" />
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="alamat" value="Alamat" />
                            <x-text-input id="alamat" name="alamat" class="mt-1 block w-full" required
                                          value="{{ old('alamat', $settings['alamat']['value']) }}" />
                            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="telepon" value="Telepon" />
                            <x-text-input id="telepon" name="telepon" class="mt-1 block w-full"
                                          value="{{ old('telepon', $settings['telepon']['value']) }}" />
                            <x-input-error :messages="$errors->get('telepon')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                          value="{{ old('email', $settings['email']['value']) }}" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="kecamatan" value="Kecamatan" />
                            <x-text-input id="kecamatan" name="kecamatan" class="mt-1 block w-full"
                                          value="{{ old('kecamatan', $settings['kecamatan']['value']) }}" />
                        </div>
                        <div>
                            <x-input-label for="kabupaten" value="Kabupaten/Kota" />
                            <x-text-input id="kabupaten" name="kabupaten" class="mt-1 block w-full"
                                          value="{{ old('kabupaten', $settings['kabupaten']['value']) }}" />
                        </div>
                        <div>
                            <x-input-label for="kode_pos" value="Kode Pos" />
                            <x-text-input id="kode_pos" name="kode_pos" class="mt-1 block w-full"
                                          value="{{ old('kode_pos', $settings['kode_pos']['value']) }}" />
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="jam_layanan" value="Jam Layanan" />
                            <textarea id="jam_layanan" name="jam_layanan" rows="3"
                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm"
                                      maxlength="255" placeholder="Senin – Jumat&#10;08.00 – 15.00 WIB">{{ old('jam_layanan', $settings['jam_layanan']['value']) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Ditampilkan di footer beranda, halaman permohonan, dan pengumuman. Boleh lebih dari satu baris.</p>
                            <x-input-error :messages="$errors->get('jam_layanan')" class="mt-2" />
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-800 mt-8 mb-4">Kepala KUA (Penandatangan)</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <x-input-label for="kepala_nama" value="Nama Kepala KUA" />
                            <x-text-input id="kepala_nama" name="kepala_nama" class="mt-1 block w-full" required
                                          value="{{ old('kepala_nama', $settings['kepala_nama']['value']) }}" />
                            <x-input-error :messages="$errors->get('kepala_nama')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="kepala_nip" value="NIP" />
                            <x-text-input id="kepala_nip" name="kepala_nip" class="mt-1 block w-full"
                                          value="{{ old('kepala_nip', $settings['kepala_nip']['value']) }}" />
                        </div>
                        <div>
                            <x-input-label for="kepala_pangkat" value="Pangkat/Golongan" />
                            <x-text-input id="kepala_pangkat" name="kepala_pangkat" class="mt-1 block w-full"
                                          value="{{ old('kepala_pangkat', $settings['kepala_pangkat']['value']) }}" />
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="sk_kepala" value="No. SK Pengangkatan" />
                            <x-text-input id="sk_kepala" name="sk_kepala" class="mt-1 block w-full"
                                          value="{{ old('sk_kepala', $settings['sk_kepala']['value']) }}" />
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-800 mt-8 mb-4">Tanda Tangan Digital</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <x-input-label for="ttd_path" value="File Tanda Tangan (path)" />
                            <x-text-input id="ttd_path" name="ttd_path" class="mt-1 block w-full"
                                          value="{{ old('ttd_path', $settings['ttd_path']['value']) }}"
                                          placeholder="storage/ttd/tanda-tangan.png" />
                            <p class="text-xs text-gray-500 mt-1">Upload file gambar TTD (PNG dengan latar transparan) ke folder <code>storage/ttd/</code>, lalu isi path-nya di sini.</p>
                            <x-input-error :messages="$errors->get('ttd_path')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-6 flex items-center gap-4">
                        <x-primary-button>Simpan</x-primary-button>
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
