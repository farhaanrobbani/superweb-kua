<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buat Surat Baru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (! $selectedType)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Pilih Jenis Surat</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($letterTypes as $type)
                            <a href="{{ route('letters.create', ['jenis' => $type->code]) }}"
                               class="border border-gray-200 rounded-lg p-4 hover:border-teal-400 hover:shadow transition">
                                <div class="font-semibold text-gray-800">{{ $type->name }}</div>
                                <div class="text-sm text-gray-500 mt-1">{{ $type->description }}</div>
                                <div class="text-xs text-gray-400 mt-2 font-mono">{{ count($type->fields ?? []) }} field</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $selectedType->name }}</h3>
                            <a href="{{ route('letters.create') }}" class="text-sm text-gray-500 hover:underline">Ganti jenis</a>
                        </div>

                        @if (isset($dari) && $dari)
                            <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-md text-sm">
                                Data diisi otomatis dari permohonan <strong>{{ $dari->nama_pemohon }}</strong> ({{ $dari->kontak }}). Silakan periksa kembali.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('letters.store') }}">
                            @csrf
                            <input type="hidden" name="jenis" value="{{ $selectedType->code }}">
                            @if (isset($dari) && $dari)
                                <input type="hidden" name="dari" value="{{ $dari->id }}">
                            @endif

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="nomor" value="Nomor Surat" />
                                    <x-text-input id="nomor" name="nomor" class="mt-1 block w-full"
                                                  value="{{ old('nomor') }}"
                                                  placeholder="Contoh: 001/KUA.10.02.07/VIII/2026" />
                                    <x-input-error :messages="$errors->get('nomor')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="tanggal_surat" value="Tanggal Surat" />
                                    <x-text-input id="tanggal_surat" name="tanggal_surat" type="date"
                                                  class="mt-1 block w-full"
                                                  value="{{ old('tanggal_surat', now()->toDateString()) }}" />
                                    <x-input-error :messages="$errors->get('tanggal_surat')" class="mt-2" />
                                </div>
                            </div>

                            <div class="mt-6">
                                <x-input-label for="perihal" value="Perihal Surat" />
                                <x-text-input id="perihal" name="perihal" class="mt-1 block w-full" required
                                              value="{{ $perihal }}" placeholder="Contoh: Permohonan Penerbitan Surat Keterangan" />
                                <x-input-error :messages="$errors->get('perihal')" class="mt-2" />
                            </div>

                            <div class="mt-6 space-y-4">
                                @php($internalShown = false)
                                @foreach ($selectedType->fields ?? [] as $field)
                                    @if (! empty($field['internal']))
                                        @if (! $internalShown)
                                            @php($internalShown = true)
                                            <div class="border-t border-gray-200 pt-4">
                                                <h4 class="font-semibold text-gray-700">Data tambahan (diisi petugas)</h4>
                                                <p class="text-xs text-gray-500 mt-1">Field berikut hanya diisi oleh petugas saat membuat surat.</p>
                                            </div>
                                        @endif
                                    @endif
                                    @php($name = 'data[' . $field['name'] . ']')
                                    @php($value = old('data.' . $field['name'], $data[$field['name']] ?? null))
                                    <div>
                                        <x-input-label :for="'field-' . $field['name']" :value="$field['label'] . ($field['required'] ? ' *' : '')" />
                                        @if ($field['type'] === 'textarea')
                                            <textarea id="field-{{ $field['name'] }}" name="{{ $name }}" rows="3"
                                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">{{ $value }}</textarea>
                                        @elseif ($field['type'] === 'date')
                                            <x-text-input id="field-{{ $field['name'] }}" name="{{ $name }}" type="date"
                                                          class="mt-1 block w-full" :value="$value" />
                                        @elseif ($field['type'] === 'select')
                                            <select id="field-{{ $field['name'] }}" name="{{ $name }}"
                                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">
                                                <option value="">-- Pilih --</option>
                                                @foreach ($field['options'] ?? [] as $option)
                                                    <option value="{{ $option }}" {{ $value == $option ? 'selected' : '' }}>{{ $option }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <x-text-input id="field-{{ $field['name'] }}" name="{{ $name }}"
                                                          class="mt-1 block w-full" :value="$value" />
                                        @endif
                                        <x-input-error :messages="$errors->get('data.' . $field['name'])" class="mt-2" />
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6 flex items-center gap-4">
                                <x-primary-button>Simpan Draft</x-primary-button>
                                <a href="{{ route('letters.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                            </div>
                        </form>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 h-fit">
                        <h3 class="font-semibold text-gray-800 mb-3">Template Surat</h3>
                        @if ($selectedType->templates->first())
                            <div class="text-sm text-gray-800 bg-gray-50 rounded-md p-3">{!! \App\Support\HtmlSanitizer::normalize($selectedType->templates->first()->body) !!}</div>
                        @else
                            <p class="text-sm text-gray-400">Belum ada template untuk jenis surat ini.</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
