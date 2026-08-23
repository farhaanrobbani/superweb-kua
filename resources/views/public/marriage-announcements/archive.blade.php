@extends('layouts.public')

@section('title', 'Arsip — '.(kua_navbar_page_label('pengumuman-nikah', $page->title ?? 'Pengumuman Kehendak Nikah')))

@section('content')
    <section class="mx-auto max-w-5xl px-6 pb-16 pt-12">
        <div>
            <h1 class="text-center text-2xl font-bold">Arsip Pengumuman Kehendak Nikah</h1>
            <p class="mx-auto mt-2 max-w-2xl text-center text-sm text-[#1b1b1870]">
                Riwayat akad yang telah berlalu.
            </p>
        </div>

        <div class="mt-6">
            <a href="{{ route('pengumuman-nikah.index') }}" class="text-sm text-teal-700 hover:underline">← Kembali ke Pengumuman</a>
        </div>

        <form method="GET" action="{{ route('pengumuman-nikah.arsip') }}" class="mt-4 rounded-lg border border-teal-100 bg-white p-4">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                <div>
                    <label for="dari" class="block text-xs font-medium text-gray-700">Dari Tanggal</label>
                    <input type="date" id="dari" name="dari" value="{{ request('dari') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-teal-500 focus:ring-teal-500">
                </div>
                <div>
                    <label for="sampai" class="block text-xs font-medium text-gray-700">Sampai Tanggal</label>
                    <input type="date" id="sampai" name="sampai" value="{{ request('sampai') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-teal-500 focus:ring-teal-500">
                </div>
                <div>
                    <label for="q" class="block text-xs font-medium text-gray-700">Pencarian</label>
                    <input type="text" id="q" name="q" value="{{ request('q') }}"
                           placeholder="Nomor pendaftaran, nama…"
                           class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-teal-500 focus:ring-teal-500">
                </div>
            </div>
            <div class="mt-3 flex gap-2">
                <button type="submit" class="rounded-md bg-teal-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-teal-600">Terapkan</button>
                <a href="{{ route('pengumuman-nikah.arsip') }}" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Reset</a>
            </div>
        </form>

        @if ($announcements->isNotEmpty())
            <div class="mt-6 overflow-hidden rounded-lg border border-teal-100 bg-white shadow-sm">
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
                                    <td class="px-4 py-4 text-center text-[#1b1b1870]">{{ $announcements->firstItem() + $i }}</td>
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

            <div class="mt-4">{{ $announcements->links() }}</div>
        @elseif (request()->filled('q') || request()->filled('dari') || request()->filled('sampai'))
            <div class="mt-6 rounded-lg border border-teal-100 bg-white p-8 text-center text-sm text-[#1b1b1870]">
                Tidak ada arsip yang sesuai filter.
            </div>
        @else
            <div class="mt-6 rounded-lg border border-teal-100 bg-white p-8 text-center text-sm text-[#1b1b1870]">
                Belum ada arsip pengumuman kehendak nikah.
            </div>
        @endif
    </section>
@endsection
