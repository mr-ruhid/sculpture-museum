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
            <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); border: 2px solid #fff; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.5);">
                <svg style="width: 22px; height: 22px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 800">
                    <g fill="#ffffff">
                        <path d="M352 400 L448 400 L442 688 L358 688 Z"/>
                        <path d="M380 230 C330 240 300 300 306 410 L320 495 L360 512 L440 512 L480 495 L494 410 C500 300 470 240 420 230 Z"/>
                        <path d="M375 230 L425 230 L400 290 Z" fill="none" stroke="#6366f1" stroke-width="6"/>
                        <rect x="375" y="200" width="50" height="40"/>
                        <path d="M375 230 L400 250 L425 230 Z" fill="none" stroke="#6366f1" stroke-width="6"/>
                        <circle cx="400" cy="165" r="45"/>
                        <circle cx="352" cy="165" r="8"/>
                        <circle cx="448" cy="165" r="8"/>
                        <path d="M355 160 C355 120 380 110 405 110 C435 110 450 130 450 155 C435 155 430 145 420 140 C410 135 390 145 375 145 C365 145 360 155 355 160 Z"/>
                        <rect x="266" y="688" width="268" height="32"/>
                        <rect x="286" y="720" width="228" height="96"/>
                        <rect x="320" y="746" width="160" height="44" fill="none" stroke="#6366f1" stroke-width="6"/>
                        <path d="M250 816 C250 790 270 776 296 776 L504 776 C530 776 550 790 550 816 Z"/>
                        <rect x="226" y="816" width="348" height="40"/>
                    </g>
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
