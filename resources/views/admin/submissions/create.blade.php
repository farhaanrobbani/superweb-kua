<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">Buat Permohonan Surat</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 dark:bg-red-900/30 dark:border-red-800 dark:text-red-300 px-4 py-3 rounded-md text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (! $selectedType)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">Pilih Jenis Surat</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            @foreach ($letterTypes as $type)
                                <a href="{{ route('submissions.create', ['jenis' => $type->code]) }}"
                                   class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-teal-400 hover:shadow transition">
                                    <div class="font-semibold text-gray-800 dark:text-gray-100">{{ $type->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $type->description }}</div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-gray-800 dark:text-gray-100">{{ $selectedType->name }}</h3>
                            <a href="{{ route('submissions.create') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:underline">Ganti jenis</a>
                        </div>

                        <form method="POST" action="{{ route('submissions.store') }}">
                            @csrf
                            <input type="hidden" name="jenis" value="{{ $selectedType->code }}">

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <x-input-label for="nama_pemohon" value="Nama Lengkap Pemohon *" />
                                    <x-text-input id="nama_pemohon" name="nama_pemohon" class="mt-1 block w-full" required
                                                  value="{{ old('nama_pemohon') }}" />
                                </div>
                                <div>
                                    <x-input-label for="kontak" value="No. HP / Email *" />
                                    <x-text-input id="kontak" name="kontak" class="mt-1 block w-full" required
                                                  value="{{ old('kontak') }}" placeholder="0821xxxx / email@contoh.id" />
                                </div>
                            </div>

                            <div class="mt-5 space-y-4">
                                @foreach ($selectedType->fields ?? [] as $field)
                                    @if (! empty($field['internal']))
                                        @continue
                                    @endif
                                    @php
                                        $name = 'data[' . $field['name'] . ']';
                                        $value = old('data.' . $field['name']);
                                    @endphp
                                    <div>
                                        <x-input-label :for="'field-' . $field['name']" :value="$field['label'] . ($field['required'] ? ' *' : '')" />
                                        @if ($field['type'] === 'textarea')
                                            <textarea id="field-{{ $field['name'] }}" name="{{ $name }}" rows="3"
                                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">{{ $value }}</textarea>
                                        @elseif ($field['type'] === 'date')
                                            <x-text-input id="field-{{ $field['name'] }}" name="{{ $name }}" type="date"
                                                          class="mt-1 block w-full" :value="$value" />
                                        @elseif ($field['type'] === 'select')
                                            <select id="field-{{ $field['name'] }}" name="{{ $name }}"
                                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                                                <option value="">-- Pilih --</option>
                                                @foreach ($field['options'] ?? [] as $option)
                                                    <option value="{{ $option }}" {{ $value == $option ? 'selected' : '' }}>{{ $option }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <x-text-input id="field-{{ $field['name'] }}" name="{{ $name }}"
                                                          class="mt-1 block w-full" :value="$value" />
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6 flex items-center gap-4">
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-teal-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-600">
                                    Simpan Permohonan
                                </button>
                                <a href="{{ route('submissions.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>

                @if ($selectedType->permohonan_informasi)
                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-sm leading-relaxed text-gray-600 dark:text-gray-300">
                        {!! nl2br(e($selectedType->permohonan_informasi)) !!}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
