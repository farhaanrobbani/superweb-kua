@extends('layouts.public')

@section('title', kua_setting('instansi', 'Surat Digital KUA').' — '.kua_navbar_page_label('pengumuman-nikah', $page->title ?? 'Pengumuman Kehendak Nikah'))

@section('content')
    <section class="mx-auto max-w-4xl px-6 pb-16 pt-12">
        <div class="print:hidden">
            <h1 class="text-center text-2xl font-bold">{{ $page->title ?? 'Pengumuman Kehendak Nikah' }}</h1>
            <p class="mx-auto mt-2 max-w-2xl text-center text-sm text-[#1b1b1870]">
                {{ $page->description ?? 'Berdasarkan Pasal 9 PMA No. 30 Tahun 2024, kami mengumumkan kehendak nikah calon pasangan berikut. Apabila ada yang menghalangi atau mengetahui adanya penghalang perkawinan, dapat menyampaikannya kepada KUA.' }}
            </p>
        </div>

        @if ($announcements->isNotEmpty())
            <div class="mt-6 flex justify-end print:hidden">
                <button type="button" onclick="window.print()"
                        class="inline-flex items-center gap-2 rounded-md bg-teal-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-teal-600">
                    🖨️ Cetak
                </button>
            </div>

            <div class="mt-4 overflow-hidden rounded-lg border border-teal-100 bg-white shadow-sm">
                <p class="border-b border-teal-100 bg-teal-50/60 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-teal-800">
                    Pengumuman Kehendak Nikah — KUA {{ kua_setting('kecamatan', '') }}{{ kua_setting('kabupaten') ? ', '.kua_setting('kabupaten') : '' }}
                </p>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[640px] text-sm">
                        <thead>
                            <tr class="border-b border-teal-100 text-start text-xs uppercase tracking-wide text-[#1b1b1870]">
                                <th class="px-4 py-3 text-center font-semibold">No</th>
                                <th class="px-4 py-3 text-start font-semibold">Calon Mempelai Pria</th>
                                <th class="px-4 py-3 text-start font-semibold">Calon Mempelai Wanita</th>
                                <th class="px-4 py-3 text-start font-semibold">Tanggal Akad</th>
                                <th class="px-4 py-3 text-start font-semibold">Tempat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($announcements as $i => $item)
                                <tr class="border-b border-teal-50 align-top last:border-b-0 odd:bg-white even:bg-teal-50/30">
                                    <td class="px-4 py-4 text-center text-[#1b1b1870]">{{ $i + 1 }}</td>
                                    <td class="px-4 py-4">
                                        <span class="block font-semibold text-teal-900">{{ $item->nama_pria }}</span>
                                        @if ($item->asal_pria)
                                            <span class="mt-0.5 block text-xs text-[#1b1b1870]">{{ $item->asal_pria }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="block font-semibold text-teal-900">{{ $item->nama_wanita }}</span>
                                        @if ($item->asal_wanita)
                                            <span class="mt-0.5 block text-xs text-[#1b1b1870]">{{ $item->asal_wanita }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">{{ tanggal_indonesia($item->tanggal_akad) }}</td>
                                    <td class="px-4 py-4">{{ $item->tempat_nikah ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <p class="mt-4 text-center text-xs text-[#1b1b1870] print:block">
                Terakhir diperbarui: {{ now()->translatedFormat('d F Y') }} WIB
            </p>
        @else
            <div class="mt-8 rounded-lg border border-teal-100 bg-white p-8 text-center text-sm text-[#1b1b1870]">
                Belum ada pengumuman kehendak nikah.
            </div>
        @endif
    </section>

    <style>
        @media print {
            body {
                background: white !important;
            }
        }
    </style>
@endsection
