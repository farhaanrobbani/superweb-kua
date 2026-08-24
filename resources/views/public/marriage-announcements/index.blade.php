@extends('layouts.public')

@section('title', kua_setting('instansi', 'Surat Digital KUA').' — '.kua_navbar_page_label('pengumuman-nikah', $page->title ?? 'Pengumuman Kehendak Nikah'))

@section('content')
    <section class="mx-auto max-w-5xl px-6 pb-16 pt-12">
        <div>
            <h1 class="text-center text-2xl font-bold">{{ $page->title ?? 'Pengumuman Kehendak Nikah' }}</h1>
            <p class="mx-auto mt-2 max-w-2xl text-center text-sm text-[#1b1b1870]">
                {{ $page->description ?? 'Berdasarkan Pasal 9 PMA No. 30 Tahun 2024, kami mengumumkan kehendak nikah calon pasangan berikut. Apabila ada yang menghalangi atau mengetahui adanya penghalang perkawinan, dapat menyampaikannya kepada KUA.' }}
            </p>
        </div>

        @if ($announcements->isNotEmpty())
            <div class="mt-6 flex items-center justify-between gap-4 text-xs text-[#1b1b1870]">
                <span>Terakhir diperbarui: {{ now()->translatedFormat('d F Y') }}</span>
                <a href="{{ route('pengumuman-nikah.arsip') }}" class="shrink-0 font-medium text-teal-700 hover:underline">Lihat Arsip →</a>
            </div>

            <div class="mt-4">
                @include('partials.ringkasan-jadwal', ['announcements' => $announcements])
            </div>

            <div class="mt-4 overflow-hidden rounded-lg border border-teal-100 bg-white shadow-sm">
                <div class="flex items-center justify-between gap-4 border-b border-teal-100 bg-teal-50/60 px-5 py-3">
                    <p class="text-xs font-semibold uppercase tracking-wide text-teal-800">
                        Daftar Pengumuman Kehendak Nikah — KUA {{ kua_setting('kecamatan', '') }}{{ kua_setting('kabupaten') ? ', '.kua_setting('kabupaten') : '' }}
                    </p>
                    <a href="{{ route('pengumuman-nikah.arsip') }}"
                       class="shrink-0 text-xs font-medium text-teal-700 hover:underline">Lihat Daftar Lengkap →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[860px] text-sm">
                        <thead>
                            <tr class="border-b border-teal-100 bg-gray-50/60 text-start text-xs uppercase tracking-wide text-[#1b1b1870]">
                                <th class="px-4 py-3 text-center font-semibold">No</th>
                                <th class="px-4 py-3 text-start font-semibold">No. Pendaftaran</th>
                                <th class="px-4 py-3 text-start font-semibold">Calon Mempelai Pria</th>
                                <th class="px-4 py-3 text-start font-semibold">Calon Mempelai Wanita</th>
                                <th class="px-4 py-3 text-start font-semibold">Wali Nikah</th>
                                <th class="px-4 py-3 text-start font-semibold">Tanggal Akad</th>
                                <th class="px-4 py-3 text-start font-semibold">Tempat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($announcements as $i => $item)
                                <tr class="border-b border-teal-50 align-top last:border-b-0 odd:bg-white even:bg-teal-50/30">
                                    <td class="px-4 py-4 text-center text-[#1b1b1870]">{{ $i + 1 }}</td>
                                    <td class="px-4 py-4 font-mono text-xs text-[#1b1b1870]">{{ $item->no_pendaftaran ?: '—' }}</td>
                                    <td class="px-4 py-4">
                                        <span class="block font-semibold text-teal-900">{{ $item->namaLengkapPria() }}</span>
                                        @if ($item->alamat_pria)
                                            <span class="mt-0.5 block text-xs text-[#1b1b1870]">{{ $item->alamat_pria }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="block font-semibold text-teal-900">{{ $item->namaLengkapWanita() }}</span>
                                        @if ($item->alamat_wanita)
                                            <span class="mt-0.5 block text-xs text-[#1b1b1870]">{{ $item->alamat_wanita }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">{{ $item->status_wali ?: '—' }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap">{{ tanggal_indonesia($item->tanggal_akad) }}</td>
                                    <td class="px-4 py-4">{{ $item->tempat_nikah ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @else
            <div class="mt-8 rounded-lg border border-teal-100 bg-white p-8 text-center text-sm text-[#1b1b1870]">
                Belum ada pengumuman kehendak nikah.
            </div>
        @endif
    </section>
@endsection
