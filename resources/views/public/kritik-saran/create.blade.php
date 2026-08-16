@extends('layouts.public')

@section('title', (kua_navbar_page_label('kritik-saran', 'Kritik & Saran')).' — '.kua_setting('instansi', 'KUA'))

@section('content')
    <section class="mx-auto max-w-3xl px-6 py-14">
        <div class="mb-6">
            <p class="text-sm font-medium uppercase tracking-widest text-teal-700">Suara Anda</p>
            <h1 class="mt-2 text-3xl font-bold sm:text-4xl">Kritik & Saran</h1>
            <p class="mt-2 text-sm text-[#1b1b1870]">
                Sampaikan kritik, saran, atau masukan Anda untuk meningkatkan pelayanan Kantor Urusan Agama.
            </p>
        </div>

        @if (session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-lg border border-teal-100 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('kritik-saran.store') }}">
                @csrf

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="nama" value="Nama Lengkap *" />
                        <x-text-input id="nama" name="nama" class="mt-1 block w-full" required maxlength="150"
                                      value="{{ old('nama') }}" />
                    </div>
                    <div>
                        <x-input-label for="kontak" value="Kontak (email / WA, opsional)" />
                        <x-text-input id="kontak" name="kontak" class="mt-1 block w-full" maxlength="150"
                                      value="{{ old('kontak') }}" placeholder="08123456789" />
                    </div>
                </div>

                <div class="mt-4">
                    <x-input-label for="kategori" value="Kategori" />
                    <select id="kategori" name="kategori"
                            class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">
                        <option value="">Pilih kategori...</option>
                        @foreach (\App\Models\KritikSaran::KATEGORI as $kategori)
                            <option value="{{ $kategori }}" @selected(old('kategori') === $kategori)>{{ $kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4">
                    <x-input-label for="isi" value="Isi Kritik / Saran *" />
                    <textarea id="isi" name="isi" rows="6" required maxlength="5000"
                              class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm"
                              placeholder="Tulis kritik, saran, atau masukan Anda di sini...">{{ old('isi') }}</textarea>
                </div>

                <div class="mt-6">
                    <button type="submit"
                            class="rounded-md bg-teal-700 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-teal-800">
                        Kirim Kritik & Saran
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection
