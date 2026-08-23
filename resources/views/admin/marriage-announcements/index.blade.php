<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">Pengumuman Kehendak Nikah</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 dark:bg-green-900/30 dark:border-green-800 dark:text-green-300 px-4 py-3 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex justify-between items-center">
                <a href="{{ kua_navbar_page_url('pengumuman-nikah', '/pengumuman-nikah') }}" target="_blank"
                   class="text-sm text-teal-700 dark:text-teal-400 hover:underline">Lihat halaman publik ↗</a>
                <a href="{{ route('marriage-announcements.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-teal-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-600">
                    + Tambah Pengumuman
                </a>
            </div>

            <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg dark:bg-gray-800">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/40">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Pendaftaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Calon Mempelai Pria</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Calon Mempelai Wanita</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Akad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tempat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                        @forelse ($announcements as $item)
                            <tr>
                                <td class="px-6 py-4 text-xs font-mono text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                    {{ $item->no_pendaftaran ?: '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    <span class="font-medium">{{ $item->namaLengkapPria() }}</span>
                                    @if ($item->alamat_pria)
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $item->alamat_pria }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    <span class="font-medium">{{ $item->namaLengkapWanita() }}</span>
                                    @if ($item->alamat_wanita)
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $item->alamat_wanita }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-500">
                                    {{ tanggal_indonesia($item->tanggal_akad) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $item->tempat_nikah ?: '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if (! $item->active)
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">Nonaktif</span>
                                    @elseif ($item->isBerlalu())
                                        <span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-300">Berlalu</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300">Aktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm space-x-2 whitespace-nowrap">
                                    <a href="{{ route('marriage-announcements.edit', $item) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('marriage-announcements.destroy', $item) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus pengumuman ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada pengumuman kehendak nikah.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $announcements->links() }}</div>
        </div>
    </div>
</x-app-layout>
