@extends('layouts.public')

@section('title', $service->name.' — '.kua_setting('instansi', 'Surat Digital KUA'))

@section('content')
    <div class="mx-auto max-w-3xl px-6 pb-16 pt-12">
        <a href="{{ url('/') }}" class="text-sm font-medium text-teal-700 hover:underline">← Kembali ke Beranda</a>

        <h1 class="mt-4 text-2xl font-bold">{{ $service->name }}</h1>
        @if ($service->description)
            <p class="mt-2 text-sm text-[#1b1b1870]">{{ $service->description }}</p>
        @endif

        <div class="mt-6 leading-relaxed konten-pengumuman">{!! \App\Support\HtmlSanitizer::normalize($service->content) !!}</div>
    </div>
@endsection
