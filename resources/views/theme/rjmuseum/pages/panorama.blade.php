<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sculpture->translation()?->title }} — 360°</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; margin: 0; overflow: hidden; }
        #panorama-frame {
            position: fixed;
            top: 64px;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: calc(100vh - 64px);
            border: 0;
        }
        #panorama-frame iframe,
        #panorama-frame > * {
            width: 100%;
            height: 100%;
            border: 0;
        }
    </style>
</head>
<body class="bg-slate-950">

<header class="fixed top-0 left-0 right-0 h-16 z-50" style="background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(20px) saturate(180%); -webkit-backdrop-filter: blur(20px) saturate(180%); border-bottom: 1px solid rgba(255, 255, 255, 0.08); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);">
    <div class="h-full px-6 flex items-center justify-between">

        <div class="flex items-center gap-3 min-w-0">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); border: 2px solid #fff; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.5);">
                <svg class="w-6 h-6" fill="none" stroke="#ffffff" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 21v-7m0 0V9a2 2 0 012-2h2m-4 6h4m12 8v-7m0 0V9a2 2 0 00-2-2h-2m4 6h-4M12 3v18"/>
                </svg>
            </div>
            <div class="min-w-0">
                <div class="text-sm font-bold text-white truncate">
                    {{ $sculpture->translation()?->title }}
                </div>
                <div class="text-[11px] text-indigo-400 font-medium tracking-wider uppercase">360°</div>
            </div>
        </div>

        <a href="{{ url('/' . app()->getLocale() . '/sculptures/' . $sculpture->slug) }}"
           class="group flex items-center gap-2 pl-3 pr-4 py-1.5 rounded-full bg-white/10 backdrop-blur border border-white/20 hover:bg-white/20 transition-all duration-300"
           title="{{ __('frontend.close') }}">
            <span class="text-sm font-semibold text-white hidden sm:block">{{ __('frontend.close') }}</span>
            <svg class="w-4 h-4 text-white/70 group-hover:text-white transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </a>

    </div>
</header>

<div id="panorama-frame">
    {!! $sculpture->panorama_embed !!}
</div>

</body>
</html>
