@php
    $locale = app()->getLocale();
    $featured = \App\Models\Sculpture::with('translations')
        ->where('is_published', true)
        ->latest()
        ->take(3)
        ->get();
    $count = $featured->count();
    $lastSculpture = $featured->last();
@endphp

@if ($count)
<section id="scroll-showcase" class="relative bg-slate-950" style="height: {{ ($count + 1) * 140 }}vh;">
    <div class="sc-stage sticky w-full overflow-hidden">

        {{-- Sculpture slides --}}
        @foreach ($featured as $i => $sculpture)
            @php $tr = $sculpture->translation($locale); @endphp

            <article class="sc-slide absolute inset-0" style="z-index: {{ $i + 1 }};">
                @if ($sculpture->main_image)
                    <img src="{{ asset('storage/' . $sculpture->main_image) }}"
                         class="sc-image absolute inset-0 w-full h-full object-cover"
                         alt="{{ $tr?->title }}">
                @else
                    <div class="sc-image absolute inset-0 bg-gradient-to-br from-slate-800 to-slate-950"></div>
                @endif

                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>

                <div class="sc-ghost" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>

                <div class="sc-content absolute inset-0 flex items-end">
                    <div class="w-full max-w-7xl mx-auto px-6 pb-24">
                        <div class="max-w-2xl">
                            @if ($tr?->style)
                                <div data-reveal="0">
                                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-white/10 backdrop-blur border border-white/20 text-xs font-semibold text-white mb-4">
                                        {{ $tr->style }}
                                    </div>
                                </div>
                            @endif

                            <h2 data-split class="text-5xl md:text-7xl font-black text-white leading-tight mb-4">{{ $tr?->title }}</h2>

                            @if ($tr?->sculptor)
                                <p data-reveal="1" class="text-lg text-slate-300 mb-2">{{ $tr->sculptor }}</p>
                            @endif

                            @if ($tr?->city || $sculpture->year)
                                <p data-reveal="2" class="text-slate-400 mb-8">
                                    {{ $tr?->city }}
                                    @if ($tr?->city && $sculpture->year) · @endif
                                    {{ $sculpture->year }}
                                </p>
                            @endif

                            <div data-reveal="3">
                                <a href="{{ url('/' . $locale . '/sculptures/' . $sculpture->slug) }}"
                                   class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white text-slate-900 font-semibold hover:bg-slate-100 transition text-sm">
                                    {{ __('frontend.view_details') }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($i === 0)
                    <div class="absolute bottom-8 left-1/2 hidden md:block" style="margin-left: -12px;">
                        <div data-reveal="4">
                            <div class="animate-bounce">
                                <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="sc-dim absolute inset-0"></div>
            </article>
        @endforeach

        {{-- Final call-to-action slide --}}
        <article class="sc-slide absolute inset-0" style="z-index: {{ $count + 1 }};">
            @if ($lastSculpture && $lastSculpture->main_image)
                <img src="{{ asset('storage/' . $lastSculpture->main_image) }}"
                     class="sc-image absolute inset-0 w-full h-full object-cover"
                     alt="">
            @else
                <div class="sc-image absolute inset-0 bg-gradient-to-br from-indigo-900 to-slate-950"></div>
            @endif

            <div class="sc-cta-shade absolute inset-0"></div>

            <div class="sc-content absolute inset-0 flex items-center justify-center">
                <div class="text-center px-6 max-w-4xl mx-auto">
                    <div data-reveal="0">
                        <div class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-6">
                            {{ __('frontend.latest') }}
                        </div>
                    </div>

                    <h2 data-split class="text-4xl md:text-7xl font-black text-white leading-tight mb-8">{{ __('frontend.scroll_for_more') }}</h2>

                    <div data-reveal="2">
                        <a href="{{ url('/' . $locale . '/sculptures') }}"
                           class="inline-flex items-center gap-3 px-10 py-5 rounded-full bg-white text-slate-900 font-bold hover:bg-slate-100 transition shadow-2xl">
                            {{ __('frontend.browse_sculptures') }}
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </article>

        {{-- HUD: counter, dots, progress bar --}}
        <div class="sc-hud">
            <div class="sc-counter">
                <span class="sc-current">01</span>
                <span class="sc-total">/ {{ str_pad($count, 2, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="sc-dots">
                @for ($k = 0; $k <= $count; $k++)
                    <button type="button" class="sc-dot" aria-label="{{ $k + 1 }}"></button>
                @endfor
            </div>

            <div class="sc-progress"><span></span></div>
        </div>
    </div>
</section>
@endif
