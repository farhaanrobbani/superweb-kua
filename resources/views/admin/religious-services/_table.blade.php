<div class="mb-4 flex justify-end">
    <a href="{{ route('religious-services.create') }}"
       class="inline-flex items-center px-4 py-2 bg-teal-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-600">
        + Tambah Topik
    </a>
</div>

<div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg dark:bg-gray-800">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700/40">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 dark:text-gray-500 uppercase">Topik</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 dark:text-gray-500 uppercase">Deskripsi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 dark:text-gray-500 uppercase">Urutan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 dark:text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 dark:text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
            @forelse ($religiousServices as $item)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-md bg-teal-50 text-teal-700 dark:text-teal-400">
                                @include('partials.service-icon', ['icon' => $item->icon, 'class' => 'h-5 w-5'])
                            </span>
                            <div>
                                <span class="font-medium">{{ $item->name }}</span>
                                @if ($item->description)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 dark:text-gray-500 mt-0.5">{{ str($item->description)->limit(70) }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">
                        @if ($item->persyaratan || $item->alur || $item->sop)
                            <span class="text-xs text-blue-600 dark:text-blue-400">Ada</span>
                        @else
                            <span class="text-xs text-gray-400 dark:text-gray-500">Belum ada</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">{{ $item->sort_order }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $item->active ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:text-gray-500 dark:text-gray-300 dark:text-gray-500' }}">
                            {{ $item->active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm space-x-2">
                        <a href="{{ route('religious-services.edit', $item) }}" class="text-blue-600 dark:text-blue-400 hover:underline">Edit</a>
                        <form action="{{ route('religious-services.destroy', $item) }}" method="POST" class="inline"
                              onsubmit="return confirm('Hapus topik ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400 dark:text-gray-500">Belum ada topik.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $religiousServices->links() }}</div>
