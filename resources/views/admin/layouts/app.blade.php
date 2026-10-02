<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen antialiased">

<header class="bg-white border-b border-slate-200 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 21v-7m0 0V9a2 2 0 012-2h2m-4 6h4m12 8v-7m0 0V9a2 2 0 00-2-2h-2m4 6h-4M12 3v18"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-semibold text-slate-800 leading-tight">{{ config('app.name') }}</div>
                    <div class="text-[11px] text-slate-400 leading-tight">İdarəetmə paneli</div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-medium text-slate-700">{{ auth()->user()->name }}</div>
                    <div class="text-[11px] text-slate-400">Administrator</div>
                </div>
                <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                            class="text-sm text-slate-500 hover:text-red-600 px-3 py-2 rounded-lg hover:bg-red-50 transition">
                        Çıxış
                    </button>
                </form>
            </div>
        </div>

        <nav class="flex gap-1 -mb-px overflow-x-auto">
            @php
                $tabs = [
                    ['route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'label' => 'Ana səhifə', 'icon' => 'home'],
                    ['route' => 'admin.sculptures.index', 'pattern' => 'admin.sculptures.*', 'label' => 'Heykəllər', 'icon' => 'sculpture'],
                    ['route' => 'admin.settings', 'pattern' => 'admin.settings*', 'label' => 'Ayarlar', 'icon' => 'settings'],
                    ['route' => 'admin.about', 'pattern' => 'admin.about', 'label' => 'Haqqında', 'icon' => 'info'],
                ];
            @endphp

            @foreach ($tabs as $tab)
                @php $active = request()->routeIs($tab['pattern']); @endphp
                <a href="{{ route($tab['route']) }}"
                   class="flex items-center gap-2 px-4 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap
                          {{ $active ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
                    @if ($tab['icon'] === 'home')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3m10-11v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    @elseif ($tab['icon'] === 'sculpture')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 21v-7m0 0V9a2 2 0 012-2h2m-4 6h4m12 8v-7m0 0V9a2 2 0 00-2-2h-2m4 6h-4M12 3v18"/></svg>
                    @elseif ($tab['icon'] === 'settings')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @endif
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </nav>
    </div>
</header>

<main class="max-w-7xl mx-auto px-6 py-8">
    @yield('content')
</main>

</body>
</html>
