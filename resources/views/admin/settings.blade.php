@extends('admin.layouts.app')

@section('title', 'Ayarlar')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-5xl">

        <a href="{{ route('admin.settings.smtp') }}"
           class="bg-white rounded-lg shadow p-6 hover:shadow-md transition">
            <div class="text-3xl mb-3">📧</div>
            <h3 class="text-lg font-semibold text-gray-800 mb-1">SMTP</h3>
            <p class="text-sm text-gray-500">E-poçt göndərişi ayarları</p>
        </a>

        <a href="{{ route('admin.languages.index') }}"
           class="bg-white rounded-lg shadow p-6 hover:shadow-md transition">
            <div class="text-3xl mb-3">🌐</div>
            <h3 class="text-lg font-semibold text-gray-800 mb-1">Dillər</h3>
            <p class="text-sm text-gray-500">Dil idarəsi və tərcümələr</p>
        </a>

        <a href="{{ route('admin.profile.index') }}"
           class="bg-white rounded-lg shadow p-6 hover:shadow-md transition">
            <div class="text-3xl mb-3">👤</div>
            <h3 class="text-lg font-semibold text-gray-800 mb-1">Profil</h3>
            <p class="text-sm text-gray-500">Şifrə və 2FA ayarları</p>
        </a>

        <div class="bg-white rounded-lg shadow p-6 opacity-50 cursor-not-allowed">
            <div class="text-3xl mb-3">⚡</div>
            <h3 class="text-lg font-semibold text-gray-800 mb-1">Keş</h3>
            <p class="text-sm text-gray-500">Tezliklə</p>
        </div>

    </div>
@endsection
