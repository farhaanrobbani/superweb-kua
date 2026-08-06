<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Permohonan</h2>
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
                    $color = match ($submission->status) {
                        'baru' => 'bg-yellow-100 text-yellow-700',
                        'diproses' => 'bg-blue-100 text-blue-700',
                        'selesai' => 'bg-green-100 text-green-700',
                        'ditolak' => 'bg-red-100 text-red-700',
                    };
                @endphp
                <span class="px-3 py-1 text-sm rounded-full {{ $color }}">{{ \App\Models\Submission::statuses()[$submission->status] }}</span>
                <a href="{{ route('submissions.index') }}" class="text-sm text-gray-500 hover:underline">Kembali</a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Data Permohonan</h3>
                    <dl class="space-y-3 text-sm">
                        <div><dt class="text-gray-500">Pemohon</dt><dd class="font-medium">{{ $submission->nama_pemohon }}</dd></div>
                        <div><dt class="text-gray-500">Kontak</dt><dd class="font-medium">{{ $submission->kontak }}</dd></div>
                        <div><dt class="text-gray-500">Jenis Surat</dt><dd class="font-medium">{{ $submission->letterType->name }}</dd></div>
                        <div><dt class="text-gray-500">Diajukan</dt><dd class="font-medium">{{ $submission->created_at->format('d M Y H:i') }}</dd></div>
                        @if ($submission->catatan)
                            <div><dt class="text-gray-500">Catatan</dt><dd class="text-red-600">{{ $submission->catatan }}</dd></div>
                        @endif
                    </dl>

                    <h4 class="font-semibold text-gray-800 mt-6 mb-3">Data Surat yang Diminta</h4>
                    <dl class="grid grid-cols-1 gap-3 text-sm">
                        @foreach ($submission->letterType->fields ?? [] as $field)
                            <div>
                                <dt class="text-gray-500">{{ $field['label'] }}</dt>
                                <dd class="font-medium">{{ $submission->data[$field['name']] ?? '—' }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>

                <div class="space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-semibold text-gray-800 mb-4">Perbarui Status</h3>
                        <form method="POST" action="{{ route('submissions.update', $submission) }}">
                            @csrf
                            @method('PUT')
                            <div>
                                <x-input-label for="status" value="Status" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">
                                    @foreach ($statuses = \App\Models\Submission::statuses() as $key => $label)
                                        <option value="{{ $key }}" {{ $submission->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-4">
                                <x-input-label for="catatan" value="Catatan (untuk penolakan)" />
                                <textarea id="catatan" name="catatan" rows="3"
                                          class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">{{ old('catatan', $submission->catatan) }}</textarea>
                            </div>
                            <div class="mt-4">
                                <x-primary-button>Simpan Status</x-primary-button>
                            </div>
                        </form>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-semibold text-gray-800 mb-2">Buat Surat</h3>
                        <p class="text-xs text-gray-500 mb-4">Cetak surat permohonan untuk arsip KUA, lalu buat draft surat dengan data permohonan yang sudah terisi otomatis.</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('submissions.cetak-permohonan', $submission) }}"
                               class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-500">
                                Cetak Surat Permohonan (arsip)
                            </a>
                            <form method="POST" action="{{ route('submissions.buat-surat', $submission) }}">
                                @csrf
                                <button class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Buat Surat dari Permohonan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
