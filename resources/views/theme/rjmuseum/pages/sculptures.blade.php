@extends('theme.rjmuseum.layouts.app')

@section('title', __('frontend.sculptures') . ' — ' . \App\Models\Setting::get('site_name_' . app()->getLocale(), config('app.name')))

@section('content')

<section class="pt-32 pb-16 bg-slate-950 text-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-3">
            {{ __('frontend.catalog') }}
        </div>
        <h1 class="text-5xl md:text-6xl font-black leading-tight mb-4">
            {{ __('frontend.sculptures') }}
        </h1>
        <p class="text-lg text-slate-400 max-w-2xl">
            {{ __('frontend.sculptures_subtitle') }}
        </p>
    </div>
</section>

<section class="pt-8 pb-10 bg-slate-50 sticky top-20 z-30">
    <div class="max-w-7xl mx-auto px-6">
        <form method="GET"
              class="bg-white/90 backdrop-blur-xl border border-slate-200/80 rounded-2xl shadow-lg shadow-slate-900/5 p-2 flex flex-col lg:flex-row gap-2">

            <div class="relative flex-1 min-w-0">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="{{ __('frontend.search_placeholder') }}"
                       class="w-full bg-transparent border-0 rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition placeholder:text-slate-400">
            </div>

            <div class="hidden lg:block w-px bg-slate-200 my-2"></div>

            <div class="grid grid-cols-1 sm:grid-cols-3 lg:flex gap-2">
                <select name="city"
                        class="bg-slate-50 hover:bg-slate-100 border border-transparent rounded-xl px-4 py-3 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition cursor-pointer">
                    <option value="">{{ __('frontend.all_cities') }}</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>

                <select name="style"
                        class="bg-slate-50 hover:bg-slate-100 border border-transparent rounded-xl px-4 py-3 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition cursor-pointer">
                    <option value="">{{ __('frontend.all_styles') }}</option>
                    @foreach ($styles as $style)
                        <option value="{{ $style }}" {{ request('style') === $style ? 'selected' : '' }}>{{ $style }}</option>
                    @endforeach
                </select>

                <select name="year"
                        class="bg-slate-50 hover:bg-slate-100 border border-transparent rounded-xl px-4 py-3 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition cursor-pointer">
                    <option value="">{{ __('frontend.all_years') }}</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}" {{ (string) request('year') === (string) $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                @if (request()->anyFilled(['q', 'city', 'style', 'year']))
                    <a href="{{ url('/' . app()->getLocale() . '/sculptures') }}"
                       class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-slate-100 text-slate-600 text-sm font-semibold hover:bg-slate-200 transition"
                       title="{{ __('frontend.clear') }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span class="lg:hidden">{{ __('frontend.clear') }}</span>
                    </a>
                @endif

                <button type="submit"
                        class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-indigo-600 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    {{ __('frontend.filter') }}
                </button>
            </div>

        </form>
    </div>
</section>

<section class="pt-8 pb-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">

        <div class="mb-8 text-sm text-slate-500">
            {{ trans_choice('frontend.results_count', $sculptures->total(), ['count' => $sculptures->total()]) }}
        </div>

        @if ($sculptures->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6">
                @foreach ($sculptures as $sculpture)
                    @include('theme.rjmuseum.widgets.sculpture-card', [
                        'sculpture' => $sculpture,
                        'index' => ($sculptures->currentPage() - 1) * $sculptures->perPage() + $loop->index,
                    ])
                @endforeach
            </div>

            <div class="mt-12">
                {{ $sculptures->links() }}
            </div>
        @else
            <div class="text-center py-24">
                <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">{{ __('frontend.no_results') }}</h3>
                <p class="text-slate-500">{{ __('frontend.no_results_text') }}</p>
            </div>
        @endif

    </div>
</section>

@endsection
