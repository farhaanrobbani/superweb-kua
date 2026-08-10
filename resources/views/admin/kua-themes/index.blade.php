<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-100">Tema Pekerjaan</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm dark:bg-green-900/30 dark:border-green-800 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('kua-daily.index') }}"
                   class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:bg-gray-700/40 dark:text-teal-400">
                    &larr; Kembali ke Master Data Harian
                </a>

                <a href="{{ route('kua-themes.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-teal-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-600">
                    + Tambah Tema
                </a>
            </div>

            <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg dark:bg-gray-800">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/40">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">#</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Tema Pekerjaan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Key</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @forelse ($themes as $theme)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">{{ $theme->sort_order + 1 }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-gray-100">{{ $theme->label }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">{{ $theme->key }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if ($theme->active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm space-x-3 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <form action="{{ route('kua-themes.move', $theme) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="direction" value="up" />
                                            <button class="text-gray-500 hover:text-teal-600 dark:text-gray-400" title="Naik">↑</button>
                                        </form>
                                        <form action="{{ route('kua-themes.move', $theme) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="direction" value="down" />
                                            <button class="text-gray-500 hover:text-teal-600 dark:text-gray-400" title="Turun">↓</button>
                                        </form>
                                    </div>
                                    <a href="{{ route('kua-themes.edit', $theme) }}" class="text-blue-600 hover:underline dark:text-blue-400">Edit</a>
                                    <form action="{{ route('kua-themes.destroy', $theme) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus permanen tema &quot;{{ $theme->label }}&quot; beserta nilainya pada semua data harian?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline dark:text-red-400">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Belum ada tema pekerjaan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
