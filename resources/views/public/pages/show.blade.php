@extends('layouts.public')

@section('title', $page->title.' — '.kua_setting('instansi', 'Surat Digital KUA'))

@section('content')
    <article class="mx-auto max-w-3xl px-6 pb-16 pt-12">
        <h1 class="text-2xl font-bold">{{ $page->title }}</h1>
        <div class="mt-6 leading-relaxed konten-pengumuman">{!! \App\Support\HtmlSanitizer::sanitize($page->content) !!}</div>
    </article>
@endsection
