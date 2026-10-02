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
