<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Staf</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex justify-end">
                <a href="{{ route('staff.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-teal-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-600">
                    + Tambah Pegawai
                </a>
            </div>

            <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pegawai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIP</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jabatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontak</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($staff as $staffMember)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($staffMember->fotoUrl())
                                            <img src="{{ $staffMember->fotoUrl() }}" alt="{{ $staffMember->nama }}"
                                                 class="h-12 w-12 shrink-0 rounded-full border border-gray-200 object-cover" />
                                        @else
                                            <div class="h-12 w-12 shrink-0 rounded-full bg-teal-100 flex items-center justify-center text-sm font-bold text-teal-700">
                                                {{ str($staffMember->nama)->charAt(0) }}
                                            </div>
                                        @endif
                                        <span class="font-medium text-gray-900">{{ $staffMember->nama }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-mono text-gray-500">{{ $staffMember->nip ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $staffMember->jabatan }}
                                    @if ($staffMember->pangkat_golongan)
                                        <span class="block text-xs text-gray-500">{{ $staffMember->pangkat_golongan }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $staffMember->kontak ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $staffMember->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $staffMember->active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    <a href="{{ route('staff.edit', $staffMember) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('staff.destroy', $staffMember) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus pegawai {{ $staffMember->nama }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada pegawai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $staff->links() }}</div>
        </div>
    </div>
</x-app-layout>
