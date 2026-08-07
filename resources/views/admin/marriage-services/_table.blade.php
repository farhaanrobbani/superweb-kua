<div class="mb-4 flex justify-end">
    <a href="{{ route('marriage-services.create') }}"
       class="inline-flex items-center px-4 py-2 bg-teal-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-600">
        + Tambah Topik
    </a>
</div>

<div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Topik</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alur Permohonan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Urutan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($marriageServices as $item)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-md bg-teal-50 text-teal-700">
                                @include('partials.service-icon', ['icon' => $item->icon, 'class' => 'h-5 w-5'])
                            </span>
                            <div>
                                <span class="font-medium">{{ $item->name }}</span>
                                @if ($item->description)
                                    <p class="text-xs text-gray-500 mt-0.5">{{ str($item->description)->limit(70) }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        @if ($item->target_url)
                            <span class="text-xs text-blue-600">Ada</span>
                        @else
                            <span class="text-xs text-gray-400">Belum ada</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $item->sort_order }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $item->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $item->active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm space-x-2">
                        <a href="{{ route('marriage-services.edit', $item) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('marriage-services.destroy', $item) }}" method="POST" class="inline"
                              onsubmit="return confirm('Hapus topik ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada topik.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $marriageServices->links() }}</div>
