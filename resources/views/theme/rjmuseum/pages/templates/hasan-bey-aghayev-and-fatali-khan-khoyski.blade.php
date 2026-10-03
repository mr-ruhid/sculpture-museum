@php
    $tr = $page->translation();
    $siteName = \App\Models\Setting::get('site_name_' . app()->getLocale(), config('app.name'));

    $slugs = [
        'hasan-bey-aghayev',
        'fatali-khan-khoyski',
    ];

    $items = \App\Models\Sculpture::with('translations')
        ->whereIn('slug', $slugs)
        ->where('is_published', true)
        ->get()
        ->sortBy(fn ($s) => array_search($s->slug, $slugs))
        ->values();
@endphp

@extends('theme.rjmuseum.layouts.app')

@section('title', $tr?->meta_title ?: ($tr?->title ? $tr->title . ' — ' . $siteName : $siteName))
@section('meta_description', $tr?->meta_description ?? '')
@section('meta_keywords', $tr?->meta_keywords ?? '')

@push('styles')
<style>
    .story-split {
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: calc(100vh - 5rem);
        transition: grid-template-columns 0.9s cubic-bezier(.4, 0, .2, 1);
    }
    .story-split.has-active-left { grid-template-columns: 1.4fr 1fr; }
    .story-split.has-active-right { grid-template-columns: 1fr 1.4fr; }

    .story-panel {
        position: relative;
        overflow: hidden;
        cursor: pointer;
        transition: filter 0.7s ease, opacity 0.7s ease;
    }
    .story-panel::after {
        content: '';
        position: absolute;
        inset: 0;
        background: #020617;
        opacity: 0;
        transition: opacity 0.7s ease;
        pointer-events: none;
        z-index: 15;
    }
    .story-split.has-active-left .story-panel[data-side="right"]::after,
    .story-split.has-active-right .story-panel[data-side="left"]::after {
        opacity: 0.75;
    }

    .story-panel__img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 1.2s cubic-bezier(.4, 0, .2, 1), filter 0.9s ease;
    }
    .story-panel[data-active="true"] .story-panel__img {
        transform: scale(1.08);
    }

    .story-panel__gradient {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(2, 6, 23, 0.95) 0%, rgba(2, 6, 23, 0.35) 45%, rgba(2, 6, 23, 0.15) 100%);
        z-index: 10;
    }

    .story-panel__content {
        position: absolute;
        inset: 0;
        z-index: 20;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 2.5rem;
        transition: transform 0.8s cubic-bezier(.4, 0, .2, 1), opacity 0.6s ease;
    }
    .story-panel[data-side="right"] .story-panel__content {
        padding-left: 2.5rem;
        padding-right: 2.5rem;
    }
    .story-panel[data-active="false"] .story-panel__content {
        opacity: 0.55;
        transform: translateY(8px);
    }

    .story-panel__divider {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 50%;
        width: 1px;
        background: linear-gradient(to bottom, transparent, rgba(255, 255, 255, 0.35), transparent);
        z-index: 25;
        pointer-events: none;
        transition: left 0.9s cubic-bezier(.4, 0, .2, 1), opacity 0.6s ease;
    }
    .story-split.has-active-left .story-panel__divider { left: 58.33%; }
    .story-split.has-active-right .story-panel__divider { left: 41.66%; }
    .story-split.has-active .story-panel__divider { opacity: 0; }

    .story-ghost {
        position: absolute;
        top: 4rem;
        font-size: clamp(6rem, 14vw, 16rem);
        font-weight: 900;
        line-height: 0.85;
        letter-spacing: -0.05em;
        color: transparent;
        -webkit-text-stroke: 2px rgba(255, 255, 255, 0.28);
        pointer-events: none;
        user-select: none;
        z-index: 12;
        transition: -webkit-text-stroke-color 0.6s ease, transform 0.8s ease;
    }
    .story-panel[data-side="left"] .story-ghost { left: 2.5rem; }
    .story-panel[data-side="right"] .story-ghost { right: 2.5rem; }
    .story-panel[data-active="true"] .story-ghost {
        -webkit-text-stroke-color: rgba(255, 255, 255, 0.75);
    }

    .story-index {
        position: absolute;
        top: 5rem;
        z-index: 22;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.25em;
        color: rgba(255, 255, 255, 0.75);
        text-transform: uppercase;
    }
    .story-panel[data-side="left"] .story-index { left: 2.5rem; }
    .story-panel[data-side="right"] .story-index { right: 2.5rem; }

    .story-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem 1rem;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 1.25rem;
    }
    .story-meta__dot {
        width: 4px;
        height: 4px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.4);
    }

    .story-cta {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.9rem 1.75rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.95);
        color: #0f172a;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.4s cubic-bezier(.4, 0, .2, 1);
        opacity: 0;
        transform: translateY(15px);
        pointer-events: none;
    }
    .story-panel[data-active="true"] .story-cta {
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
    }
    .story-cta:hover {
        background: #6366f1;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 20px 40px rgba(99, 102, 241, 0.35);
    }

    .story-title {
        font-size: clamp(2rem, 4vw, 3.75rem);
        font-weight: 900;
        line-height: 1.05;
        color: #fff;
        letter-spacing: -0.02em;
        margin-bottom: 1rem;
        max-width: 90%;
    }

    @media (max-width: 768px) {
        .story-split {
            grid-template-columns: 1fr;
            grid-template-rows: 60vh 60vh;
            min-height: auto;
        }
        .story-split.has-active-left,
        .story-split.has-active-right {
            grid-template-columns: 1fr;
        }
        .story-panel__divider { display: none; }
        .story-panel__content { padding: 1.75rem; }
        .story-ghost { top: 2rem; font-size: 5rem; }
        .story-index { top: 2rem; }
        .story-cta { opacity: 1; transform: none; pointer-events: auto; }
    }
</style>
@endpush

@section('content')

@if ($items->count() < 2)
    <div class="min-h-screen flex items-center justify-center bg-slate-950 text-white">
        <p class="text-slate-400">Məlumat tapılmadı.</p>
    </div>
@else
    @php
        $left = $items[0];
        $right = $items[1];
        $leftTr = $left->translation();
        $rightTr = $right->translation();
    @endphp

    <section class="story-split" id="story-split">
        <div class="story-panel" data-side="left" data-active="false">
            @if ($left->main_image)
                <img src="{{ asset('storage/' . $left->main_image) }}" class="story-panel__img" alt="{{ $leftTr?->title }}">
            @else
                <div class="story-panel__img bg-gradient-to-br from-slate-800 to-slate-950"></div>
            @endif

            <div class="story-panel__gradient"></div>
            <div class="story-ghost" aria-hidden="true">01</div>
            <div class="story-index">I</div>

            <div class="story-panel__content">
                <div class="story-meta">
                    @if ($leftTr?->sculptor)
                        <span>{{ $leftTr->sculptor }}</span>
                    @endif
                    @if ($left->year)
                        @if ($leftTr?->sculptor)<span class="story-meta__dot"></span>@endif
                        <span>{{ $left->year }}</span>
                    @endif
                    @if ($leftTr?->city)
                        @if ($leftTr?->sculptor || $left->year)<span class="story-meta__dot"></span>@endif
                        <span>{{ $leftTr->city }}</span>
                    @endif
                </div>

                <h2 class="story-title">{{ $leftTr?->title }}</h2>

                <a href="{{ url('/' . app()->getLocale() . '/sculptures/' . $left->slug) }}" class="story-cta">
                    {{ __('frontend.view_details') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="story-panel" data-side="right" data-active="false">
            @if ($right->main_image)
                <img src="{{ asset('storage/' . $right->main_image) }}" class="story-panel__img" alt="{{ $rightTr?->title }}">
            @else
                <div class="story-panel__img bg-gradient-to-br from-slate-800 to-slate-950"></div>
            @endif

            <div class="story-panel__gradient"></div>
            <div class="story-ghost" aria-hidden="true">02</div>
            <div class="story-index">II</div>

            <div class="story-panel__content">
                <div class="story-meta">
                    @if ($rightTr?->sculptor)
                        <span>{{ $rightTr->sculptor }}</span>
                    @endif
                    @if ($right->year)
                        @if ($rightTr?->sculptor)<span class="story-meta__dot"></span>@endif
                        <span>{{ $right->year }}</span>
                    @endif
                    @if ($rightTr?->city)
                        @if ($rightTr?->sculptor || $right->year)<span class="story-meta__dot"></span>@endif
                        <span>{{ $rightTr->city }}</span>
                    @endif
                </div>

                <h2 class="story-title">{{ $rightTr?->title }}</h2>

                <a href="{{ url('/' . app()->getLocale() . '/sculptures/' . $right->slug) }}" class="story-cta">
                    {{ __('frontend.view_details') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="story-panel__divider"></div>
    </section>
@endif

@endsection

@push('scripts')
<script>
(function () {
    const split = document.getElementById('story-split');
    if (!split) return;

    const panels = split.querySelectorAll('.story-panel');
    const isTouch = window.matchMedia('(hover: none)').matches;

    function setActive(side) {
        if (side === 'left') {
            split.classList.add('has-active', 'has-active-left');
            split.classList.remove('has-active-right');
        } else if (side === 'right') {
            split.classList.add('has-active', 'has-active-right');
            split.classList.remove('has-active-left');
        }

        panels.forEach(p => {
            p.dataset.active = p.dataset.side === side ? 'true' : 'false';
        });
    }

    function clearActive() {
        split.classList.remove('has-active', 'has-active-left', 'has-active-right');
        panels.forEach(p => p.dataset.active = 'false');
    }

    panels.forEach(panel => {
        if (!isTouch) {
            panel.addEventListener('mouseenter', () => setActive(panel.dataset.side));
        }

        panel.addEventListener('click', function (e) {
            if (e.target.closest('.story-cta')) return;

            if (isTouch) {
                if (panel.dataset.active === 'true') {
                    const href = panel.querySelector('.story-cta')?.getAttribute('href');
                    if (href) window.location.href = href;
                    return;
                }
                setActive(panel.dataset.side);
            } else {
                const href = panel.querySelector('.story-cta')?.getAttribute('href');
                if (href) window.location.href = href;
            }
        });
    });

    if (!isTouch) {
        split.addEventListener('mouseleave', clearActive);
    }
})();
</script>
@endpush
