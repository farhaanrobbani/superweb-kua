<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">Tolak Surat</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm dark:bg-gray-800 sm:rounded-lg p-6">
                <div class="mb-4 text-sm text-gray-600 dark:text-gray-300 dark:text-gray-500">
                    Surat: <strong>{{ $letter->perihal }}</strong> ({{ $letter->letterType->name }})
                </div>

                <form method="POST" action="{{ route('letters.tolak', $letter) }}">
                    @csrf
                    <div>
                        <x-input-label for="keterangan" value="Catatan Penolakan" />
                        <textarea id="keterangan" name="keterangan" rows="4" required
                                  class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">{{ old('keterangan') }}</textarea>
                        <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                    </div>
                    <div class="mt-6 flex items-center gap-4">
                        <x-danger-button type="submit">Tolak Surat</x-danger-button>
                        <a href="{{ route('letters.show', $letter) }}" class="text-sm text-gray-600 dark:text-gray-300 dark:text-gray-500 hover:underline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
