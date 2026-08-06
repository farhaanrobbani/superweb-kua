<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kritik & Saran</h2>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontak</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($feedbacks as $feedback)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $feedback->nama }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $feedback->kontak ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full bg-teal-100 text-teal-700">{{ $feedback->kategori ?? 'Umum' }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $feedback->created_at->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    <a href="{{ route('kritik-saran.show', $feedback) }}" class="text-blue-600 hover:underline">Detail</a>
                                    <form action="{{ route('kritik-saran.destroy', $feedback) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus kritik/saran dari {{ $feedback->nama }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada kritik/saran masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $feedbacks->links() }}</div>
        </div>
    </div>
</x-app-layout>
