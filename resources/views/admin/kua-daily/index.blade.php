<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Master Data Harian KUA</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex flex-wrap items-center justify-between gap-4">
                <form method="GET" action="{{ route('kua-daily.index') }}" class="flex flex-wrap items-end gap-3">
                    <div>
                        <x-input-label for="bulan" value="Bulan" />
                        <select name="bulan" id="bulan"
                                class="mt-1 border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}" @selected($m === $month)>{{ tanggal_indonesia(now()->month($m), 'F') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="tahun" value="Tahun" />
                        <input type="number" name="tahun" id="tahun" value="{{ $year }}" min="2000" max="2100"
                               class="mt-1 block w-28 border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm" />
                    </div>
                    <x-primary-button>Tampilkan</x-primary-button>
                </form>

                <a href="{{ route('kua-daily.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-teal-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-600">
                    + Input Data Harian
                </a>
            </div>

            <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            @foreach ($columns as $label)
                                <th class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase whitespace-nowrap">{{ $label }}</th>
                            @endforeach
                            <th class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($data as $item)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">
                                    {{ tanggal_indonesia($item->tanggal, 'd F Y') }}
                                </td>
                                @foreach ($columns as $key => $label)
                                    <td class="px-3 py-3 text-sm text-gray-700 text-right">{{ $item->{$key} }}</td>
                                @endforeach
                                <td class="px-3 py-3 text-sm font-semibold text-teal-700 text-right">{{ $item->totalVolume() }}</td>
                                <td class="px-4 py-3 text-sm space-x-2 whitespace-nowrap">
                                    <a href="{{ route('kua-daily.edit', $item) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('kua-daily.destroy', $item) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus data harian tanggal {{ tanggal_indonesia($item->tanggal, 'd F Y') }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($columns) + 3 }}" class="px-6 py-8 text-center text-sm text-gray-500">
                                    Belum ada data harian pada {{ tanggal_indonesia(now()->month($month)->year($year), 'F Y') }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
