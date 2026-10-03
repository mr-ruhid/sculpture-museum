@php
    $pageModel = \App\Models\Page::where('slug', $slug)
        ->where('is_published', true)
        ->first();

    if (!$pageModel) {
        abort(404);
    }

    $tr = $pageModel->translation();
    if (!$tr) {
        abort(404);
    }
@endphp

@extends('theme.rjmuseum.layouts.app')

@section('title', $tr->meta_title ?: ($tr->title ? $tr->title . ' — ' . \App\Models\Setting::get('site_name_' . app()->getLocale(), config('app.name')) : \App\Models\Setting::get('site_name_' . app()->getLocale(), config('app.name'))))

@section('meta_description', $tr->meta_description ?? '')
@section('meta_keywords', $tr->meta_keywords ?? '')

@section('content')

<section class="pt-32 pb-16 bg-slate-950 text-white">
    <div class="max-w-7xl mx-auto px-6">
        <h1 class="text-5xl md:text-6xl font-black leading-tight mb-4">
            {{ $tr->title }}
        </h1>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-6 prose prose-lg prose-slate max-w-none">
        {!! $tr->content !!}
    </div>
</section>

@endsection
