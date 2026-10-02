@php
    $tr = $sculpture->translation();
    $num = str_pad(($index ?? 0) + 1, 2, '0', STR_PAD_LEFT);
@endphp

<a href="{{ route('sculpture.show', $sculpture->slug) }}"
   class="sculpture-card group relative block bg-white rounded-3xl overflow-hidden border border-slate-100 card-hover">

    <div class="relative aspect-[4/5] overflow-hidden bg-slate-100">
        @if ($sculpture->main_image)
            <img src="{{ asset('storage/' . $sculpture->main_image) }}"
                 alt="{{ $tr?->title }}"
                 class="w-full h-full object-cover transition-transform duration-[900ms] ease-out group-hover:scale-[1.12]">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                <svg class="w-20 h-20 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 21v-7m0 0V9a2 2 0 012-2h2m-4 6h4m12 8v-7m0 0V9a2 2 0 00-2-2h-2m4 6h-4M12 3v18"/>
                </svg>
            </div>
        @endif

        <div class="ghost-number absolute -top-4 right-2 font-black leading-none select-none pointer-events-none z-10"
             aria-hidden="true">
            {{ $num }}
        </div>

        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/95 via-slate-950/30 to-transparent"></div>

        <div class="absolute top-4 left-4 z-20">
            @if ($sculpture->year)
                <div class="px-3 py-1.5 rounded-full bg-white/15 backdrop-blur border border-white/30 text-xs font-semibold text-white">
                    {{ $sculpture->year }}
                </div>
            @endif
        </div>

        @if ($sculpture->condition !== 'exists')
            <div class="absolute top-4 right-4 z-20 px-3 py-1.5 rounded-full bg-red-500/90 backdrop-blur text-xs font-semibold text-white">
                {{ __('frontend.condition_' . $sculpture->condition) }}
            </div>
        @endif

        <div class="absolute bottom-5 left-5 right-5 z-20">
            @if ($tr?->style)
                <div class="inline-block mb-3 px-3 py-1 rounded-full bg-white/10 backdrop-blur border border-white/20 text-[11px] font-semibold text-white/90">
                    {{ $tr->style }}
                </div>
            @endif
            <h3 class="text-xl md:text-2xl font-bold text-white mb-1.5 line-clamp-2 leading-tight">
                {{ $tr?->title ?? '—' }}
            </h3>
            @if ($tr?->sculptor)
                <p class="text-sm text-white/70 line-clamp-1">{{ $tr->sculptor }}</p>
            @endif
        </div>
    </div>

    <div class="p-5 md:p-6 flex items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-sm text-slate-500 min-w-0">
            @if ($tr?->city)
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="truncate">{{ $tr->city }}</span>
            @endif
        </div>

        <div class="w-11 h-11 rounded-full bg-slate-900 group-hover:bg-indigo-600 flex items-center justify-center transition-all duration-500 flex-shrink-0 group-hover:rotate-[-45deg] group-hover:scale-110">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 5l7 7-7 7"/>
            </svg>
        </div>
    </div>

    <span class="card-ripple" aria-hidden="true"></span>
</a>
