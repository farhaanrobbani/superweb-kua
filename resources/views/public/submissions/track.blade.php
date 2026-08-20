<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracking Permohonan - {{ kua_setting('instansi', 'Surat Digital KUA') }}</title>
    <link rel="icon" href="{{ \App\Models\KuaSetting::logoUrl() ?: asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-teal-50 via-emerald-50 to-white min-h-screen flex flex-col">
    @include('partials.public-header')

    @php
        $statuses = [
            \App\Models\Submission::STATUS_BARU    => 'Baru',
            \App\Models\Submission::STATUS_DIPROSES => 'Diproses',
            \App\Models\Submission::STATUS_SELESAI  => 'Selesai',
        ];
        $rejected = $submission->status === \App\Models\Submission::STATUS_DITOLAK;
        $currentKey = $rejected ? \App\Models\Submission::STATUS_DITOLAK : $submission->status;
        $statusOrder = array_keys($statuses);
        $currentIndex = array_search($currentKey, $statusOrder);
    @endphp

    <main class="flex-1 flex items-center justify-center py-10">
        <div class="max-w-lg w-full mx-4 bg-white rounded-lg shadow-sm border border-teal-100 p-8">
            <h1 class="text-xl font-bold text-gray-800 text-center mb-6">📋 Status Permohonan</h1>

            <div class="space-y-3 mb-6 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Jenis Surat</span>
                    <span class="font-medium text-gray-800">{{ $submission->letterType->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Pemohon</span>
                    <span class="font-medium text-gray-800">{{ $submission->nama_pemohon }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Tanggal</span>
                    <span class="font-medium text-gray-800">{{ $submission->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <hr class="border-gray-100 mb-6">

            @if ($rejected)
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center mb-4">
                    <div class="text-2xl mb-1">❌</div>
                    <div class="font-semibold text-red-700">Permohonan Ditolak</div>
                    @if ($submission->catatan)
                        <p class="text-sm text-red-600 mt-2">Catatan: {{ $submission->catatan }}</p>
                    @endif
                </div>
            @else
                <div class="space-y-0">
                    @foreach ($statuses as $key => $label)
                        @php
                            $statusIndex = array_search($key, $statusOrder);
                            $isPassed = $statusIndex < $currentIndex;
                            $isCurrent = $statusIndex === $currentIndex;
                        @endphp
                        <div class="flex items-start gap-3">
                            <div class="flex flex-col items-center">
                                @if ($isPassed)
                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </div>
                                @elseif ($isCurrent)
                                    <div class="w-8 h-8 rounded-full bg-teal-500 flex items-center justify-center ring-4 ring-teal-100">
                                        <div class="w-2.5 h-2.5 bg-white rounded-full"></div>
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                        <div class="w-2.5 h-2.5 bg-gray-300 rounded-full"></div>
                                    </div>
                                @endif
                                @if (! $loop->last)
                                    <div class="w-0.5 h-6 {{ $isPassed ? 'bg-green-200' : 'bg-gray-200' }}"></div>
                                @endif
                            </div>
                            <div class="pt-1 pb-4">
                                <span class="text-sm font-medium {{ $isCurrent ? 'text-teal-700' : ($isPassed ? 'text-green-700' : 'text-gray-400') }}">
                                    {{ $label }}
                                </span>
                                @if ($isCurrent)
                                    <span class="ml-2 text-xs bg-teal-100 text-teal-700 px-2 py-0.5 rounded-full">Saat Ini</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($submission->catatan && ! $rejected)
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm mt-2">
                    <span class="font-medium text-gray-700">Catatan:</span>
                    <span class="text-gray-600">{{ $submission->catatan }}</span>
                </div>
            @endif

            <div class="text-center mt-6">
                <a href="{{ route('permohonan.create') }}" class="text-sm text-teal-700 hover:underline">
                    Ajukan permohonan lain
                </a>
            </div>
        </div>
    </main>

    @include('partials.public-footer')
</body>
</html>
