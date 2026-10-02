<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between py-4">
            <h1 class="text-lg font-semibold text-gray-800">{{ config('app.name') }}</h1>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                    {{ __('admin.logout') }}
                </button>
            </form>
        </div>
        <nav class="flex gap-1 -mb-px">
            <a href="{{ route('admin.dashboard') }}"
               class="px-4 py-2 text-sm font-medium border-b-2 {{ request()->routeIs('admin.dashboard') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
                {{ __('admin.home') }}
            </a>
            <a href="{{ route('admin.wikis.index') }}"
               class="px-4 py-2 text-sm font-medium border-b-2 {{ request()->routeIs('admin.wikis.*') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
                {{ __('admin.wikis') }}
            </a>
            <a href="{{ route('admin.settings') }}"
               class="px-4 py-2 text-sm font-medium border-b-2 {{ request()->routeIs('admin.settings') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
                {{ __('admin.account_settings') }}
            </a>
            <a href="{{ route('admin.about') }}"
               class="px-4 py-2 text-sm font-medium border-b-2 {{ request()->routeIs('admin.about') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
                {{ __('admin.about') }}
            </a>
        </nav>
    </div>
</div>

<main class="max-w-7xl mx-auto px-6 py-8">
    @yield('content')
</main>

</body>
</html>
