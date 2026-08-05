<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Jenis Surat</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('letter-types.store') }}" x-data="fieldRepeater({{ json_encode(old('fields', [])) }})">
                    @csrf

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <x-input-label for="code" value="Kode (contoh: SKN, SKT, SKC)" />
                            <x-text-input id="code" name="code" class="mt-1 block w-full" required
                                          value="{{ old('code') }}" placeholder="SKN" />
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="name" value="Nama Jenis Surat" />
                            <x-text-input id="name" name="name" class="mt-1 block w-full" required
                                          value="{{ old('name') }}" placeholder="Surat Keterangan Nikah" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-input-label for="description" value="Deskripsi" />
                        <textarea id="description" name="description" rows="2"
                                  class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="mt-6">
                        <div class="flex items-center justify-between">
                            <x-input-label value="Field / Data Surat" />
                            <button type="button" @click="addField()"
                                    class="text-sm text-blue-600 hover:underline">+ Tambah Field</button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Field ini menjadi form pengisian saat membuat surat. Gunakan nama field sebagai placeholder <code>[nama_field]</code> di template surat.</p>

                        <div class="mt-3 space-y-3">
                            <template x-for="(field, index) in fields" :key="index">
                                <div class="border border-gray-200 rounded-md p-4 bg-gray-50">
                                    <input type="hidden" :name="`fields[${index}][required]`" :value="field.required ? 1 : 0">
                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-6">
                                        <div class="sm:col-span-2">
                                            <input :name="`fields[${index}][name]`" x-model="field.name" required
                                                   placeholder="nama_field" class="w-full rounded-md border-gray-300 text-sm" />
                                        </div>
                                        <div class="sm:col-span-2">
                                            <input :name="`fields[${index}][label]`" x-model="field.label" required
                                                   placeholder="Label field" class="w-full rounded-md border-gray-300 text-sm" />
                                        </div>
                                        <div class="sm:col-span-1">
                                            <select :name="`fields[${index}][type]`" x-model="field.type"
                                                    class="w-full rounded-md border-gray-300 text-sm">
                                                <option value="text">Text</option>
                                                <option value="textarea">Textarea</option>
                                                <option value="date">Tanggal</option>
                                                <option value="select">Pilihan</option>
                                            </select>
                                        </div>
                                        <div class="sm:col-span-1 flex items-center gap-3">
                                            <label class="flex items-center text-xs text-gray-600">
                                                <input type="checkbox" x-model="field.required" class="rounded border-gray-300"> Wajib
                                            </label>
                                            <button type="button" @click="fields.splice(index, 1)"
                                                    class="text-red-600 text-sm hover:underline">Hapus</button>
                                        </div>
                                    </div>
                                    <div class="mt-2" x-show="field.type === 'select'">
                                        <input :name="`fields[${index}][options][]`" x-model="field.optionsText" placeholder="Opsi pilihan, pisahkan dengan koma"
                                               class="w-full rounded-md border-gray-300 text-sm" />
                                    </div>
                                </div>
                            </template>
                            <p x-show="fields.length === 0" class="text-sm text-gray-400">Belum ada field. Klik "+ Tambah Field".</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="active" value="1" checked class="rounded border-gray-300">
                            <span class="ms-2 text-sm text-gray-700">Aktif (bisa dipilih saat membuat surat)</span>
                        </label>
                    </div>

                    <div class="mt-6 flex items-center gap-4">
                        <x-primary-button>Simpan</x-primary-button>
                        <a href="{{ route('letter-types.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function fieldRepeater(initialFields) {
            const normalize = (f) => ({
                name: f.name || '',
                label: f.label || '',
                type: f.type || 'text',
                required: !!f.required,
                optionsText: Array.isArray(f.options) ? f.options.join(', ') : (f.optionsText || ''),
            });

            return {
                fields: (initialFields || []).map(normalize),
                addField() {
                    this.fields.push({ name: '', label: '', type: 'text', required: true, optionsText: '' });
                },
            };
        }
    </script>
</x-app-layout>
