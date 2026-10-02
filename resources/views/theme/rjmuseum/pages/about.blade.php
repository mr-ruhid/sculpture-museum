@extends('theme.rjmuseum.layouts.app')

@php
    $locale = app()->getLocale();
    $title = \App\Models\Setting::get("about_title_{$locale}");
    $short = \App\Models\Setting::get("about_short_{$locale}");
    $content = \App\Models\Setting::get("about_content_{$locale}");
    $image = \App\Models\Setting::get('about_image');
@endphp

@section('title', ($title ?: __('frontend.about')) . ' — ' . \App\Models\Setting::get('site_name_' . $locale, config('app.name')))
@section('meta_description', $short)

@section('content')

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

@if ($image)
<section class="pt-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="rounded-3xl overflow-hidden shadow-2xl">
            <img src="{{ asset('storage/' . $image) }}" class="w-full h-auto object-cover" alt="">
        </div>
    </div>
</section>
@endif

@if ($content)
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-6">
        <div class="prose prose-slate prose-lg max-w-none text-slate-700 leading-relaxed whitespace-pre-line">
            {{ $content }}
        </div>
    </div>
</section>
@endif

@if (!$title && !$short && !$content)
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

@php
    $stats = [
        ['value' => \App\Models\Setting::get('stat_sculptures', \App\Models\Sculpture::where('is_published', true)->count()), 'label' => __('frontend.stat_sculptures')],
        ['value' => \App\Models\Setting::get('stat_cities', '—'), 'label' => __('frontend.stat_cities')],
        ['value' => \App\Models\Setting::get('stat_sculptors', '—'), 'label' => __('frontend.stat_sculptors')],
        ['value' => \App\Models\Setting::get('stat_years', '—'), 'label' => __('frontend.stat_years')],
    ];
@endphp

<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach ($stats as $stat)
                <div class="text-center p-6 rounded-2xl bg-white border border-slate-100">
                    <div class="text-3xl md:text-4xl font-black text-slate-900 mb-2">{{ $stat['value'] }}</div>
                    <div class="text-xs md:text-sm text-slate-500 uppercase tracking-wider font-medium">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
