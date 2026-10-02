<footer class="bg-slate-950 text-slate-400">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">

            <div class="md:col-span-2">
                <div class="text-white font-bold text-xl mb-4">
                    {{ \App\Models\Setting::get('site_name_' . app()->getLocale(), config('app.name')) }}
                </div>
                <p class="text-sm leading-relaxed max-w-md mb-6">
                    {{ \App\Models\Setting::get('site_description_' . app()->getLocale(), '') }}
                </p>

                @php
                    $socials = [
                        'facebook' => 'FB',
                        'instagram' => 'IG',
                        'twitter' => 'X',
                        'youtube' => 'YT',
                        'linkedin' => 'IN',
                        'telegram' => 'TG',
                        'whatsapp' => 'WA',
                    ];
                    $hasSocial = false;
                @endphp

                <div class="flex gap-2 flex-wrap">
                    @foreach ($socials as $key => $label)
                        @php $link = \App\Models\Setting::get($key, null) ?: \App\Models\Setting::get('social_' . $key); @endphp
                        @if ($link)
                            @php $hasSocial = true; @endphp
                            <a href="{{ $link }}" target="_blank" rel="noopener"
                               class="w-10 h-10 rounded-full bg-slate-900 hover:bg-slate-800 flex items-center justify-center transition">
                                <span class="text-xs font-bold text-white">{{ $label }}</span>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            <div>
                <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">
                    {{ __('frontend.navigation') }}
                </h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ url('/') }}" class="hover:text-white transition">{{ __('frontend.home') }}</a></li>
                    <li><a href="{{ url('/sculptures') }}" class="hover:text-white transition">{{ __('frontend.sculptures') }}</a></li>
                    <li><a href="{{ url('/about') }}" class="hover:text-white transition">{{ __('frontend.about') }}</a></li>
                    <li><a href="{{ url('/contact') }}" class="hover:text-white transition">{{ __('frontend.contact') }}</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">
                    {{ __('frontend.contact') }}
                </h4>
                <ul class="space-y-3 text-sm">
                    @php
                        $email = \App\Models\Setting::get('contact_email');
                        $phone = \App\Models\Setting::get('contact_phone');
                        $address = \App\Models\Setting::get('contact_address_' . app()->getLocale());
                    @endphp
                    @if ($address)
                        <li>{{ $address }}</li>
                    @endif
                    @if ($phone)
                        <li><a href="tel:{{ $phone }}" class="hover:text-white transition">{{ $phone }}</a></li>
                    @endif
                    @if ($email)
                        <li><a href="mailto:{{ $email }}" class="hover:text-white transition">{{ $email }}</a></li>
                    @endif
                </ul>
            </div>

        </div>

        <div class="border-t border-slate-800 mt-12 pt-6 flex flex-col md:flex-row justify-between items-center gap-3 text-xs">
            <div>
                {{ \App\Models\Setting::get('footer_text_' . app()->getLocale(), '© ' . date('Y') . ' ' . config('app.name')) }}
            </div>
            <div class="flex gap-4">
                <a href="https://ruhidjavadoff.blogspot.com/2026/07/rj-cms-sistemlri.html" target="_blank" rel="noopener" class="hover:text-white transition">
                    RJ CMS Lite
                </a>
                <span>v1.1.5</span>
            </div>
        </div>
    </div>
</footer>
