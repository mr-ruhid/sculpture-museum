@extends('admin.layouts.app')

@section('title', 'Keş')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-3xl">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Keş idarəsi</h2>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <form method="POST" action="{{ route('admin.cache.clear') }}">
                @csrf
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-1">Tətbiq keşi</h3>
                    <p class="text-sm text-gray-500 mb-3">Bütün keşlənmiş məlumatları təmizləyir.</p>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                        Təmizlə
                    </button>
                </div>
            </form>

            <form method="POST" action="{{ route('admin.cache.config') }}">
                @csrf
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-1">Konfiqurasiya keşi</h3>
                    <p class="text-sm text-gray-500 mb-3">Config fayllarının keşini yeniləyir.</p>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                        Yenilə
                    </button>
                </div>
            </form>

            <form method="POST" action="{{ route('admin.cache.route') }}">
                @csrf
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-1">Route keşi</h3>
                    <p class="text-sm text-gray-500 mb-3">Route keşini yeniləyir.</p>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                        Yenilə
                    </button>
                </div>
            </form>

            <form method="POST" action="{{ route('admin.cache.view') }}">
                @csrf
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-1">View keşi</h3>
                    <p class="text-sm text-gray-500 mb-3">Blade şablonlarının keşini təmizləyir.</p>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                        Təmizlə
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection
