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

<section class="py-12 bg-white border-b border-slate-100 sticky top-0 z-30 glass">
    <div class="max-w-7xl mx-auto px-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3">

            <div class="md:col-span-2">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="{{ __('frontend.search_placeholder') }}"
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
            </div>

            <div>
                <select name="city"
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    <option value="">{{ __('frontend.all_cities') }}</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="style"
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    <option value="">{{ __('frontend.all_styles') }}</option>
                    @foreach ($styles as $style)
                        <option value="{{ $style }}" {{ request('style') === $style ? 'selected' : '' }}>{{ $style }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="year"
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    <option value="">{{ __('frontend.all_years') }}</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}" {{ (string) request('year') === (string) $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-5 flex gap-3">
                <button type="submit"
                        class="px-6 py-3 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
                    {{ __('frontend.filter') }}
                </button>
                @if (request()->anyFilled(['q', 'city', 'style', 'year']))
                    <a href="{{ url('/' . app()->getLocale() . '/sculptures') }}"
                       class="px-6 py-3 rounded-xl bg-slate-100 text-slate-700 text-sm font-semibold hover:bg-slate-200 transition">
                        {{ __('frontend.clear') }}
                    </a>
                @endif
            </div>

        </form>
    </div>
</section>

<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">

        <div class="mb-8 text-sm text-slate-500">
            {{ trans_choice('frontend.results_count', $sculptures->total(), ['count' => $sculptures->total()]) }}
        </div>

        @if ($sculptures->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($sculptures as $sculpture)
                    @include('theme.rjmuseum.widgets.sculpture-card', ['sculpture' => $sculpture])
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
