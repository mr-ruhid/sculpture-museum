@php
    $locale = app()->getLocale();
    $heroTitle = \App\Models\Setting::get("hero_title_{$locale}") ?: \App\Models\Setting::get('site_name_' . $locale);
    $heroSubtitle = \App\Models\Setting::get("hero_subtitle_{$locale}") ?: \App\Models\Setting::get('site_description_' . $locale);
    $heroButton = \App\Models\Setting::get("hero_button_{$locale}") ?: __('frontend.explore');
    $heroImage = \App\Models\Setting::get('hero_image');
@endphp

<section class="relative min-h-screen flex items-center overflow-hidden bg-slate-900">
    @if ($heroImage)
        <div class="absolute inset-0">
            <img src="{{ asset('storage/' . $heroImage) }}" class="w-full h-full object-cover opacity-60" alt="">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/80 to-transparent"></div>
        </div>
    @else
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800"></div>
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-20 w-96 h-96 rounded-full bg-white blur-3xl"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 rounded-full bg-indigo-500 blur-3xl"></div>
        </div>
    @endif

    <div class="relative z-10 max-w-7xl mx-auto px-6 py-32 w-full">
        <div class="max-w-2xl">

            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur border border-white/20 mb-8 animate-fade-up">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span class="text-xs font-medium text-white tracking-wider uppercase">
                    {{ \App\Models\Setting::get('site_name_' . app()->getLocale(), config('app.name')) }}
                </span>
            </div>

            <h1 class="text-5xl md:text-7xl font-black text-white leading-tight mb-6 animate-fade-up delay-1">
                {{ $heroTitle }}
            </h1>

            @if ($heroSubtitle)
                <p class="text-lg md:text-xl text-slate-300 leading-relaxed mb-10 animate-fade-up delay-2">
                    {{ $heroSubtitle }}
                </p>
            @endif

            <div class="flex flex-wrap gap-4 animate-fade-up delay-3">
                <a href="{{ url('/sculptures') }}"
                   class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-white text-slate-900 font-semibold hover:bg-slate-100 transition shadow-xl">
                    {{ $heroButton }}
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
                <a href="{{ url('/about') }}"
                   class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-white/10 backdrop-blur border border-white/20 text-white font-semibold hover:bg-white/20 transition">
                    {{ __('frontend.learn_more') }}
                </a>
            </div>

        </div>
    </div>

    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-10 animate-bounce">
        <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
        </svg>
    </div>
</section>
