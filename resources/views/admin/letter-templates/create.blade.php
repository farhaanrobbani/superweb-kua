<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Template Surat</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('letter-templates.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <x-input-label for="letter_type_id" value="Jenis Surat" />
                            <select id="letter_type_id" name="letter_type_id" required
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">
                                <option value="">-- Pilih Jenis Surat --</option>
                                @foreach ($letterTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('letter_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('letter_type_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="name" value="Nama Template" />
                            <x-text-input id="name" name="name" class="mt-1 block w-full" required
                                          value="{{ old('name') }}" placeholder="Template Surat Keterangan Nikah" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-input-label for="body" value="Isi Template Surat" />
                        <p class="text-xs text-gray-500 mt-1">Gunakan placeholder <code>[nama_field]</code> sesuai field pada jenis surat. Body adalah isi surat setelah kepala/kop surat (otomatis ditambahkan di PDF).</p>
                        <textarea id="body" name="body" rows="14"
                                  class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm font-mono text-sm">{{ old('body') }}</textarea>
                        <x-input-error :messages="$errors->get('body')" class="mt-2" />
                    </div>

                    <div class="mt-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="active" value="1" checked class="rounded border-gray-300">
                            <span class="ms-2 text-sm text-gray-700">Aktif</span>
                        </label>
                    </div>

                    <div class="mt-6 flex items-center gap-4">
                        <x-primary-button>Simpan</x-primary-button>
                        <a href="{{ route('letter-templates.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
