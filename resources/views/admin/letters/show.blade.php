<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Surat</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex items-center gap-3">
                @php
                    $color = match ($letter->status) {
                        'draft' => 'bg-gray-100 text-gray-600',
                        'diajukan' => 'bg-yellow-100 text-yellow-700',
                        'disetujui' => 'bg-blue-100 text-blue-700',
                        'terbit' => 'bg-green-100 text-green-700',
                        'ditolak' => 'bg-red-100 text-red-700',
                    };
                @endphp
                <span class="px-3 py-1 text-sm rounded-full {{ $color }}">{{ \App\Models\Letter::statuses()[$letter->status] }}</span>
                @if ($letter->nomor)
                    <span class="text-sm font-mono text-gray-600">{{ $letter->nomor }}</span>
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-500">Jenis Surat:</span> <span class="font-medium">{{ $letter->letterType->name }}</span></div>
                    <div><span class="text-gray-500">Perihal:</span> <span class="font-medium">{{ $letter->perihal }}</span></div>
                    <div><span class="text-gray-500">Nomor Surat:</span> <span class="font-medium">{{ $letter->nomor ?? '—' }}</span></div>
                    <div><span class="text-gray-500">Tanggal Surat:</span> <span class="font-medium">{{ $letter->tanggal_surat ? $letter->tanggal_surat->format('d M Y') : '—' }}</span></div>
                    <div><span class="text-gray-500">Dibuat oleh:</span> {{ $letter->creator?->name ?? '—' }} ({{ $letter->created_at->format('d M Y H:i') }})</div>
                    <div><span class="text-gray-500">Disetujui oleh:</span> {{ $letter->approver?->name ?? '—' }} {{ $letter->approved_at ? '(' . $letter->approved_at->format('d M Y H:i') . ')' : '' }}</div>
                    @if ($letter->keterangan)
                        <div class="sm:col-span-2"><span class="text-gray-500">Catatan:</span> <span class="text-red-600">{{ $letter->keterangan }}</span></div>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">Data Surat</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    @foreach ($letter->letterType->fields ?? [] as $field)
                        <div>
                            <dt class="text-gray-500">{{ $field['label'] }}</dt>
                            <dd class="font-medium text-gray-800">{{ $letter->data[$field['name']] ?? '—' }}</dd>
                        </div>
                    @endforeach
                </dl>
                @if (count($letter->metaRows()) > 0)
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mt-4 border-t border-gray-100 pt-4">
                        @foreach ($letter->metaRows() as $metaRow)
                            <div>
                                <dt class="text-gray-500">{{ $metaRow['label'] ?? '—' }}</dt>
                                <dd class="font-medium text-gray-800">{{ $metaRow['value'] ?? '' }}</dd>
                            </div>
                        @endforeach
                    </dl>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-3">
                @if ($letter->status === \App\Models\Letter::STATUS_DRAFT)
                    <form method="POST" action="{{ route('letters.ajukan', $letter) }}">
                        @csrf
                        <x-primary-button>Ajukan ke Kepala KUA</x-primary-button>
                    </form>
                    <a href="{{ route('letters.edit', $letter) }}" class="text-sm text-blue-600 hover:underline">Edit</a>
                @endif

                @if ($letter->status === \App\Models\Letter::STATUS_DIAJUKAN && auth()->user()->isKepala())
                    <form method="POST" action="{{ route('letters.setujui', $letter) }}">
                        @csrf
                        <x-primary-button>Setujui</x-primary-button>
                    </form>
                    <a href="{{ route('letters.reject', $letter) }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">Tolak</a>
                @endif

                @if ($letter->status === \App\Models\Letter::STATUS_DISETUJUI)
                    <form method="POST" action="{{ route('letters.terbitkan', $letter) }}">
                        @csrf
                        <x-primary-button>Terbitkan</x-primary-button>
                    </form>
                @endif

                @if ($letter->status === \App\Models\Letter::STATUS_TERBIT)
                    <a href="{{ route('letters.pdf', $letter) }}"
                       class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-500">
                        Unduh PDF
                    </a>
                @endif

                <a href="{{ route('letters.index') }}" class="text-sm text-gray-600 hover:underline">Kembali ke arsip</a>
            </div>
        </div>
    </div>
</x-app-layout>
