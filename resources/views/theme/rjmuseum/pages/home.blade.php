@extends('theme.rjmuseum.layouts.app')

@section('title', \App\Models\Setting::get('site_name_' . app()->getLocale(), config('app.name')))

@section('content')

@include('theme.rjmuseum.widgets.scroll-showcase')

@include('theme.rjmuseum.widgets.map', ['sculptures' => $sculptures])

@php
    $aboutTitle = \App\Models\Setting::get('about_title_' . app()->getLocale());
    $aboutShort = \App\Models\Setting::get('about_short_' . app()->getLocale());
    $aboutImage = \App\Models\Setting::get('about_image');
@endphp

@if ($aboutTitle || $aboutShort)
<section class="py-24 bg-slate-950 text-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            <div>
                <div class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-3">
                    {{ __('frontend.about_us') }}
                </div>
                <h2 class="text-4xl md:text-5xl font-black leading-tight mb-6">
                    {{ $aboutTitle }}
                </h2>
                @if ($aboutShort)
                    <p class="text-lg text-slate-400 leading-relaxed mb-8">
                        {{ $aboutShort }}
                    </p>
                @endif
                <a href="{{ url('/' . app()->getLocale() . '/about') }}"
                   class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-white text-slate-900 font-semibold hover:bg-slate-100 transition">
                    {{ __('frontend.learn_more') }}
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

            <div class="relative">
                @if ($aboutImage)
                    <img src="{{ asset('storage/' . $aboutImage) }}" class="w-full rounded-3xl shadow-2xl" alt="">
                @else
                    <div class="aspect-square rounded-3xl bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                        <svg class="w-32 h-32 text-slate-700" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 21v-7m0 0V9a2 2 0 012-2h2m-4 6h4m12 8v-7m0 0V9a2 2 0 00-2-2h-2m4 6h-4M12 3v18"/>
                        </svg>
                    </div>
                @endif
            </div>

        </div>
    </div>
</section>
@endif

<section class="relative py-32 bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-0 left-1/4 w-96 h-96 rounded-full bg-white blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 rounded-full bg-pink-400 blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
        <h2 class="text-4xl md:text-6xl font-black text-white leading-tight mb-6">
            {{ __('frontend.cta_title') }}
        </h2>
        <p class="text-lg text-indigo-100 mb-10 max-w-2xl mx-auto">
            {{ __('frontend.cta_subtitle') }}
        </p>
        <a href="{{ url('/' . app()->getLocale() . '/sculptures') }}"
           class="inline-flex items-center gap-2 px-10 py-5 rounded-full bg-white text-slate-900 font-bold hover:bg-slate-100 transition shadow-2xl">
            {{ __('frontend.cta_button') }}
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>
</section>

@endsection
