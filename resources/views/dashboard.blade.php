<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">Total Surat</div>
                    <div class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_surat'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">Surat Terbit Bulan Ini</div>
                    <div class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['surat_bulan_ini'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">Menunggu Persetujuan</div>
                    <div class="text-3xl font-bold {{ $stats['menunggu_persetujuan'] ? 'text-yellow-600' : 'text-gray-800' }} mt-1">{{ $stats['menunggu_persetujuan'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">Permohonan Baru</div>
                    <div class="text-3xl font-bold {{ $stats['permohonan_baru'] ? 'text-blue-600' : 'text-gray-800' }} mt-1">{{ $stats['permohonan_baru'] }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                <div class="lg:col-span-1 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Surat per Status</h3>
                    <ul class="space-y-2 text-sm">
                        @foreach ($perStatus as $item)
                            <li class="flex justify-between">
                                <span class="text-gray-600">{{ $item['label'] }}</span>
                                <span class="font-semibold">{{ $item['count'] }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <h3 class="font-semibold text-gray-800 mt-6 mb-3">Surat per Jenis (Top 5)</h3>
                    <ul class="space-y-2 text-sm">
                        @forelse ($perJenis as $type)
                            <li class="flex justify-between">
                                <span class="text-gray-600">{{ $type->name }}</span>
                                <span class="font-semibold">{{ $type->letters_count }}</span>
                            </li>
                        @empty
                            <li class="text-gray-400">Belum ada data.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="lg:col-span-1 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-800">Surat Terbaru</h3>
                        <a href="{{ route('letters.index') }}" class="text-xs text-blue-600 hover:underline">Lihat semua</a>
                    </div>
                    <ul class="divide-y divide-gray-100">
                        @forelse ($suratTerbaru as $letter)
                            <li class="py-3">
                                <a href="{{ route('letters.show', $letter) }}" class="text-sm font-medium text-gray-800 hover:text-blue-600">{{ $letter->perihal }}</a>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    {{ $letter->letterType->name }} &bull; {{ $letter->created_at->format('d M Y') }}
                                    @if ($letter->nomor)<span class="font-mono"> &bull; {{ $letter->nomor }}</span>@endif
                                </div>
                            </li>
                        @empty
                            <li class="py-3 text-sm text-gray-400">Belum ada surat.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="lg:col-span-1 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-800">Permohonan Terbaru</h3>
                        <a href="{{ route('submissions.index') }}" class="text-xs text-blue-600 hover:underline">Lihat semua</a>
                    </div>
                    <ul class="divide-y divide-gray-100">
                        @forelse ($permohonanTerbaru as $submission)
                            <li class="py-3">
                                <a href="{{ route('submissions.show', $submission) }}" class="text-sm font-medium text-gray-800 hover:text-blue-600">{{ $submission->nama_pemohon }}</a>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    {{ $submission->letterType->name }} &bull; {{ $submission->created_at->format('d M Y H:i') }}
                                </div>
                            </li>
                        @empty
                            <li class="py-3 text-sm text-gray-400">Belum ada permohonan.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
