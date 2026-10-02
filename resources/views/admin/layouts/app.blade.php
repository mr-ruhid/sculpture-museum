<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass { backdrop-filter: blur(12px); background: rgba(255,255,255,0.85); }
        .nav-pill { transition: all .25s cubic-bezier(.4,0,.2,1); }
        .nav-pill:hover { transform: translateY(-1px); }
        .gradient-text { background: linear-gradient(135deg, #3b82f6, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

<div class="bg-white border-b border-slate-200 sticky top-0 z-40">
    <div class="px-8 h-16 flex items-center justify-between">
        <div class="flex items-center gap-8">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/25">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 21v-7m0 0V9a2 2 0 012-2h2m-4 6h4m12 8v-7m0 0V9a2 2 0 00-2-2h-2m4 6h-4M12 3v18"/>
                    </svg>
                </div>
                <div class="leading-tight">
                    <div class="text-sm font-bold gradient-text">Azərbaycan Heykəlləri</div>
                    <div class="text-[10px] text-slate-400 font-medium tracking-wider uppercase">Admin Panel</div>
                </div>
            </div>

            <nav class="hidden md:flex items-center gap-1">
                @php
                    $tabs = [
                        ['route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'label' => 'Ana səhifə'],
                        ['route' => 'admin.wikis.index', 'pattern' => 'admin.wikis.*', 'label' => 'Wikilər'],
                        ['route' => 'admin.settings', 'pattern' => 'admin.settings*', 'label' => 'Ayarlar'],
                        ['route' => 'admin.about', 'pattern' => 'admin.about', 'label' => 'Haqqında'],
                    ];
                @endphp
                @foreach ($tabs as $tab)
                    @php $active = request()->routeIs($tab['pattern']); @endphp
                    <a href="{{ route($tab['route']) }}"
                       class="nav-pill px-4 py-2 text-sm font-semibold rounded-full
                              {{ $active ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'text-slate-600 hover:bg-slate-100' }}">
                        {{ $tab['label'] }}
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="flex items-center gap-2">
            <button class="w-9 h-9 rounded-full hover:bg-slate-100 flex items-center justify-center text-slate-500 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </button>

            <div class="w-px h-6 bg-slate-200 mx-1"></div>

            <div class="flex items-center gap-2 px-2 py-1 rounded-full hover:bg-slate-100 transition cursor-pointer">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="text-sm font-medium text-slate-700 pr-1">{{ auth()->user()->name }}</div>
            </div>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit"
                        class="w-9 h-9 rounded-full hover:bg-red-50 flex items-center justify-center text-slate-500 hover:text-red-600 transition"
                        title="Çıxış">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </div>
</div>

<main class="px-8 py-8">
    @yield('content')
</main>

</body>
</html>
