@extends('theme.rjmuseum.layouts.app')

@php
    $tr = $sculpture->translation();
    $metaTitle = $tr?->meta_title ?: $tr?->title;
    $metaDesc = $tr?->meta_description ?: $tr?->short_description;
@endphp

@section('title', $metaTitle . ' — ' . \App\Models\Setting::get('site_name_' . app()->getLocale(), config('app.name')))
@section('meta_description', $metaDesc)
@section('meta_keywords', $tr?->meta_keywords)

@section('content')

<section class="pt-32 pb-12 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <nav class="flex items-center gap-2 text-sm text-slate-500 mb-8">
            <a href="{{ url('/' . app()->getLocale()) }}" class="hover:text-slate-900 transition">{{ __('frontend.home') }}</a>
            <span>/</span>
            <a href="{{ url('/' . app()->getLocale() . '/sculptures') }}" class="hover:text-slate-900 transition">{{ __('frontend.sculptures') }}</a>
            <span>/</span>
            <span class="text-slate-800 font-medium line-clamp-1">{{ $tr?->title }}</span>
        </nav>
    </div>
</section>

<section class="pb-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

            <div>
                @if ($sculpture->main_image)
                    <div class="rounded-3xl overflow-hidden shadow-2xl">
                        <img src="{{ asset('storage/' . $sculpture->main_image) }}"
                             alt="{{ $tr?->title }}"
                             class="w-full h-auto object-cover">
                    </div>
                @else
                    <div class="aspect-square rounded-3xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                        <svg class="w-32 h-32 text-slate-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 21v-7m0 0V9a2 2 0 012-2h2m-4 6h4m12 8v-7m0 0V9a2 2 0 00-2-2h-2m4 6h-4M12 3v18"/>
                        </svg>
                    </div>
                @endif
            </div>

            <div>
                @if ($tr?->style)
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold mb-4">
                        {{ $tr->style }}
                    </div>
                @endif

                <h1 class="text-4xl md:text-5xl font-black text-slate-900 leading-tight mb-4">
                    {{ $tr?->title }}
                </h1>

                @if ($tr?->sculptor)
                    <div class="flex items-center gap-2 text-lg text-slate-600 mb-6">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>{{ $tr->sculptor }}</span>
                    </div>
                @endif

                @if ($tr?->short_description)
                    <p class="text-lg text-slate-600 leading-relaxed mb-8">
                        {{ $tr->short_description }}
                    </p>
                @endif

                <div class="grid grid-cols-2 gap-4 pt-6 border-t border-slate-100">
                    @if ($sculpture->year)
                        <div>
                            <div class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">{{ __('frontend.year') }}</div>
                            <div class="text-slate-800 font-semibold">{{ $sculpture->year }}</div>
                        </div>
                    @endif

                    @if ($sculpture->opening_date)
                        <div>
                            <div class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">{{ __('frontend.opening_date') }}</div>
                            <div class="text-slate-800 font-semibold">{{ $sculpture->opening_date->format('d.m.Y') }}</div>
                        </div>
                    @endif

                    @if ($tr?->city)
                        <div>
                            <div class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">{{ __('frontend.city') }}</div>
                            <div class="text-slate-800 font-semibold">{{ $tr->city }}</div>
                        </div>
                    @endif

                    @if ($tr?->architect)
                        <div>
                            <div class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">{{ __('frontend.architect') }}</div>
                            <div class="text-slate-800 font-semibold">{{ $tr->architect }}</div>
                        </div>
                    @endif

                    @if ($tr?->material)
                        <div>
                            <div class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">{{ __('frontend.material') }}</div>
                            <div class="text-slate-800 font-semibold">{{ $tr->material }}</div>
                        </div>
                    @endif

                    @if ($sculpture->dimensions)
                        <div>
                            <div class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">{{ __('frontend.dimensions') }}</div>
                            <div class="text-slate-800 font-semibold">{{ $sculpture->dimensions }}</div>
                        </div>
                    @endif

                    @if ($sculpture->condition !== 'exists')
                        <div class="col-span-2">
                            <div class="inline-flex items-center px-3 py-1 rounded-full bg-red-50 text-red-700 text-sm font-semibold">
                                {{ __('frontend.condition_' . $sculpture->condition) }}
                            </div>
                        </div>
                    @endif
                </div>

                @if ($tr?->address)
                    <div class="mt-6 pt-6 border-t border-slate-100 flex items-start gap-2 text-sm text-slate-600">
                        <svg class="w-5 h-5 text-slate-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $tr->address }}</span>
                    </div>
                @endif
            </div>

        </div>
    </div>
</section>

@if ($tr?->description || $tr?->history)
<section class="py-16 bg-slate-50">
    <div class="max-w-4xl mx-auto px-6">
        @if ($tr?->description)
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">{{ __('frontend.description') }}</h2>
                <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed whitespace-pre-line">
                    {{ $tr->description }}
                </div>
            </div>
        @endif

        @if ($tr?->history)
            <div>
                <h2 class="text-2xl font-bold text-slate-900 mb-4">{{ __('frontend.history') }}</h2>
                <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed whitespace-pre-line">
                    {{ $tr->history }}
                </div>
            </div>
        @endif
    </div>
</section>
@endif

@if ($sculpture->images->count())
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-2xl font-bold text-slate-900 mb-8">{{ __('frontend.gallery') }}</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($sculpture->images as $img)
                <a href="{{ asset('storage/' . $img->path) }}" target="_blank"
                   class="block aspect-square rounded-2xl overflow-hidden border border-slate-100 card-hover">
                    <img src="{{ asset('storage/' . $img->path) }}" class="w-full h-full object-cover" alt="">
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@if ($sculpture->panorama_embed)
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-slate-900">{{ __('frontend.panorama_360') }}</h2>
            <a href="{{ url('/' . app()->getLocale() . '/sculptures/' . $sculpture->slug . '/360') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                </svg>
                {{ __('frontend.panorama_fullscreen') }}
            </a>
        </div>
        <div class="rounded-3xl overflow-hidden shadow-xl aspect-video">
            {!! $sculpture->panorama_embed !!}
        </div>
    </div>
</section>
@endif

@if ($sculpture->latitude && $sculpture->longitude)
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-2xl font-bold text-slate-900 mb-8">{{ __('frontend.location') }}</h2>
        <div class="rounded-3xl overflow-hidden border border-slate-100 aspect-video">
            <iframe
                src="https://www.google.com/maps?q={{ $sculpture->latitude }},{{ $sculpture->longitude }}&output=embed"
                class="w-full h-full" loading="lazy"></iframe>
        </div>
    </div>
</section>
@endif

@if ($related->count())
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-2xl font-bold text-slate-900 mb-8">{{ __('frontend.related') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($related as $item)
                @include('theme.rjmuseum.widgets.sculpture-card', ['sculpture' => $item])
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
