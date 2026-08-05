@extends('layouts.public')

@section('title', $announcement->title.' — '.kua_setting('instansi', 'Surat Digital KUA'))

@section('content')
    <article class="mx-auto max-w-3xl px-6 pb-16 pt-12">
        <a href="{{ route('pengumuman.index') }}" class="text-sm text-teal-700 hover:underline">&larr; Semua Pengumuman</a>
        <h1 class="mt-4 text-2xl font-bold">{{ $announcement->title }}</h1>
        <p class="mt-2 text-sm text-[#1b1b1870]">
            {{ tanggal_indonesia($announcement->published_at ?? $announcement->created_at, 'd F Y H:i') }}
        </p>
        <div class="mt-6 whitespace-pre-wrap leading-relaxed">{{ $announcement->content }}</div>
    </article>
@endsection
