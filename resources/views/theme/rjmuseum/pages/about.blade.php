@extends('theme.rjmuseum.layouts.app')

@php
    $locale = app()->getLocale();
    $title = \App\Models\Setting::get("about_title_{$locale}");
    $short = \App\Models\Setting::get("about_short_{$locale}");
    $content = \App\Models\Setting::get("about_content_{$locale}");
@endphp

@section('title', ($title ?: __('frontend.about')) . ' — ' . \App\Models\Setting::get('site_name_' . $locale, config('app.name')))
@section('meta_description', $short)

@if ($content)
    @section('og_type', 'article')
    @section('og_title', $title ?: __('frontend.about'))
    @section('og_description', $short)
@endif

@section('content')

@if ($content)
    <div class="page-content">
        {!! $content !!}
    </div>
@else
    <section class="pt-32 pb-16 bg-slate-950 text-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-3">
                {{ __('frontend.about_us') }}
            </div>
            <h1 class="text-5xl md:text-6xl font-black leading-tight mb-4">
                {{ $title ?: __('frontend.about') }}
            </h1>
            @if ($short)
                <p class="text-lg text-slate-400 max-w-3xl leading-relaxed">
                    {{ $short }}
                </p>
            @endif
        </div>
    </section>

    <section class="py-24 bg-white">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">{{ __('frontend.about_empty') }}</h3>
            <p class="text-slate-500">{{ __('frontend.about_empty_text') }}</p>
        </div>
    </section>
@endif

@endsection
