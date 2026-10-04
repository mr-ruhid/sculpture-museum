@extends('theme.rjmuseum.layouts.app')

@php
    $tr = $sculpture->translation();
    $metaTitle = $tr?->meta_title ?: $tr?->title;
    $metaDesc = $tr?->meta_description ?: $tr?->short_description;
    $ogDesc = $tr?->short_description
        ?: \Illuminate\Support\Str::limit(strip_tags($tr?->description ?? ''), 160);
    $ogImage = $sculpture->main_image
        ? asset('storage/' . $sculpture->main_image)
        : (\App\Models\Setting::get('logo') ? asset('storage/' . \App\Models\Setting::get('logo')) : null);

    $shareUrl = urlencode(url()->current());
    $shareTitle = urlencode($tr?->title ?? '');

    $schemaData = [
        '@context' => 'https://schema.org',
        '@type' => 'Sculpture',
        'name' => $tr?->title,
        'url' => url()->current(),
        'inLanguage' => app()->getLocale(),
    ];

    if ($tr?->short_description) {
        $schemaData['description'] = $tr->short_description;
    } elseif ($tr?->description) {
        $schemaData['description'] = \Illuminate\Support\Str::limit(strip_tags($tr->description), 300);
    }

    if ($tr?->sculptor) {
        $schemaData['creator'] = [
            '@type' => 'Person',
            'name' => $tr->sculptor,
        ];
    }

    if ($tr?->architect) {
        $schemaData['contributor'] = [
            '@type' => 'Person',
            'name' => $tr->architect,
        ];
    }

    if ($sculpture->year) {
        $schemaData['dateCreated'] = (string) $sculpture->year;
    }

    if ($tr?->material) {
        $schemaData['material'] = $tr->material;
    }

    if ($tr?->style) {
        $schemaData['artform'] = $tr->style;
    }

    if ($ogImage) {
        $images = [];
        if ($sculpture->main_image) {
            $images[] = asset('storage/' . $sculpture->main_image);
        }
        foreach ($sculpture->images as $img) {
            $images[] = asset('storage/' . $img->path);
        }
        if (count($images) === 1) {
            $schemaData['image'] = $images[0];
        } elseif (count($images) > 1) {
            $schemaData['image'] = $images;
        }
    }

    if ($sculpture->latitude && $sculpture->longitude) {
        $place = [
            '@type' => 'Place',
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => (float) $sculpture->latitude,
                'longitude' => (float) $sculpture->longitude,
            ],
        ];

        if ($tr?->city) {
            $place['name'] = $tr->city;
            $place['address'] = [
                '@type' => 'PostalAddress',
                'addressLocality' => $tr->city,
            ];
        }

        if ($tr?->address) {
            if (!isset($place['address'])) {
                $place['address'] = ['@type' => 'PostalAddress'];
            }
            $place['address']['streetAddress'] = $tr->address;
        }

        $schemaData['locationCreated'] = $place;
    }
@endphp

@section('title', $metaTitle . ' — ' . \App\Models\Setting::get('site_name_' . app()->getLocale(), config('app.name')))
@section('meta_description', $metaDesc)
@section('meta_keywords', $tr?->meta_keywords)

@section('og_type', 'article')
@section('og_title', $metaTitle)
@section('og_description', $ogDesc)
@if ($ogImage)
    @section('og_image', $ogImage)
@endif

@push('styles')
<style>
    .lightbox {
        position: fixed;
        inset: 0;
        z-index: 200;
        background: rgba(2, 6, 23, 0.95);
        backdrop-filter: blur(12px);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    .lightbox.is-open {
        display: flex;
    }
    .lightbox img {
        max-width: 90vw;
        max-height: 85vh;
        border-radius: 12px;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.6);
        object-fit: contain;
    }
    .lightbox-close {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        width: 3rem;
        height: 3rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background .3s, transform .3s;
    }
    .lightbox-close:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(90deg);
    }
    .lightbox-prev,
    .lightbox-next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 3rem;
        height: 3rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background .3s, transform .3s;
    }
    .lightbox-prev { left: 1.5rem; }
    .lightbox-next { right: 1.5rem; }
    .lightbox-prev:hover,
    .lightbox-next:hover {
        background: rgba(255, 255, 255, 0.25);
    }
    .lightbox-prev:hover { transform: translateY(-50%) translateX(-3px); }
    .lightbox-next:hover { transform: translateY(-50%) translateX(3px); }
    .lightbox-counter {
        position: absolute;
        bottom: 1.5rem;
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.7);
        background: rgba(0, 0, 0, 0.4);
        padding: 0.4rem 1rem;
        border-radius: 999px;
        font-weight: 600;
    }
    @media (max-width: 640px) {
        .lightbox-prev, .lightbox-next { display: none; }
        .lightbox-close { top: 1rem; right: 1rem; width: 2.5rem; height: 2.5rem; }
    }
</style>
@endpush

@section('content')

<script type="application/ld+json">
{!! json_encode($schemaData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

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
                    <div class="rounded-3xl overflow-hidden shadow-2xl cursor-zoom-in"
                         onclick="openLightbox({{ $sculpture->main_image ? json_encode(asset('storage/' . $sculpture->main_image)) : 'null' }}, 0, [{{ $sculpture->images->map(fn($i) => '"' . asset('storage/' . $i->path) . '"')->implode(',') }}])">
                        <img src="{{ asset('storage/' . $sculpture->main_image) }}"
                             alt="{{ $tr?->title }}"
                             class="w-full h-auto object-cover transition-transform duration-700 hover:scale-[1.02]">
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

                <div class="flex items-center gap-2 mb-6 flex-wrap">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider mr-1">
                        {{ __('frontend.share') }}:
                    </span>

                    <a href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20{{ $shareUrl }}"
                       target="_blank" rel="noopener" title="WhatsApp"
                       class="w-9 h-9 rounded-full bg-slate-100 hover:bg-green-500 text-slate-600 hover:text-white flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
                    </a>

                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
                       target="_blank" rel="noopener" title="Facebook"
                       class="w-9 h-9 rounded-full bg-slate-100 hover:bg-blue-600 text-slate-600 hover:text-white flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>

                    <a href="https://twitter.com/intent/tweet?text={{ $shareTitle }}&url={{ $shareUrl }}"
                       target="_blank" rel="noopener" title="Twitter / X"
                       class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-900 text-slate-600 hover:text-white flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>

                    <a href="https://t.me/share/url?url={{ $shareUrl }}&text={{ $shareTitle }}"
                       target="_blank" rel="noopener" title="Telegram"
                       class="w-9 h-9 rounded-full bg-slate-100 hover:bg-sky-500 text-slate-600 hover:text-white flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                    </a>

                    <button type="button" onclick="copyShareUrl(this)" title="{{ __('frontend.copy_link') }}"
                            class="w-9 h-9 rounded-full bg-slate-100 hover:bg-indigo-600 text-slate-600 hover:text-white flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </button>
                </div>

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
    @php
        $galleryImages = $sculpture->images->map(fn($i) => asset('storage/' . $i->path))->values()->toArray();
    @endphp
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl font-bold text-slate-900 mb-8">{{ __('frontend.gallery') }}</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($sculpture->images as $idx => $img)
                    <button type="button"
                            onclick='openLightbox(@json(asset("storage/" . $img->path)), {{ $idx }}, @json($galleryImages))'
                            class="block aspect-square rounded-2xl overflow-hidden border border-slate-100 card-hover cursor-zoom-in">
                        <img src="{{ asset('storage/' . $img->path) }}" class="w-full h-full object-cover" alt="">
                    </button>
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

<div id="lightbox" class="lightbox" onclick="if (event.target === this) closeLightbox()">
    <button type="button" class="lightbox-close" onclick="closeLightbox()" aria-label="Close">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <button type="button" class="lightbox-prev" onclick="lightboxPrev()" aria-label="Previous">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <img id="lightbox-img" src="" alt="">
    <button type="button" class="lightbox-next" onclick="lightboxNext()" aria-label="Next">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    </button>
    <div id="lightbox-counter" class="lightbox-counter"></div>
</div>

<script>
let lbImages = [];
let lbIndex = 0;

function openLightbox(src, index, extra) {
    lbImages = [];
    if (src) lbImages.push(src);
    if (Array.isArray(extra) && extra.length) {
        lbImages = lbImages.concat(extra);
    }
    lbIndex = Math.max(0, parseInt(index) || 0);

    const modal = document.getElementById('lightbox');
    const img = document.getElementById('lightbox-img');
    const counter = document.getElementById('lightbox-counter');

    img.src = lbImages[lbIndex] || '';
    counter.textContent = (lbIndex + 1) + ' / ' + lbImages.length;

    modal.classList.add('is-open');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox').classList.remove('is-open');
    document.body.style.overflow = '';
}

function lightboxNext() {
    if (!lbImages.length) return;
    lbIndex = (lbIndex + 1) % lbImages.length;
    refreshLightbox();
}

function lightboxPrev() {
    if (!lbImages.length) return;
    lbIndex = (lbIndex - 1 + lbImages.length) % lbImages.length;
    refreshLightbox();
}

function refreshLightbox() {
    document.getElementById('lightbox-img').src = lbImages[lbIndex];
    document.getElementById('lightbox-counter').textContent = (lbIndex + 1) + ' / ' + lbImages.length;
}

document.addEventListener('keydown', function (e) {
    const modal = document.getElementById('lightbox');
    if (!modal.classList.contains('is-open')) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowRight') lightboxNext();
    if (e.key === 'ArrowLeft') lightboxPrev();
});

function copyShareUrl(btn) {
    navigator.clipboard.writeText(window.location.href).then(function () {
        const orig = btn.innerHTML;
        btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
        btn.classList.add('bg-emerald-600', 'text-white');
        setTimeout(function () {
            btn.innerHTML = orig;
            btn.classList.remove('bg-emerald-600', 'text-white');
        }, 1400);
    });
}
</script>

@endsection
