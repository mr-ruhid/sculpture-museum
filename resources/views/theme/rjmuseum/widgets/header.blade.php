@php
    $locale = app()->getLocale();
    $segments = request()->segments();
    $currentPath = count($segments) > 1 ? implode('/', array_slice($segments, 1)) : '';
@endphp

<header id="site-header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-500">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between h-20">

            <a href="{{ url('/' . $locale) }}" class="flex items-center gap-3">
                @php $logo = \App\Models\Setting::get('logo'); @endphp
                @if ($logo)
                    <img src="{{ asset('storage/' . $logo) }}" class="h-10" alt="Logo">
                @else
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-900 to-slate-700 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 21v-7m0 0V9a2 2 0 012-2h2m-4 6h4m12 8v-7m0 0V9a2 2 0 00-2-2h-2m4 6h-4M12 3v18"/>
                        </svg>
                    </div>
                    <div class="font-bold text-lg text-slate-900">
                        {{ \App\Models\Setting::get('site_name_' . $locale, config('app.name')) }}
                    </div>
                @endif
            </a>

            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ url('/' . $locale) }}"
                   class="text-sm font-medium transition {{ $currentPath === '' ? 'text-slate-900' : 'text-slate-600 hover:text-slate-900' }}">
                    {{ __('frontend.home') }}
                </a>
                <a href="{{ url('/' . $locale . '/sculptures') }}"
                   class="text-sm font-medium transition {{ str_starts_with($currentPath, 'sculptures') ? 'text-slate-900' : 'text-slate-600 hover:text-slate-900' }}">
                    {{ __('frontend.sculptures') }}
                </a>
                <a href="{{ url('/' . $locale . '/about') }}"
                   class="text-sm font-medium transition {{ $currentPath === 'about' ? 'text-slate-900' : 'text-slate-600 hover:text-slate-900' }}">
                    {{ __('frontend.about') }}
                </a>
                <a href="{{ url('/' . $locale . '/contact') }}"
                   class="text-sm font-medium transition {{ $currentPath === 'contact' ? 'text-slate-900' : 'text-slate-600 hover:text-slate-900' }}">
                    {{ __('frontend.contact') }}
                </a>
            </nav>

            <div class="flex items-center gap-2">
                @foreach (\App\Models\Language::active() as $lang)
                    <a href="{{ url('/' . $lang->code . ($currentPath ? '/' . $currentPath : '')) }}"
                       title="{{ $lang->name }}"
                       class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold transition
                              {{ $locale === $lang->code ? 'bg-slate-900 text-white' : 'hover:bg-slate-200 text-slate-600' }}">
                        @if ($lang->flag)
                            <img src="{{ $lang->flag }}" class="w-5 h-3.5 rounded-sm object-cover" alt="{{ $lang->code }}">
                        @else
                            {{ strtoupper($lang->code) }}
                        @endif
                    </a>
                @endforeach

                <button id="mobile-menu-btn" class="md:hidden w-9 h-9 rounded-lg hover:bg-slate-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <div id="mobile-menu" class="hidden md:hidden glass border-t border-slate-200">
        <nav class="max-w-7xl mx-auto px-6 py-4 space-y-2">
            <a href="{{ url('/' . $locale) }}" class="block py-2 text-sm font-medium text-slate-700">{{ __('frontend.home') }}</a>
            <a href="{{ url('/' . $locale . '/sculptures') }}" class="block py-2 text-sm font-medium text-slate-700">{{ __('frontend.sculptures') }}</a>
            <a href="{{ url('/' . $locale . '/about') }}" class="block py-2 text-sm font-medium text-slate-700">{{ __('frontend.about') }}</a>
            <a href="{{ url('/' . $locale . '/contact') }}" class="block py-2 text-sm font-medium text-slate-700">{{ __('frontend.contact') }}</a>
        </nav>
    </div>
</header>
