<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Navbar</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Urutan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Label</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">URL</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($mainItems as $item)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $item->sort_order }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <span class="font-medium">{{ $item->label }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm font-mono text-gray-500">{{ $item->url ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $item->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $item->active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('navbar.edit', $item) }}" class="text-blue-600 hover:underline">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada item navbar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <h3 class="mt-8 text-lg font-semibold text-gray-800">Sub Menu Tentang Kami</h3>
            <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg mt-2">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Urutan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Label</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">URL</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($tentangItems as $item)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $item->sort_order }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <span class="font-medium">{{ $item->label }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm font-mono text-gray-500">{{ $item->url ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $item->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $item->active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('navbar.edit', $item) }}" class="text-blue-600 hover:underline">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada item.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8 mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Sub Menu Layanan</h3>
                <a href="{{ route('services.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-teal-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-600">
                    + Tambah Layanan
                </a>
            </div>
            <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Urutan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">URL</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($services as $service)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $service->sort_order }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <span class="font-medium">{{ $service->name }}</span>
                                    @if ($service->description)
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $service->description }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-mono text-gray-500">{{ $service->url ?? '-' }}
                                    @if ($service->embed_url)
                                        <span class="ml-2 rounded-full bg-purple-100 text-purple-700 px-2 py-0.5 text-xs font-sans">embed</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $service->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $service->active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    <a href="{{ route('services.edit', $service) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('services.destroy', $service) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus layanan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada layanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $services->links() }}</div>
        </div>
    </div>
</x-app-layout>
