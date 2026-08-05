<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Arsip Surat</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <form method="GET" class="flex flex-wrap items-end gap-3">
                    <div>
                        <label class="text-xs text-gray-500">Jenis</label>
                        <select name="jenis" class="rounded-md border-gray-300 text-sm">
                            <option value="">Semua</option>
                            @foreach ($letterTypes as $type)
                                <option value="{{ $type->id }}" {{ request('jenis') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Status</label>
                        <select name="status" class="rounded-md border-gray-300 text-sm">
                            <option value="">Semua</option>
                            @foreach ($statuses as $key => $label)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Tahun</label>
                        <select name="tahun" class="rounded-md border-gray-300 text-sm">
                            <option value="">Semua</option>
                            @foreach ($years as $year)
                                <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Cari</label>
                        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Nomor / perihal"
                               class="rounded-md border-gray-300 text-sm">
                    </div>
                    <button class="px-3 py-2 bg-gray-800 text-white text-xs rounded-md">Filter</button>
                </form>
                <a href="{{ route('letters.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    + Buat Surat
                </a>
            </div>

            <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Perihal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dibuat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($letters as $letter)
                            <tr>
                                <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $letter->nomor ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">{{ $letter->perihal }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $letter->letterType->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $letter->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $color = match ($letter->status) {
                                            'draft' => 'bg-gray-100 text-gray-600',
                                            'diajukan' => 'bg-yellow-100 text-yellow-700',
                                            'disetujui' => 'bg-blue-100 text-blue-700',
                                            'terbit' => 'bg-green-100 text-green-700',
                                            'ditolak' => 'bg-red-100 text-red-700',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded-full {{ $color }}">{{ $statuses[$letter->status] }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    <a href="{{ route('letters.show', $letter) }}" class="text-blue-600 hover:underline">Detail</a>
                                    @if ($letter->status === 'terbit')
                                        <a href="{{ route('letters.pdf', $letter) }}" class="text-teal-600 hover:underline">PDF</a>
                                    @endif
                                    <form action="{{ route('letters.destroy', $letter) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus surat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada surat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $letters->links() }}</div>
        </div>
    </div>
</x-app-layout>
