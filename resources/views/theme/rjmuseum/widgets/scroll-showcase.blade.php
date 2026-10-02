@php
    $featured = \App\Models\Sculpture::with('translations')
        ->where('is_published', true)
        ->latest()
        ->take(3)
        ->get();
    $locale = app()->getLocale();
@endphp

@if ($featured->count())
<div id="scroll-showcase" class="relative bg-slate-950">

    @foreach ($featured as $i => $sculpture)
        @php
            $tr = $sculpture->translation($locale);
            $isLast = $i === $featured->count() - 1;
        @endphp

        <div class="showcase-panel sticky top-0 h-screen w-full overflow-hidden"
             data-index="{{ $i }}"
             data-last="{{ $isLast ? '1' : '0' }}">

            @if ($sculpture->main_image)
                <img src="{{ asset('storage/' . $sculpture->main_image) }}"
                     class="showcase-image absolute inset-0 w-full h-full object-cover"
                     alt="{{ $tr?->title }}">
            @else
                <div class="absolute inset-0 bg-gradient-to-br from-slate-800 to-slate-950"></div>
            @endif

            <div class="showcase-overlay absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>

            <div class="showcase-content absolute inset-0 flex items-end">
                <div class="w-full max-w-7xl mx-auto px-6 pb-24">

                    <div class="max-w-2xl">
                        @if ($tr?->style)
                            <div class="inline-flex items-center px-3 py-1 rounded-full bg-white/10 backdrop-blur border border-white/20 text-xs font-semibold text-white mb-4">
                                {{ $tr->style }}
                            </div>
                        @endif

                        <h2 class="text-5xl md:text-7xl font-black text-white leading-tight mb-4">
                            {{ $tr?->title }}
                        </h2>

                        @if ($tr?->sculptor)
                            <p class="text-lg text-slate-300 mb-2">{{ $tr->sculptor }}</p>
                        @endif

                        @if ($tr?->city || $sculpture->year)
                            <p class="text-slate-400 mb-8">
                                {{ $tr?->city }}
                                @if ($tr?->city && $sculpture->year) · @endif
                                {{ $sculpture->year }}
                            </p>
                        @endif

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

            @if ($isLast)
                <div class="showcase-cta absolute inset-0 flex items-center justify-center pointer-events-none opacity-0">
                    <div class="text-center px-6">
                        <h2 class="text-5xl md:text-7xl font-black text-white leading-tight mb-6">
                            {{ __('frontend.scroll_for_more') }}
                        </h2>
                        <a href="{{ url('/' . $locale . '/sculptures') }}"
                           class="pointer-events-auto inline-flex items-center gap-2 px-8 py-4 rounded-full bg-white text-slate-900 font-bold hover:bg-slate-100 transition">
                            {{ __('frontend.browse_sculptures') }}
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endif

        </div>
    @endforeach

</div>
@endif
