@extends('admin.layouts.app')

@section('title', 'Ayarlar')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

        <a href="{{ route('admin.settings.general') }}"
           class="group bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md hover:border-indigo-200 transition">
            <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center mb-4 group-hover:bg-slate-100 transition">
                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-slate-800 mb-1">Ümumi</h3>
            <p class="text-sm text-slate-500">Sayt adı, logo, favicon</p>
        </a>

        <a href="{{ route('admin.settings.contact') }}"
           class="group bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md hover:border-indigo-200 transition">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center mb-4 group-hover:bg-emerald-100 transition">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-slate-800 mb-1">Əlaqə</h3>
            <p class="text-sm text-slate-500">Telefon, ünvan, xəritə</p>
        </a>

        <a href="{{ route('admin.settings.social') }}"
           class="group bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md hover:border-indigo-200 transition">
            <div class="w-12 h-12 rounded-xl bg-pink-50 flex items-center justify-center mb-4 group-hover:bg-pink-100 transition">
                <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-slate-800 mb-1">Sosial</h3>
            <p class="text-sm text-slate-500">Facebook, Instagram, YouTube</p>
        </a>

        <a href="{{ route('admin.settings.seo') }}"
           class="group bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md hover:border-indigo-200 transition">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center mb-4 group-hover:bg-blue-100 transition">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-slate-800 mb-1">SEO</h3>
            <p class="text-sm text-slate-500">Meta, analytics, robots</p>
        </a>

        <a href="{{ route('admin.settings.homepage') }}"
           class="group bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md hover:border-indigo-200 transition">
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center mb-4 group-hover:bg-amber-100 transition">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-slate-800 mb-1">Ana səhifə</h3>
            <p class="text-sm text-slate-500">Hero, statistika</p>
        </a>

        <a href="{{ route('admin.settings.smtp') }}"
           class="group bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md hover:border-indigo-200 transition">
            <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center mb-4 group-hover:bg-red-100 transition">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-slate-800 mb-1">SMTP</h3>
            <p class="text-sm text-slate-500">E-poçt göndərişi</p>
        </a>

        <a href="{{ route('admin.languages.index') }}"
           class="group bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md hover:border-indigo-200 transition">
            <div class="w-12 h-12 rounded-xl bg-cyan-50 flex items-center justify-center mb-4 group-hover:bg-cyan-100 transition">
                <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-slate-800 mb-1">Dillər</h3>
            <p class="text-sm text-slate-500">Dil idarəsi və tərcümələr</p>
        </a>

        <a href="{{ route('admin.profile.index') }}"
           class="group bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md hover:border-indigo-200 transition">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center mb-4 group-hover:bg-indigo-100 transition">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-slate-800 mb-1">Profil</h3>
            <p class="text-sm text-slate-500">Şifrə və 2FA</p>
        </a>

        <a href="{{ route('admin.cache.index') }}"
           class="group bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md hover:border-indigo-200 transition">
            <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center mb-4 group-hover:bg-orange-100 transition">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-slate-800 mb-1">Keş</h3>
            <p class="text-sm text-slate-500">Keş idarəsi</p>
        </a>

    </div>
@endsection
