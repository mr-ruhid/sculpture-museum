@extends('theme.rjmuseum.layouts.app')

@php
    $locale = app()->getLocale();
    $email = \App\Models\Setting::get('contact_email');
    $phone = \App\Models\Setting::get('contact_phone');
    $phone2 = \App\Models\Setting::get('contact_phone_2');
    $fax = \App\Models\Setting::get('contact_fax');
    $address = \App\Models\Setting::get("contact_address_{$locale}");
    $hours = \App\Models\Setting::get("working_hours_{$locale}");
    $mapEmbed = \App\Models\Setting::get('map_embed');
@endphp

@section('title', __('frontend.contact') . ' — ' . \App\Models\Setting::get('site_name_' . $locale, config('app.name')))

@section('content')

<section class="pt-32 pb-16 bg-slate-950 text-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-3">
            {{ __('frontend.get_in_touch') }}
        </div>
        <h1 class="text-5xl md:text-6xl font-black leading-tight mb-4">
            {{ __('frontend.contact') }}
        </h1>
        <p class="text-lg text-slate-400 max-w-2xl">
            {{ __('frontend.contact_subtitle') }}
        </p>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            @if ($address)
                <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-2">{{ __('frontend.address') }}</h3>
                    <p class="text-slate-800 font-medium leading-relaxed">{{ $address }}</p>
                </div>
            @endif

            @if ($phone || $phone2 || $fax)
                <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-2">{{ __('frontend.phone') }}</h3>
                    <div class="space-y-1">
                        @if ($phone)
                            <a href="tel:{{ $phone }}" class="block text-slate-800 font-medium hover:text-indigo-600 transition">{{ $phone }}</a>
                        @endif
                        @if ($phone2)
                            <a href="tel:{{ $phone2 }}" class="block text-slate-800 font-medium hover:text-indigo-600 transition">{{ $phone2 }}</a>
                        @endif
                        @if ($fax)
                            <div class="text-sm text-slate-500">Fax: {{ $fax }}</div>
                        @endif
                    </div>
                </div>
            @endif

            @if ($email || $hours)
                <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-2">{{ __('frontend.email') }}</h3>
                    @if ($email)
                        <a href="mailto:{{ $email }}" class="block text-slate-800 font-medium hover:text-indigo-600 transition break-all">{{ $email }}</a>
                    @endif
                    @if ($hours)
                        <div class="mt-3 text-sm text-slate-500">{{ $hours }}</div>
                    @endif
                </div>
            @endif

        </div>
    </div>
</section>

@if ($mapEmbed)
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-6 pb-16">
        <div class="rounded-3xl overflow-hidden shadow-xl aspect-video">
            {!! $mapEmbed !!}
        </div>
    </div>
</section>
@endif

<section class="py-16 bg-slate-50">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold text-slate-900 mb-4">{{ __('frontend.contact_cta_title') }}</h2>
        <p class="text-slate-600 mb-8">{{ __('frontend.contact_cta_subtitle') }}</p>
        <a href="{{ url('/' . $locale . '/sculptures') }}"
           class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-slate-900 text-white font-semibold hover:bg-slate-800 transition">
            {{ __('frontend.cta_button') }}
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>
</section>

@endsection
