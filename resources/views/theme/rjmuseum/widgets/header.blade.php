@php
    $locale = app()->getLocale();
    $segments = request()->segments();
    $currentPath = count($segments) > 1 ? implode('/', array_slice($segments, 1)) : '';
    $currentLang = \App\Models\Language::where('code', $locale)->first();
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

            <nav class="hidden md:flex items-center gap-1">
                @php
                    $tabs = [
                        ['path' => '', 'label' => __('frontend.home'), 'match' => ''],
                        ['path' => 'sculptures', 'label' => __('frontend.sculptures'), 'match' => 'sculptures'],
                        ['path' => 'about', 'label' => __('frontend.about'), 'match' => 'about'],
                        ['path' => 'contact', 'label' => __('frontend.contact'), 'match' => 'contact'],
                    ];
                @endphp
                @foreach ($tabs as $tab)
                    @php
                        $isActive = $tab['match'] === ''
                            ? $currentPath === ''
                            : str_starts_with($currentPath, $tab['match']);
                    @endphp
                    <a href="{{ url('/' . $locale . ($tab['path'] ? '/' . $tab['path'] : '')) }}"
                       class="relative px-4 py-2 text-sm font-medium rounded-full transition-all duration-300
                              {{ $isActive ? 'text-white bg-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        {{ $tab['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-2">

                <div class="lang-switcher relative" id="lang-switcher">
                    <button type="button" id="lang-toggle"
                            class="group flex items-center gap-2 pl-1.5 pr-3 py-1.5 rounded-full bg-white/60 backdrop-blur border border-slate-200 hover:border-slate-300 hover:bg-white transition-all duration-300 shadow-sm">
                        <span class="w-8 h-8 rounded-full flex items-center justify-center overflow-hidden bg-slate-100 flex-shrink-0 transition-transform duration-300 group-hover:scale-110">
                            @if ($currentLang?->flag)
                                <img src="{{ $currentLang->flag }}" class="w-full h-full object-cover" alt="{{ $currentLang->code }}">
                            @else
                                <span class="text-[11px] font-bold text-slate-700">{{ strtoupper($locale) }}</span>
                            @endif
                        </span>
                        <span class="text-sm font-semibold text-slate-800 hidden sm:block">{{ strtoupper($locale) }}</span>
                        <svg id="lang-chevron" class="w-3.5 h-3.5 text-slate-500 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div id="lang-menu"
                         class="lang-menu absolute right-0 top-full mt-2 min-w-[200px] bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden opacity-0 invisible scale-95 origin-top-right transition-all duration-300">
                        @foreach (\App\Models\Language::active() as $lang)
                            @php
                                $targetPath = $currentPath ? '/' . $currentPath : '';
                                $isCurrent = $locale === $lang->code;
                            @endphp
                            <a href="{{ url('/' . $lang->code . $targetPath) }}"
                               class="lang-item flex items-center gap-3 px-4 py-3 transition-colors duration-200
                                      {{ $isCurrent ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-slate-50 text-slate-700' }}">
                                <span class="w-8 h-8 rounded-full flex items-center justify-center overflow-hidden bg-slate-100 flex-shrink-0">
                                    @if ($lang->flag)
                                        <img src="{{ $lang->flag }}" class="w-full h-full object-cover" alt="{{ $lang->code }}">
                                    @else
                                        <span class="text-[11px] font-bold">{{ strtoupper($lang->code) }}</span>
                                    @endif
                                </span>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-semibold">{{ $lang->name }}</div>
                                    <div class="text-[11px] uppercase tracking-wider {{ $isCurrent ? 'text-indigo-500' : 'text-slate-400' }}">{{ $lang->code }}</div>
                                </div>
                                @if ($isCurrent)
                                    <svg class="w-4 h-4 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                <button id="mobile-menu-btn" class="md:hidden w-10 h-10 rounded-full hover:bg-slate-100 flex items-center justify-center transition">
                    <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <div id="mobile-menu" class="hidden md:hidden glass border-t border-slate-200">
        <nav class="max-w-7xl mx-auto px-6 py-4 space-y-1">
            <a href="{{ url('/' . $locale) }}" class="block py-3 px-4 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-100 transition">{{ __('frontend.home') }}</a>
            <a href="{{ url('/' . $locale . '/sculptures') }}" class="block py-3 px-4 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-100 transition">{{ __('frontend.sculptures') }}</a>
            <a href="{{ url('/' . $locale . '/about') }}" class="block py-3 px-4 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-100 transition">{{ __('frontend.about') }}</a>
            <a href="{{ url('/' . $locale . '/contact') }}" class="block py-3 px-4 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-100 transition">{{ __('frontend.contact') }}</a>
        </nav>
    </div>
</header>
