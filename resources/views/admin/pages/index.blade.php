<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Page</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex justify-end">
                <a href="{{ route('pages.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-teal-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-600">
                    + Tambah Page
                </a>
            </div>

            <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kunci</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($pages as $page)
                            <tr>
                                <td class="px-6 py-4 text-sm font-mono text-gray-500">{{ $page->key }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $page->title }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    @if ($page->description)
                                        {{ str($page->description)->limit(70) }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $page->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $page->active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    <a href="{{ route('pages.edit', $page) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('pages.destroy', $page) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus halaman ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada halaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $pages->links() }}</div>
        </div>
    </div>
</x-app-layout>