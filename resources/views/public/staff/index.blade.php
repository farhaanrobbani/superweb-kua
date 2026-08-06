@extends('layouts.public')

@section('title', 'Daftar Pegawai — '.kua_setting('instansi', 'KUA'))

@section('content')
    <section class="mx-auto max-w-5xl px-6 pb-16 pt-14">
        <div class="text-center">
            <p class="text-sm font-medium uppercase tracking-widest text-teal-700">Kantor Urusan Agama {{ kua_setting('kecamatan') }}</p>
            <h1 class="mt-3 text-3xl font-bold sm:text-4xl">Struktur Organisasi</h1>
        </div>

        @forelse ($groups as $bagian => $members)
            <div class="mt-10">
                <h2 class="flex items-center gap-3 text-lg font-bold text-teal-900">
                    <span class="h-6 w-1 rounded-full bg-teal-700"></span>
                    {{ $bagian }}
                </h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($members as $staff)
                        <div class="flex items-center gap-4 rounded-lg border border-teal-100 bg-white p-5 shadow-sm">
                            @if ($staff->fotoUrl())
                                <img src="{{ $staff->fotoUrl() }}" alt="{{ $staff->nama }}"
                                     class="h-16 w-16 shrink-0 rounded-full border border-gray-200 object-cover" />
                            @else
                                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-teal-100 text-xl font-bold text-teal-700">
                                    {{ str($staff->nama)->charAt(0) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-gray-900">{{ $staff->nama }}</p>
                                <p class="text-sm text-teal-700">{{ $staff->jabatan }}</p>
                                @if ($staff->nip)
                                    <p class="mt-1 text-xs text-[#1b1b1870]">NIP. {{ $staff->nip }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="mt-10 text-center text-sm text-[#1b1b1870]">Belum ada data pegawai.</div>
        @endforelse
    </section>

    @if ($all->isNotEmpty())
        <section class="mx-auto max-w-5xl px-6 pb-16">
            <div class="flex items-center gap-3">
                <span class="h-6 w-1 rounded-full bg-teal-700"></span>
                <h2 class="text-lg font-bold text-teal-900">Data Pegawai</h2>
            </div>
            <div class="mt-4 overflow-x-auto rounded-lg border border-teal-100 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIP</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jabatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pangkat/Golongan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontak</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($all as $index => $staff)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $staff->nama }}</td>
                                <td class="px-6 py-4 text-sm font-mono text-gray-500">{{ $staff->nip ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $staff->jabatan }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $staff->pangkat_golongan ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $staff->kontak ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endif
@endsection
