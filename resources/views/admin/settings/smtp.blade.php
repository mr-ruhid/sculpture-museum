
@extends('admin.layouts.app')

@section('title', 'SMTP ayarları')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-3xl">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">SMTP ayarları</h2>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.smtp.update') }}">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Host</label>
                    <input type="text" name="smtp_host" value="{{ $settings['smtp_host'] ?? '' }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Port</label>
                    <input type="text" name="smtp_port" value="{{ $settings['smtp_port'] ?? '' }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="smtp_username" value="{{ $settings['smtp_username'] ?? '' }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="smtp_password" value="{{ $settings['smtp_password'] ?? '' }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Encryption</label>
                    <select name="smtp_encryption"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                        <option value="tls" {{ ($settings['smtp_encryption'] ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ ($settings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                        <option value="none" {{ ($settings['smtp_encryption'] ?? '') === 'none' ? 'selected' : '' }}>None</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From address</label>
                    <input type="email" name="smtp_from_address" value="{{ $settings['smtp_from_address'] ?? '' }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">From name</label>
                    <input type="text" name="smtp_from_name" value="{{ $settings['smtp_from_name'] ?? '' }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <button type="submit"
                    class="mt-6 bg-blue-600 text-white px-6 py-2 rounded text-sm hover:bg-blue-700">
                Yadda saxla
            </button>
        </form>
    </div>
@endsection
