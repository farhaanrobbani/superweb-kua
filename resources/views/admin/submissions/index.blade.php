<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Permohonan Surat</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form method="GET" class="mb-4 flex flex-wrap items-end gap-3">
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
                    <label class="text-xs text-gray-500">Cari</label>
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Nama pemohon"
                           class="rounded-md border-gray-300 text-sm">
                </div>
                <button class="px-3 py-2 bg-gray-800 text-white text-xs rounded-md">Filter</button>
            </form>

            <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pemohon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Surat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($submissions as $submission)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $submission->nama_pemohon }}</div>
                                    <div class="text-xs text-gray-500">{{ $submission->kontak }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $submission->letterType->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $submission->created_at->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $color = match ($submission->status) {
                                            'baru' => 'bg-yellow-100 text-yellow-700',
                                            'diproses' => 'bg-blue-100 text-blue-700',
                                            'selesai' => 'bg-green-100 text-green-700',
                                            'ditolak' => 'bg-red-100 text-red-700',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded-full {{ $color }}">{{ $statuses[$submission->status] }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    <a href="{{ route('submissions.show', $submission) }}" class="text-blue-600 hover:underline">Detail</a>
                                    <form action="{{ route('submissions.destroy', $submission) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus permohonan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada permohonan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $submissions->links() }}</div>
        </div>
    </div>
</x-app-layout>
