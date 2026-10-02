<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .nav-link { transition: all .2s ease; }
        .nav-link:hover { transform: translateX(3px); }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen">

<div class="flex min-h-screen">

    <aside class="w-64 bg-slate-900 flex-shrink-0 flex flex-col fixed h-screen">
        <div class="h-16 flex items-center gap-3 px-5 border-b border-slate-800">
            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 21v-7m0 0V9a2 2 0 012-2h2m-4 6h4m12 8v-7m0 0V9a2 2 0 00-2-2h-2m4 6h-4M12 3v18"/>
                </svg>
            </div>
            <div>
                <div class="text-sm font-bold text-white leading-tight">Azərbaycan</div>
                <div class="text-[11px] text-blue-400 leading-tight">Heykəlləri</div>
            </div>
        </div>

        <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
            @php
                $tabs = [
                    ['route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'label' => 'Ana səhifə', 'icon' => 'home'],
                    ['route' => 'admin.wikis.index', 'pattern' => 'admin.wikis.*', 'label' => 'Wikilər', 'icon' => 'wiki'],
                    ['route' => 'admin.settings', 'pattern' => 'admin.settings*', 'label' => 'Ayarlar', 'icon' => 'settings'],
                    ['route' => 'admin.about', 'pattern' => 'admin.about', 'label' => 'Haqqında', 'icon' => 'info'],
                ];
            @endphp

            @foreach ($tabs as $tab)
                @php $active = request()->routeIs($tab['pattern']); @endphp
                <a href="{{ route($tab['route']) }}"
                   class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                          {{ $active ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    @if ($tab['icon'] === 'home')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3m10-11v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    @elseif ($tab['icon'] === 'wiki')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    @elseif ($tab['icon'] === 'settings')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @endif
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="border-t border-slate-800 p-3">
            <div class="flex items-center gap-3 px-2 py-2 mb-2">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-sm font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <div class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</div>
                    <div class="text-[11px] text-slate-500">Administrator</div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 text-sm text-slate-400 hover:text-red-400 px-3 py-2 rounded-lg hover:bg-slate-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Çıxış
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 ml-64">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-30">
            <h1 class="text-lg font-semibold text-slate-800">@yield('title')</h1>
            <div class="text-sm text-slate-400">{{ now()->format('d.m.Y') }}</div>
        </header>

        <main class="p-8">
            @yield('content')
        </main>
    </div>

</div>

</body>
</html>
