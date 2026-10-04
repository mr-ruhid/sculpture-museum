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

    $socials = [
        'facebook' => ['url' => \App\Models\Setting::get('social_facebook'), 'label' => 'Facebook', 'color' => 'text-blue-600', 'hover' => 'hover:bg-blue-50 hover:border-blue-200', 'path' => 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z'],
        'instagram' => ['url' => \App\Models\Setting::get('social_instagram'), 'label' => 'Instagram', 'color' => 'text-pink-600', 'hover' => 'hover:bg-pink-50 hover:border-pink-200', 'path' => 'M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 2.4c5.302 0 9.6 4.298 9.6 9.6 0 4.812-3.544 8.787-8.163 9.442-.187-.758-.351-1.927-.07-2.76.188-.6 1.226-5.19 1.226-5.19s-.31-.626-.31-1.55c0-1.457.844-2.543 1.895-2.543.893 0 1.325.671 1.325 1.475 0 .898-.573 2.241-.867 3.484-.245 1.045.523 1.898 1.553 1.898 1.865 0 3.298-1.966 3.298-4.807 0-2.513-1.806-4.27-4.384-4.27-2.987 0-4.74 2.24-4.74 4.554 0 .902.347 1.869.78 2.397.086.104.099.196.073.302-.075.312-.243 1.006-.276 1.146-.043.18-.144.22-.331.13-1.238-.577-2.012-2.388-2.012-3.845 0-3.13 2.275-6.006 6.557-6.006 3.443 0 6.118 2.453 6.118 5.73 0 3.42-2.156 6.174-5.15 6.174-1.005 0-1.95-.522-2.274-1.139 0 0-.497 1.895-.617 2.36-.224.863-.829 1.945-1.234 2.604C13.66 21.577 12.84 21.6 12 21.6 6.698 21.6 2.4 17.302 2.4 12S6.698 2.4 12 2.4z'],
        'twitter' => ['url' => \App\Models\Setting::get('social_twitter'), 'label' => 'Twitter / X', 'color' => 'text-slate-900', 'hover' => 'hover:bg-slate-100 hover:border-slate-300', 'path' => 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z'],
        'youtube' => ['url' => \App\Models\Setting::get('social_youtube'), 'label' => 'YouTube', 'color' => 'text-red-600', 'hover' => 'hover:bg-red-50 hover:border-red-200', 'path' => 'M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z'],
        'linkedin' => ['url' => \App\Models\Setting::get('social_linkedin'), 'label' => 'LinkedIn', 'color' => 'text-blue-700', 'hover' => 'hover:bg-blue-50 hover:border-blue-200', 'path' => 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z'],
        'telegram' => ['url' => \App\Models\Setting::get('social_telegram'), 'label' => 'Telegram', 'color' => 'text-sky-500', 'hover' => 'hover:bg-sky-50 hover:border-sky-200', 'path' => 'M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z'],
        'whatsapp' => ['url' => \App\Models\Setting::get('social_whatsapp'), 'label' => 'WhatsApp', 'color' => 'text-green-600', 'hover' => 'hover:bg-green-50 hover:border-green-200', 'path' => 'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z'],
    ];
    $activeSocials = collect($socials)->filter(fn ($s) => !empty($s['url']));
@endphp

@section('title', __('frontend.contact') . ' — ' . \App\Models\Setting::get('site_name_' . $locale, config('app.name')))

@section('og_type', 'website')
@section('og_title', __('frontend.contact') . ' — ' . \App\Models\Setting::get('site_name_' . $locale, config('app.name')))
@section('og_description', __('frontend.contact_subtitle'))

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

            @if ($address || $hours)
                <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-3">{{ __('frontend.address') }}</h3>
                    @if ($address)
                        <p class="text-slate-800 font-medium leading-relaxed">{{ $address }}</p>
                    @endif
                    @if ($hours)
                        <div class="mt-4 pt-4 border-t border-slate-200 flex items-start gap-2">
                            <svg class="w-4 h-4 text-slate-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm text-slate-600">{{ $hours }}</div>
                        </div>
                    @endif
                </div>
            @endif

            @if ($phone || $phone2 || $fax)
                <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-3">{{ __('frontend.phone') }}</h3>
                    <div class="space-y-1">
                        @if ($phone)
                            <a href="tel:{{ $phone }}" class="block text-slate-800 font-medium hover:text-indigo-600 transition">{{ $phone }}</a>
                        @endif
                        @if ($phone2)
                            <a href="tel:{{ $phone2 }}" class="block text-slate-800 font-medium hover:text-indigo-600 transition">{{ $phone2 }}</a>
                        @endif
                        @if ($fax)
                            <div class="text-sm text-slate-500 pt-1">Fax: {{ $fax }}</div>
                        @endif
                    </div>
                </div>
            @endif

            @if ($email)
                <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-3">{{ __('frontend.email') }}</h3>
                    <a href="mailto:{{ $email }}" class="block text-slate-800 font-medium hover:text-indigo-600 transition break-all">{{ $email }}</a>
                </div>
            @endif

        </div>
    </div>
</section>

@if ($activeSocials->isNotEmpty())
<section class="pb-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="p-8 md:p-10 rounded-3xl bg-gradient-to-br from-slate-50 to-white border border-slate-100">
            <div class="text-center mb-8">
                <div class="text-xs font-bold text-indigo-600 uppercase tracking-widest mb-2">
                    {{ __('frontend.follow_us') }}
                </div>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900">
                    {{ __('frontend.social_media') }}
                </h2>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-3">
                @foreach ($activeSocials as $key => $social)
                    <a href="{{ $social['url'] }}" target="_blank" rel="noopener"
                       title="{{ $social['label'] }}"
                       class="group inline-flex items-center gap-3 px-5 py-3 rounded-full bg-white border border-slate-200 {{ $social['hover'] }} transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <svg class="w-5 h-5 {{ $social['color'] }}" fill="currentColor" viewBox="0 0 24 24">
                            <path d="{{ $social['path'] }}"/>
                        </svg>
                        <span class="text-sm font-semibold text-slate-700 group-hover:text-slate-900">{{ $social['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

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
