<div class="bg-white rounded-lg shadow-sm dark:bg-gray-800">
    <div class="border-b border-gray-100 px-6 py-4 dark:border-gray-700">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-100">Volume Layanan Harian</h3>
        <p class="text-xs text-gray-500 mt-0.5 dark:text-gray-400">Isi 0 untuk jenis layanan yang tidak berjalan pada tanggal tersebut.</p>
    </div>
    <div class="px-6 py-4 space-y-4">
        <div>
            <x-input-label for="tanggal" value="Tanggal" />
            <input type="date" name="tanggal" id="tanggal" required
                   value="{{ old('tanggal', isset($data) ? $data->tanggal : $tanggal ?? '') }}"
                   class="mt-1 block w-full sm:w-56 border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm" />
            <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
            <p class="text-xs text-gray-500 mt-1 dark:text-gray-400">Jika tanggal sudah ada, data akan diperbarui.</p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($columns as $key => $label)
                <div>
                    <x-input-label for="{{ $key }}" value="{{ $label }}" />
                    <input type="number" name="{{ $key }}" id="{{ $key }}" min="0" value="{{ old($key, isset($data) ? $data->{$key} : 0) }}"
                           class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm" />
                    <x-input-error :messages="$errors->get($key)" class="mt-2" />
                </div>
            @endforeach
        </div>

        <div class="pt-2 border-t border-gray-100 flex items-center gap-3 dark:border-gray-700">
            <x-primary-button>Simpan</x-primary-button>
            <a href="{{ route('kua-daily.index') }}" class="text-sm text-gray-600 hover:underline dark:text-gray-400">Batal</a>
        </div>
    </div>
</div>
