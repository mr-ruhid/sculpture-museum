@php
    $tr = $sculpture->translation();
@endphp

<a href="{{ route('sculpture.show', $sculpture->slug) }}"
   class="group block bg-white rounded-2xl overflow-hidden card-hover border border-slate-100">

    <div class="relative aspect-[4/5] overflow-hidden bg-slate-100">
        @if ($sculpture->main_image)
            <img src="{{ asset('storage/' . $sculpture->main_image) }}"
                 alt="{{ $tr?->title }}"
                 class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 21v-7m0 0V9a2 2 0 012-2h2m-4 6h4m12 8v-7m0 0V9a2 2 0 00-2-2h-2m4 6h-4M12 3v18"/>
                </svg>
            </div>
        @endif

        @if ($sculpture->year)
            <div class="absolute top-3 left-3 px-3 py-1 rounded-full bg-white/90 backdrop-blur text-xs font-semibold text-slate-700">
                {{ $sculpture->year }}
            </div>
        @endif

        @if ($sculpture->condition !== 'exists')
            <div class="absolute top-3 right-3 px-3 py-1 rounded-full bg-red-500/90 backdrop-blur text-xs font-semibold text-white">
                {{ __('frontend.condition_' . $sculpture->condition) }}
            </div>
        @endif

        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
    </div>

    <div class="p-5">
        <h3 class="text-lg font-bold text-slate-800 mb-1 line-clamp-2 group-hover:text-indigo-600 transition">
            {{ $tr?->title ?? '—' }}
        </h3>

        @if ($tr?->sculptor)
            <div class="text-sm text-slate-500 mb-3 line-clamp-1">
                {{ $tr->sculptor }}
            </div>
        @endif

        <div class="flex items-center justify-between pt-3 border-t border-slate-100">
            <div class="flex items-center gap-1.5 text-xs text-slate-500">
                @if ($tr?->city)
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="line-clamp-1">{{ $tr->city }}</span>
                @endif
            </div>

            <div class="w-8 h-8 rounded-full bg-slate-100 group-hover:bg-indigo-600 flex items-center justify-center transition">
                <svg class="w-4 h-4 text-slate-600 group-hover:text-white transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </div>
        </div>
    </div>
</a>
