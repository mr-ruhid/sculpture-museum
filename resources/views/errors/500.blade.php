@php
    $segments = request()->segments();
    $locale = $segments[0] ?? 'az';
    if (!in_array($locale, ['az', 'en', 'ru', 'ka'])) {
        $locale = 'az';
    }

    $texts = [
        'az' => [
            'title' => 'Nəsə səhv getdi',
            'text' => 'Texniki problem yaşayırıq. Zəhmət olmasa bir az sonra yenidən cəhd edin və ya ana səhifəyə qayıdın.',
            'home' => 'Ana səhifəyə',
            'retry' => 'Yenidən cəhd et',
            'lang' => 'az',
        ],
        'en' => [
            'title' => 'Something went wrong',
            'text' => "We're experiencing a technical issue. Please try again in a moment or return to the homepage.",
            'home' => 'Back to home',
            'retry' => 'Try again',
            'lang' => 'en',
        ],
        'ru' => [
            'title' => 'Что-то пошло не так',
            'text' => 'Мы испытываем технические проблемы. Пожалуйста, попробуйте ещё раз через мгновение или вернитесь на главную.',
            'home' => 'На главную',
            'retry' => 'Попробовать снова',
            'lang' => 'ru',
        ],
        'ka' => [
            'title' => 'რაღაც შეცდომა მოხდა',
            'text' => 'ჩვენ ტექნიკურ პრობლემას განვიცდით. გთხოვთ, სცადოთ ცოტა ხანში ან დაბრუნდით მთავარ გვერდზე.',
            'home' => 'მთავარ გვერდზე',
            'retry' => 'კიდევ სცადეთ',
            'lang' => 'ka',
        ],
    ];

    $t = $texts[$locale];
@endphp
<!DOCTYPE html>
<html lang="{{ $t['lang'] }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Server Error</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { margin: 0; }

        .err-stage {
            position: relative;
            min-height: 100vh;
            background: radial-gradient(ellipse at 50% 40%, #1e293b 0%, #0f172a 55%, #020617 100%);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1.5rem;
        }

        .err-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse at center, black 30%, transparent 75%);
            -webkit-mask-image: radial-gradient(ellipse at center, black 30%, transparent 75%);
            animation: errGridMove 30s linear infinite;
        }

        @keyframes errGridMove {
            to { background-position: 60px 60px, 60px 60px; }
        }

        .err-glow {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(239,68,68,.35) 0%, transparent 70%);
            filter: blur(60px);
            pointer-events: none;
            animation: errFloat 12s ease-in-out infinite;
        }

        .err-glow.g2 {
            background: radial-gradient(circle, rgba(249,115,22,.25) 0%, transparent 70%);
            width: 400px;
            height: 400px;
            right: 5%;
            animation-delay: -6s;
        }

        @keyframes errFloat {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(40px, -30px); }
        }

        .err-content {
            position: relative;
            z-index: 5;
            text-align: center;
            max-width: 720px;
        }

        .err-num {
            position: relative;
            font-size: clamp(7rem, 20vw, 16rem);
            font-weight: 900;
            line-height: .85;
            letter-spacing: -.06em;
            color: transparent;
            -webkit-text-stroke: 3px rgba(255,255,255,.85);
            margin-bottom: 1.5rem;
            animation: errPulse 4s ease-in-out infinite;
        }

        .err-num::after {
            content: '500';
            position: absolute;
            inset: 0;
            color: transparent;
            -webkit-text-stroke: 3px rgba(239,68,68,1);
            filter: blur(10px);
            opacity: .7;
            animation: errGlow 4s ease-in-out infinite;
        }

        @keyframes errPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        @keyframes errGlow {
            0%, 100% { opacity: .4; }
            50% { opacity: 1; }
        }

        .err-title {
            font-size: clamp(1.5rem, 3.5vw, 2.5rem);
            font-weight: 900;
            color: #fff;
            letter-spacing: -.02em;
            margin-bottom: 1rem;
            opacity: 0;
            animation: errFadeUp .8s cubic-bezier(.4,0,.2,1) .2s forwards;
        }

        .err-text {
            font-size: 1rem;
            color: rgba(255,255,255,.55);
            line-height: 1.7;
            max-width: 480px;
            margin: 0 auto 2.5rem;
            opacity: 0;
            animation: errFadeUp .8s cubic-bezier(.4,0,.2,1) .35s forwards;
        }

        @keyframes errFadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .err-statue {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: clamp(180px, 25vw, 320px);
            opacity: .12;
            pointer-events: none;
            z-index: 1;
            animation: errStatue 8s ease-in-out infinite;
            color: #fff;
        }

        @keyframes errStatue {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50% { transform: translateX(-50%) translateY(-12px); }
        }

        .err-actions {
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
            justify-content: center;
            opacity: 0;
            animation: errFadeUp .8s cubic-bezier(.4,0,.2,1) .5s forwards;
        }

        .err-btn {
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            padding: .9rem 1.75rem;
            border-radius: 999px;
            font-weight: 600;
            font-size: .9rem;
            transition: all .35s cubic-bezier(.4,0,.2,1);
            text-decoration: none;
            border: 0;
            cursor: pointer;
        }

        .err-btn-primary {
            background: #fff;
            color: #0f172a;
        }

        .err-btn-primary:hover {
            background: #ef4444;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(239,68,68,.35);
        }

        .err-btn-ghost {
            background: rgba(255,255,255,.08);
            color: rgba(255,255,255,.85);
            border: 1px solid rgba(255,255,255,.15);
            backdrop-filter: blur(10px);
        }

        .err-btn-ghost:hover {
            background: rgba(255,255,255,.15);
            color: #fff;
            transform: translateY(-2px);
        }

        .err-ghost-num {
            position: absolute;
            font-size: clamp(14rem, 40vw, 34rem);
            font-weight: 900;
            line-height: .8;
            color: transparent;
            -webkit-text-stroke: 1.5px rgba(255,255,255,.06);
            pointer-events: none;
            user-select: none;
            z-index: 0;
        }

        .err-ghost-num.left { top: 5%; left: -8%; }
        .err-ghost-num.right { bottom: 5%; right: -8%; }

        @media (max-width: 640px) {
            .err-num { -webkit-text-stroke-width: 2px; }
            .err-num::after { -webkit-text-stroke-width: 2px; }
            .err-ghost-num { display: none; }
        }
    </style>
</head>
<body>

<section class="err-stage">

    <div class="err-grid"></div>
    <div class="err-glow"></div>
    <div class="err-glow g2"></div>

    <div class="err-ghost-num left" aria-hidden="true">50</div>
    <div class="err-ghost-num right" aria-hidden="true">50</div>

    <svg class="err-statue" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 21v-7m0 0V9a2 2 0 012-2h2m-4 6h4m12 8v-7m0 0V9a2 2 0 00-2-2h-2m4 6h-4M12 3v18"/>
    </svg>

    <div class="err-content">

        <div class="err-num" aria-label="500">500</div>

        <h1 class="err-title">{{ $t['title'] }}</h1>

        <p class="err-text">{{ $t['text'] }}</p>

        <div class="err-actions">
            <a href="/{{ $locale }}" class="err-btn err-btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10"/>
                </svg>
                {{ $t['home'] }}
            </a>

            <button type="button" onclick="window.location.reload()" class="err-btn err-btn-ghost">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                {{ $t['retry'] }}
            </button>
        </div>

    </div>
</section>

</body>
</html>
