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

                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Teks Beranda</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <x-input-label for="hero_judul" value="Judul Utama Beranda" />
                            <textarea id="hero_judul" name="hero_judul" rows="3"
                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm"
                                      maxlength="255" placeholder="Layanan Surat Digital&#10;Tanpa Antre, Kapan Saja">{{ old('hero_judul', $settings['hero_judul']['value']) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Baris baru (enter) menjadi baris baru pada judul. Kosongkan untuk memakai teks bawaan.</p>
                            <x-input-error :messages="$errors->get('hero_judul')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="hero_subjudul" value="Paragraf Deskripsi Beranda" />
                            <textarea id="hero_subjudul" name="hero_subjudul" rows="3"
                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm"
                                      maxlength="500" placeholder="Ajukan permohonan surat keterangan dan surat pengantar secara online.">{{ old('hero_subjudul', $settings['hero_subjudul']['value']) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Kosongkan untuk memakai teks bawaan.</p>
                            <x-input-error :messages="$errors->get('hero_subjudul')" class="mt-2" />
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-800 mt-8 mb-4">Foto Background Welcome</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <x-input-label for="bg" value="Foto Background Welcome (PNG/JPG/WEBP, maks 3MB)" />
                            <input id="bg" name="bg" type="file" accept="image/png,image/jpeg,image/webp"
                                   class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:rounded-md file:border-0 file:bg-teal-700 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-teal-800" />
                            <p class="text-xs text-gray-500 mt-1">Foto yang menjadi latar belakang teks welcome di beranda. Kosongkan untuk memakai latar gradien bawaan.</p>
                            <x-input-error :messages="$errors->get('bg')" class="mt-2" />

                            @if (! empty($settings['bg_path']['value']) && \Illuminate\Support\Facades\Storage::disk('public')->exists($settings['bg_path']['value']))
                                <div class="mt-4 flex items-start gap-4">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($settings['bg_path']['value']) }}"
                                         alt="Background Welcome" class="w-full max-w-md rounded-md border border-gray-200 object-cover" />
                                    <label class="flex items-center gap-2 text-sm text-gray-600">
                                        <input type="checkbox" name="bg_hapus" value="1"
                                               class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                        Hapus gambar
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-800 mt-8 mb-4">Banner Beranda</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <x-input-label for="hero" value="Banner Beranda (PNG/JPG/WEBP, maks 3MB)" />
                            <input id="hero" name="hero" type="file" accept="image/png,image/jpeg,image/webp"
                                   class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:rounded-md file:border-0 file:bg-teal-700 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-teal-800" />
                            <p class="text-xs text-gray-500 mt-1">Gambar lebar (mis. rasio 21:9) yang tampil sebagai banner di bawah teks welcome. Bisa diganti kapan saja. Kosongkan untuk menyembunyikan banner.</p>
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
                            <x-input-label for="logo" value="Logo 1 (KUA) (PNG/JPG/WEBP, maks 2MB)" />
                            <input id="logo" name="logo" type="file" accept="image/png,image/jpeg,image/webp"
                                   class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:rounded-md file:border-0 file:bg-teal-700 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-teal-800" />
                            <p class="text-xs text-gray-500 mt-1">Gunakan PNG dengan latar transparan. Logo 1 tampil di beranda, halaman login, dan favicon.</p>
                            <x-input-error :messages="$errors->get('logo')" class="mt-2" />

                            @if (! empty($settings['logo_path']['value']) && \Illuminate\Support\Facades\Storage::disk('public')->exists($settings['logo_path']['value']))
                                <div class="mt-4 flex items-center gap-4">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($settings['logo_path']['value']) }}"
                                         alt="Logo 1 (KUA)" class="h-20 w-20 rounded-md border border-gray-200 object-contain p-1 bg-gray-50" />
                                    <label class="flex items-center gap-2 text-sm text-gray-600">
                                        <input type="checkbox" name="logo_hapus" value="1"
                                               class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                        Hapus logo 1
                                    </label>
                                </div>
                            @endif
                        </div>

                        <div>
                            <x-input-label for="logo2" value="Logo 2 (PNG/JPG/WEBP, maks 2MB)" />
                            <input id="logo2" name="logo2" type="file" accept="image/png,image/jpeg,image/webp"
                                   class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:rounded-md file:border-0 file:bg-teal-700 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-teal-800" />
                            <p class="text-xs text-gray-500 mt-1">Gunakan PNG dengan latar transparan. Logo 2 hanya dipakai untuk kop surat PDF jika dipilih.</p>
                            <x-input-error :messages="$errors->get('logo2')" class="mt-2" />

                            @if (! empty($settings['logo2_path']['value']) && \Illuminate\Support\Facades\Storage::disk('public')->exists($settings['logo2_path']['value']))
                                <div class="mt-4 flex items-center gap-4">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($settings['logo2_path']['value']) }}"
                                         alt="Logo 2" class="h-20 w-20 rounded-md border border-gray-200 object-contain p-1 bg-gray-50" />
                                    <label class="flex items-center gap-2 text-sm text-gray-600">
                                        <input type="checkbox" name="logo2_hapus" value="1"
                                               class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                        Hapus logo 2
                                    </label>
                                </div>
                            @endif
                        </div>

                        <div>
                            <x-input-label value="Logo yang Dipakai di Kop Surat" />
                            <div class="mt-1 space-y-2">
                                <label class="flex items-center gap-2 text-sm text-gray-700">
                                    <input type="radio" name="kop_logo" value="logo1"
                                           class="rounded-full border-gray-300 text-teal-600 focus:ring-teal-500"
                                           {{ (old('kop_logo', $settings['kop_logo']['value'] ?: 'logo1') === 'logo1') ? 'checked' : '' }}>
                                    Logo 1 (KUA)
                                </label>
                                <label class="flex items-center gap-2 text-sm text-gray-700">
                                    <input type="radio" name="kop_logo" value="logo2"
                                           class="rounded-full border-gray-300 text-teal-600 focus:ring-teal-500"
                                           {{ (old('kop_logo', $settings['kop_logo']['value'] ?: 'logo1') === 'logo2') ? 'checked' : '' }}>
                                    Logo 2
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Satu logo terpilih tampil di sisi kiri kop surat PDF.</p>
                            <x-input-error :messages="$errors->get('kop_logo')" class="mt-2" />
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-800 mt-8 mb-4">Kop Surat (Teks)</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <x-input-label for="kop_teks" value="Isi Teks Kop Surat" />
                            <textarea id="kop_teks" name="kop_teks" rows="5"
                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm font-mono text-sm"
                                      placeholder="#KUA KECAMATAN CONTOH&#10;##KECAMATAN CONTOH KABUPATEN CONTOH&#10;Jl. Contoh No. 1, Telp. (021) 123456">{{ old('kop_teks', $settings['kop_teks']['value']) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">
                                Tiap baris menjadi satu baris di kop surat. Penanda:
                                <code>#</code> = besar dan tebal (nama instansi), <code>##</code> = tebal (sub), tanpa penanda = baris biasa (alamat, dll).
                                Kosongkan untuk memakai field Instansi/Kecamatan/Alamat yang sudah diisi di atas.
                            </p>
                            <x-input-error :messages="$errors->get('kop_teks')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
                        <div>
                            <x-input-label for="kop_ukuran_judul" value="Ukuran Font Judul (px)" />
                            <x-text-input id="kop_ukuran_judul" name="kop_ukuran_judul" type="number" min="6" max="72" step="0.5"
                                          class="mt-1 block w-full" placeholder="17"
                                          value="{{ old('kop_ukuran_judul', $settings['kop_ukuran_judul']['value']) }}" />
                            <x-input-error :messages="$errors->get('kop_ukuran_judul')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="kop_ukuran_sub" value="Ukuran Font Sub (px)" />
                            <x-text-input id="kop_ukuran_sub" name="kop_ukuran_sub" type="number" min="6" max="72" step="0.5"
                                          class="mt-1 block w-full" placeholder="13"
                                          value="{{ old('kop_ukuran_sub', $settings['kop_ukuran_sub']['value']) }}" />
                            <x-input-error :messages="$errors->get('kop_ukuran_sub')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="kop_ukuran_baris" value="Ukuran Font Baris (px)" />
                            <x-text-input id="kop_ukuran_baris" name="kop_ukuran_baris" type="number" min="6" max="72" step="0.5"
                                          class="mt-1 block w-full" placeholder="10.5"
                                          value="{{ old('kop_ukuran_baris', $settings['kop_ukuran_baris']['value']) }}" />
                            <x-input-error :messages="$errors->get('kop_ukuran_baris')" class="mt-2" />
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Kosongkan untuk memakai ukuran bawaan (Judul 17, Sub 13, Baris 10.5 px).</p>

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
