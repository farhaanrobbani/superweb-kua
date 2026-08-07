<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-100">Edit Data Harian KUA</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm dark:bg-red-900/30 dark:border-red-800 dark:text-red-300">
                    Periksa kembali isian yang disorot di bawah.
                </div>
            @endif

            <form method="POST" action="{{ route('kua-daily.update', $data) }}" class="space-y-6">
                @csrf
                @method('PUT')
                @include('admin.kua-daily._form')
            </form>
        </div>
    </div>
</x-app-layout>
