
@extends('admin.layouts.app')

@section('title', 'Profil')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl">

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Şifrə dəyişdir</h2>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.profile.password') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari şifrə</label>
                    <input type="password" name="current_password" required
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Yeni şifrə</label>
                    <input type="password" name="password" required
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Yeni şifrə (təkrar)</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>

                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded text-sm hover:bg-blue-700">
                    Yenilə
                </button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">İki faktorlu doğrulama (2FA)</h2>

            <p class="text-sm text-gray-600 mb-4">
                Aktiv olduqda, hər girişdə e-poçtunuza 6 rəqəmli kod göndəriləcək.
            </p>

            <form method="POST" action="{{ route('admin.profile.twofactor') }}">
                @csrf

                <label class="flex items-center gap-2 mb-4">
                    <input type="checkbox" name="two_factor_enabled" value="1"
                           {{ $user->two_factor_enabled ? 'checked' : '' }}
                           class="w-4 h-4">
                    <span class="text-sm text-gray-700">2FA aktiv olsun</span>
                </label>

                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded text-sm hover:bg-blue-700">
                    Yadda saxla
                </button>
            </form>
        </div>

    </div>
@endsection
