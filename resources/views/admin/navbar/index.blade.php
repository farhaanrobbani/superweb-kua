<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">Navbar</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 dark:bg-green-900/30 dark:border-green-800 dark:text-green-300 px-4 py-3 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Item Navbar</h3>
                <a href="{{ route('navbar.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-teal-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-600">
                    + Tambah Item Navbar
                </a>
            </div>
            <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg dark:bg-gray-800">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/40">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 dark:text-gray-500 uppercase">Urutan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 dark:text-gray-500 uppercase">Label</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 dark:text-gray-500 uppercase">URL</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 dark:text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 dark:text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                        @forelse ($mainItems as $item)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">{{ $item->sort_order }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    <span class="font-medium">{{ $item->label }}</span>
                                    @if ($item->has_submenu)
                                        <span class="ml-2 rounded-full bg-teal-100 text-teal-700 dark:text-teal-400 px-2 py-0.5 text-xs">sub menu</span>
                                    @endif
                                    @if ($item->description)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 dark:text-gray-500 mt-0.5">{{ $item->description }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-mono text-gray-500 dark:text-gray-400 dark:text-gray-500">{{ $item->url ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $item->active ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:text-gray-500 dark:text-gray-300 dark:text-gray-500' }}">
                                        {{ $item->active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    <a href="{{ route('navbar.edit', $item) }}" class="text-blue-600 dark:text-blue-400 hover:underline">Edit</a>
                                    <form action="{{ route('navbar.destroy', $item) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus item navbar ini beserta sub menu-nya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">Belum ada item navbar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @foreach ($mainItems as $parent)
                @if ($parent->has_submenu)
                    <div class="mt-8 mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Sub Menu {{ $parent->label }}</h3>
                        <a href="{{ route('navbar.sub.create', $parent) }}"
                           class="inline-flex items-center px-4 py-2 bg-teal-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-600">
                            + Tambah Sub Menu {{ $parent->label }}
                        </a>
                    </div>
                    <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/40">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 dark:text-gray-500 uppercase">Urutan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 dark:text-gray-500 uppercase">Label</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 dark:text-gray-500 uppercase">URL</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 dark:text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 dark:text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                                @forelse ($parent->children as $child)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">{{ $child->sort_order }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            <span class="font-medium">{{ $child->label }}</span>
                                            @if ($child->description)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 dark:text-gray-500 mt-0.5">{{ $child->description }}</p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm font-mono text-gray-500 dark:text-gray-400 dark:text-gray-500">{{ $child->url ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $child->active ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:text-gray-500 dark:text-gray-300 dark:text-gray-500' }}">
                                                {{ $child->active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm space-x-2">
                                            <a href="{{ route('navbar.sub.edit', $child) }}" class="text-blue-600 dark:text-blue-400 hover:underline">Edit</a>
                                            <form action="{{ route('navbar.sub.destroy', $child) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Hapus sub menu ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">Belum ada sub menu. Klik "+ Tambah Sub Menu {{ $parent->label }}" untuk menambah.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</x-app-layout>
