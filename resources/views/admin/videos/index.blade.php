<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">Video</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 dark:bg-green-900/30 dark:border-green-800 dark:text-green-300 px-4 py-3 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex justify-end">
                <a href="{{ route('videos.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-teal-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-600">
                    + Tambah Video
                </a>
            </div>

            <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg dark:bg-gray-800">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/40">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Terbit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                        @forelse ($videos as $video)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    <div class="flex items-center gap-3">
                                        @if ($video->thumbnailUrl())
                                            <img src="{{ $video->thumbnailUrl() }}" alt="" class="h-12 w-16 shrink-0 rounded-md border border-gray-200 dark:border-gray-700 object-cover" />
                                        @endif
                                        <span class="font-medium">{{ $video->title }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $video->published_at ? tanggal_indonesia($video->published_at, 'd F Y') : 'Segera' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $video->active ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $video->active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    <a href="{{ route('videos.edit', $video) }}" class="text-blue-600 dark:text-blue-400 hover:underline">Edit</a>
                                    <form action="{{ route('videos.destroy', $video) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus video ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada video.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $videos->links() }}</div>
        </div>
    </div>
</x-app-layout>
