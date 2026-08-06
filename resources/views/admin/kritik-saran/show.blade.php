<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Kritik & Saran</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $feedback->nama }}</h3>
                        <p class="text-sm text-gray-500">{{ $feedback->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <span class="px-3 py-1 text-sm rounded-full bg-teal-100 text-teal-700">{{ $feedback->kategori ?? 'Umum' }}</span>
                </div>
                @if ($feedback->kontak)
                    <p class="mt-4 text-sm text-gray-600"><span class="text-gray-500">Kontak:</span> {{ $feedback->kontak }}</p>
                @endif
                <div class="mt-4 rounded-md bg-gray-50 p-4 text-sm leading-relaxed text-gray-800 whitespace-pre-line">
                    {{ $feedback->isi }}
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <form action="{{ route('kritik-saran.destroy', $feedback) }}" method="POST"
                      onsubmit="return confirm('Hapus kritik/saran ini?')">
                    @csrf
                    @method('DELETE')
                    <x-primary-button class="!bg-red-600 hover:!bg-red-500">Hapus</x-primary-button>
                </form>
                <a href="{{ route('kritik-saran.index') }}" class="text-sm text-gray-600 hover:underline">Kembali</a>
            </div>
        </div>
    </div>
</x-app-layout>
