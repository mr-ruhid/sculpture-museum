@extends('admin.layouts.app')

@section('title', 'Texniki xidmət rejimi')

@section('content')

@php
    $isDown = file_exists(storage_path('framework/down'));
    $downData = $isDown ? json_decode(file_get_contents(storage_path('framework/down')), true) : [];
    $retryUntil = $downData['retry'] ?? null;
    $secret = $downData['secret'] ?? null;
    $allowedIps = $downData['allowed'] ?? [];
    $message = $downData['message'] ?? '';
@endphp

<div class="max-w-4xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Texniki xidmət rejimi</h1>
            <p class="text-sm text-slate-500 mt-1">
                Saytı müvəqqəti bağlayın və ya açın
            </p>
        </div>
        <a href="{{ route('admin.settings') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Geri
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 px-5 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 px-5 py-3 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Status kartı --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
        <div class="p-6">
            <div class="flex items-center justify-between gap-6 flex-wrap">

                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0
                                {{ $isDown ? 'bg-amber-100' : 'bg-emerald-100' }}">
                        @if ($isDown)
                            <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        @else
                            <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @endif
                    </div>

                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            @if ($isDown)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    AKTİV
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    DEAKTİV
                                </span>
                            @endif
                        </div>
                        <h2 class="text-lg font-bold text-slate-900">
                            {{ $isDown ? 'Sayt bağlıdır' : 'Sayt işləyir' }}
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            {{ $isDown ? 'İstifadəçilər texniki xidmət səhifəsini görür' : 'Sayt normal işləyir' }}
                        </p>
                    </div>
                </div>

                @if ($isDown)
                    <form method="POST" action="{{ route('admin.settings.maintenance.disable') }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition shadow-lg shadow-emerald-600/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Saytı aç
                        </button>
                    </form>
                @endif
            </div>

            @if ($isDown && $retryUntil)
                <div class="mt-5 pt-5 border-t border-slate-100">
                    <div class="flex items-center gap-2 text-sm text-slate-600">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Avtomatik açılış:</span>
                        <strong class="text-slate-900">
                            {{ \Carbon\Carbon::createFromTimestamp($retryUntil)->format('d.m.Y H:i') }}
                        </strong>
                        <span class="text-slate-400">({{ \Carbon\Carbon::createFromTimestamp($retryUntil)->diffForHumans() }})</span>
                    </div>
                </div>
            @endif

            @if ($isDown && $secret)
                <div class="mt-3 flex items-center gap-2 text-sm text-slate-600 flex-wrap">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    <span>Bypass link:</span>
                    <code class="px-2 py-1 rounded-lg bg-slate-100 font-mono text-xs text-indigo-700 select-all">
                        {{ url('/' . $secret) }}
                    </code>
                </div>
            @endif

            @if ($isDown && !empty($allowedIps))
                <div class="mt-3 flex items-start gap-2 text-sm text-slate-600">
                    <svg class="w-4 h-4 text-emerald-500 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <div>
                        <span>İcazəli IP-lər:</span>
                        <div class="flex flex-wrap gap-1.5 mt-1">
                            @foreach ($allowedIps as $ip)
                                <code class="px-2 py-0.5 rounded bg-slate-100 font-mono text-xs text-slate-700">{{ $ip }}</code>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Aktivləşdirmə formu --}}
    @if (!$isDown)
        <form method="POST" action="{{ route('admin.settings.maintenance.enable') }}">
            @csrf

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-amber-50 to-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <h3 class="font-semibold text-slate-800">Texniki xidməti aktivləşdir</h3>
                </div>

                <div class="p-6 space-y-5">

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Mesaj (istifadəçilərə göstərilir)
                        </label>
                        <textarea name="message" rows="3"
                                  placeholder="Sayt hazırda texniki yenilənmə mərhələsindədir..."
                                  class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition"></textarea>
                        <p class="text-[11px] text-slate-400 mt-1">Boş buraxsanız, standart mesaj göstəriləcək</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Avtomatik açılış (dəqiqə)
                        </label>
                        <input type="number" name="retry" min="1" max="1440" value="60"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                        <p class="text-[11px] text-slate-400 mt-1">
                            Neçə dəqiqə sonra sayt avtomatik açılsın (1-1440)
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            İcazəli IP-lər <span class="text-slate-400 font-normal">(vergüllə ayırın)</span>
                        </label>
                        <input type="text" name="allowed_ips"
                               placeholder="127.0.0.1, 192.168.1.1"
                               value="{{ request()->ip() }}"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                        <p class="text-[11px] text-slate-400 mt-1">
                            Bu IP-lər saytı normal görəcək (öz IP-niz avtomatik əlavə olunub)
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-4 flex-wrap">
                        <div class="flex items-start gap-2 text-xs text-slate-500 max-w-md">
                            <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Sayt bağlı olanda istifadəçilər texniki xidmət səhifəsini görəcək. Admin panel işləyəcək.</span>
                        </div>

                        <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold transition shadow-lg shadow-amber-500/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Saytı bağla
                        </button>
                    </div>

                </div>
            </div>
        </form>
    @endif

</div>

@endsection
