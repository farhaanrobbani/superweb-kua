<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-100">Atur Template Kalimat</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm dark:bg-green-900/30 dark:border-green-800 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            <div>
                <a href="{{ route('kegiatan.index') }}"
                   class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:bg-gray-700/40 dark:text-teal-400">
                    &larr; Kembali ke Kegiatan Harian
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800">
                <div class="border-b border-gray-100 dark:border-gray-700 px-6 py-4">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Kalimat Laporan Pribadi</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        Kalimat ini otomatis terisi pada laporan Anda saat mengambil data dari Master Data Harian.
                        Kosongkan kedua kolom untuk menghapus template tema tersebut.
                    </p>
                </div>

                <form method="POST" action="{{ route('kegiatan.templates.store') }}" class="px-6 py-4 space-y-4">
                    @csrf

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/40">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400 w-64">Tema Pekerjaan</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Kalimat Kegiatan</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">Kalimat Pekerjaan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                @foreach ($themes as $key => $label)
                                    <tr>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $label }}</td>
                                        <td class="px-4 py-3">
                                            <textarea name="templates[{{ $key }}][kegiatan]" rows="2" maxlength="1000"
                                                      placeholder="Uraian kegiatan yang dilaksanakan"
                                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">{{ old("templates.{$key}.kegiatan", $templates[$key]['kegiatan'] ?? '') }}</textarea>
                                        </td>
                                        <td class="px-4 py-3">
                                            <textarea name="templates[{{ $key }}][pekerjaan]" rows="2" maxlength="1000"
                                                      placeholder="Hasil / pekerjaan yang diselesaikan"
                                                      class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">{{ old("templates.{$key}.pekerjaan", $templates[$key]['pekerjaan'] ?? '') }}</textarea>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="pt-2 border-t border-gray-100 flex items-center gap-3 dark:border-gray-700">
                        <x-primary-button>Simpan Template</x-primary-button>
                        <a href="{{ route('kegiatan.index') }}" class="text-sm text-gray-600 hover:underline dark:text-gray-400">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
