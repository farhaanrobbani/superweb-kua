<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Kegiatan</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm">
                    Periksa kembali isian yang disorot di bawah.
                </div>
            @endif

            <form method="POST" action="{{ route('kegiatan.update', $activity) }}" class="bg-white rounded-lg shadow-sm">
                @csrf
                @method('PUT')
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-gray-700">Kegiatan Harian {{ tanggal_indonesia($activity->tanggal, 'd F Y') }}</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div>
                            <x-input-label for="tanggal" value="Tanggal" />
                            <input type="date" name="tanggal" id="tanggal" required
                                   value="{{ old('tanggal', $activity->tanggal) }}"
                                   class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm" />
                            <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="activity_type_key" value="Jenis Kegiatan" />
                            <select name="activity_type_key" id="activity_type_key"
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">
                                <option value="">— Lainnya —</option>
                                @foreach ($columns as $key => $label)
                                    <option value="{{ $key }}" @selected(old('activity_type_key', $activity->activity_type_key) === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <x-input-label for="kegiatan" value="Kegiatan" />
                        <textarea name="kegiatan" id="kegiatan" rows="3" required
                                  class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">{{ old('kegiatan', $activity->kegiatan) }}</textarea>
                        <x-input-error :messages="$errors->get('kegiatan')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="pekerjaan" value="Pekerjaan" />
                        <textarea name="pekerjaan" id="pekerjaan" rows="3" required
                                  class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">{{ old('pekerjaan', $activity->pekerjaan) }}</textarea>
                        <x-input-error :messages="$errors->get('pekerjaan')" class="mt-2" />
                    </div>

                    <div class="sm:max-w-40">
                        <x-input-label for="total_jumlah" value="Jumlah" />
                        <input type="number" name="total_jumlah" id="total_jumlah" min="0"
                               value="{{ old('total_jumlah', $activity->total_jumlah) }}"
                               class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm" />
                        @if ($daily && $activity->activity_type_key && array_key_exists($activity->activity_type_key, $columns))
                            <p class="text-xs text-gray-500 mt-1">
                                Jumlah otomatis mengikuti Master Data Harian ({{ $daily->{$activity->activity_type_key} }}).
                            </p>
                        @endif
                    </div>

                    <div class="pt-2 border-t border-gray-100 flex items-center gap-3">
                        <x-primary-button>Simpan</x-primary-button>
                        <a href="{{ route('kegiatan.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
