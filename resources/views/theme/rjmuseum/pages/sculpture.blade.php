@if ($sculpture->panorama_embed)
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="relative rounded-3xl overflow-hidden shadow-xl bg-slate-900">
            @if ($sculpture->main_image)
                <img src="{{ asset('storage/' . $sculpture->main_image) }}"
                     class="w-full h-[400px] object-cover opacity-40" alt="">
            @endif
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6">
                <div class="w-20 h-20 rounded-full bg-white/10 backdrop-blur border border-white/20 flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-3xl md:text-4xl font-black text-white mb-3">{{ __('frontend.panorama_360') }}</h2>
                <p class="text-slate-300 mb-8 max-w-lg">{{ __('frontend.panorama_subtitle') }}</p>
                <a href="{{ url('/' . app()->getLocale() . '/sculptures/' . $sculpture->slug . '/360') }}"
                   class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-white text-slate-900 font-bold hover:bg-slate-100 transition shadow-2xl">
                    {{ __('frontend.panorama_open') }}
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>
@endif
