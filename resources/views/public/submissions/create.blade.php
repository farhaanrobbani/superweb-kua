<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Permohonan Surat - {{ $kua['instansi'] }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-3xl mx-auto px-4 py-6">
            <h1 class="text-xl font-bold text-gray-800">{{ $kua['instansi'] }}</h1>
            <p class="text-sm text-gray-500">{{ $kua['alamat'] }}
                @if ($kua['telepon']) &bull; {{ $kua['telepon'] }} @endif
                @if ($kua['email']) &bull; {{ $kua['email'] }} @endif
            </p>
            <a href="{{ route('login') }}" class="text-xs text-gray-400 hover:text-gray-600">Login Petugas</a>
        </div>
    </header>

    <main class="flex-1 py-10">
        <div class="max-w-3xl mx-auto px-4">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Form Permohonan Surat</h2>
                <p class="text-sm text-gray-500 mt-1">Isi form berikut, kemudian petugas KUA akan memproses permohonan Anda.</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (! $selectedType)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Pilih Jenis Surat</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @foreach ($letterTypes as $type)
                            <a href="{{ route('permohonan.create', ['jenis' => $type->code]) }}"
                               class="border border-gray-200 rounded-lg p-4 hover:border-indigo-400 hover:shadow transition">
                                <div class="font-semibold text-gray-800">{{ $type->name }}</div>
                                <div class="text-sm text-gray-500 mt-1">{{ $type->description }}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-800">{{ $selectedType->name }}</h3>
                        <a href="{{ route('permohonan.create') }}" class="text-sm text-gray-500 hover:underline">Ganti jenis</a>
                    </div>

                    <form method="POST" action="{{ route('permohonan.store') }}">
                        @csrf
                        <input type="hidden" name="jenis" value="{{ $selectedType->code }}">
                        <input type="text" name="website" value="" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">

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
                                @php
                                    $name = 'data[' . $field['name'] . ']';
                                    $value = old('data.' . $field['name']);
                                @endphp
                                <div>
                                    <x-input-label :for="'field-' . $field['name']" :value="$field['label'] . ($field['required'] ? ' *' : '')" />
                                    @if ($field['type'] === 'textarea')
                                        <textarea id="field-{{ $field['name'] }}" name="{{ $name }}" rows="3"
                                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ $value }}</textarea>
                                    @elseif ($field['type'] === 'date')
                                        <x-text-input id="field-{{ $field['name'] }}" name="{{ $name }}" type="date"
                                                      class="mt-1 block w-full" :value="$value" />
                                    @elseif ($field['type'] === 'select')
                                        <select id="field-{{ $field['name'] }}" name="{{ $name }}"
                                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
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
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Kirim Permohonan
                            </button>
                            <a href="{{ route('permohonan.create') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 py-6 mt-10">
        <div class="max-w-3xl mx-auto px-4 text-center text-xs text-gray-400">
            {{ $kua['instansi'] }} &copy; {{ date('Y') }}
        </div>
    </footer>
</body>
</html>
