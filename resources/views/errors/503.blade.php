@php
    $segments = request()->segments();
    $locale = $segments[0] ?? 'az';
    if (!in_array($locale, ['az', 'en', 'ru', 'ka'])) {
        $locale = 'az';
    }

    $retryAfter = null;
    if (file_exists(storage_path('framework/down'))) {
        $downData = json_decode(file_get_contents(storage_path('framework/down')), true);
        if (!empty($downData['retry'])) {
            $retryAfter = max(0, (int) $downData['retry'] - time());
        }
    }

    $maintLogo = null;
    try {
        $maintLogo = \App\Models\Setting::get('logo');
    } catch (\Throwable $e) {
        $maintLogo = null;
    }

    $texts = [
        'az' => [
            'title' => 'Texniki işlər aparılır',
            'text' => 'Sayt hazırda texniki yenilənmə mərhələsindədir. Zəhmət olmasa bir az sonra yenidən cəhd edin.',
            'eta' => 'Təxmini açılış',
            'retry' => 'Yenidən cəhd et',
            'lang' => 'az',
        ],
        'en' => [
            'title' => 'Under maintenance',
            'text' => 'The site is currently undergoing scheduled maintenance. Please try again in a few moments.',
            'eta' => 'Estimated return',
            'retry' => 'Try again',
            'lang' => 'en',
        ],
        'ru' => [
            'title' => 'Ведутся технические работы',
            'text' => 'Сайт находится на плановом техническом обслуживании. Пожалуйста, попробуйте позже.',
            'eta' => 'Примерное время',
            'retry' => 'Попробовать снова',
            'lang' => 'ru',
        ],
        'ka' => [
            'title' => 'მიმდინარეობს ტექნიკური სამუშაოები',
            'text' => 'საიტი ამჟამად ტექნიკური მომსახურების პროცესშია. გთხოვთ, სცადოთ ცოტა მოგვიანებით.',
            'eta' => 'სავარაუდო დაბრუნება',
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
    <title>{{ $t['title'] }}</title>
    <meta name="robots" content="noindex, nofollow">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { margin: 0; }

        .maint-stage {
            position: relative;
            min-height: 100vh;
            background: radial-gradient(ellipse at 50% 40%, #1e293b 0%, #0f172a 55%, #020617 100%);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1.5rem;
        }

        .maint-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse at center, black 30%, transparent 75%);
            -webkit-mask-image: radial-gradient(ellipse at center, black 30%, transparent 75%);
            animation: maintGridMove 30s linear infinite;
        }

        @keyframes maintGridMove {
            to { background-position: 60px 60px, 60px 60px; }
        }

        .maint-glow {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245,158,11,.35) 0%, transparent 70%);
            filter: blur(60px);
            pointer-events: none;
            animation: maintFloat 12s ease-in-out infinite;
        }

        .maint-glow.g2 {
            background: radial-gradient(circle, rgba(99,102,241,.25) 0%, transparent 70%);
            width: 400px;
            height: 400px;
            right: 5%;
            animation-delay: -6s;
        }

        @keyframes maintFloat {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(40px, -30px); }
        }

        .maint-content {
            position: relative;
            z-index: 5;
            text-align: center;
            max-width: 720px;
        }

        .maint-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            border-radius: 50%;
            background: linear-gradient(135deg, #f59e0b, #f97316);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 20px 60px rgba(245, 158, 11, 0.4);
            animation: maintPulse 3s ease-in-out infinite;
        }

        .maint-icon svg {
            width: 60px;
            height: 60px;
            color: #fff;
        }

        @keyframes maintPulse {
            0%, 100% { transform: scale(1); box-shadow: 0 20px 60px rgba(245, 158, 11, 0.4); }
            50% { transform: scale(1.05); box-shadow: 0 25px 75px rgba(245, 158, 11, 0.6); }
        }

        .maint-title {
            font-size: clamp(1.75rem, 4vw, 3rem);
            font-weight: 900;
            color: #fff;
            letter-spacing: -.02em;
            margin-bottom: 1rem;
            opacity: 0;
            animation: maintFadeUp .8s cubic-bezier(.4,0,.2,1) .2s forwards;
        }

        .maint-text {
            font-size: 1.05rem;
            color: rgba(255,255,255,.6);
            line-height: 1.7;
            max-width: 480px;
            margin: 0 auto 2rem;
            opacity: 0;
            animation: maintFadeUp .8s cubic-bezier(.4,0,.2,1) .35s forwards;
        }

        @keyframes maintFadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .maint-eta {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 24px;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.12);
            backdrop-filter: blur(20px);
            border-radius: 999px;
            color: #fff;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 2rem;
            opacity: 0;
            animation: maintFadeUp .8s cubic-bezier(.4,0,.2,1) .5s forwards;
        }

        .maint-eta-icon {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #f59e0b;
            animation: maintBlink 1.5s ease-in-out infinite;
        }

        @keyframes maintBlink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .maint-btn {
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
            background: #fff;
            color: #0f172a;
            opacity: 0;
            animation: maintFadeUp .8s cubic-bezier(.4,0,.2,1) .65s forwards;
        }

        .maint-btn:hover {
            background: #f59e0b;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(245, 158, 11, .35);
        }

        .maint-brand {
            position: absolute;
            top: 2rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
        }

        .maint-brand img {
            max-height: 40px;
            max-width: 200px;
            object-fit: contain;
        }

        .maint-statue {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: clamp(180px, 25vw, 320px);
            opacity: .08;
            pointer-events: none;
            z-index: 1;
            animation: maintStatue 8s ease-in-out infinite;
            color: #fff;
        }

        @keyframes maintStatue {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50% { transform: translateX(-50%) translateY(-12px); }
        }

        @media (max-width: 640px) {
            .maint-icon { width: 90px; height: 90px; }
            .maint-icon svg { width: 45px; height: 45px; }
        }
    </style>
</head>
<body>

<section class="maint-stage">

    <div class="maint-grid"></div>
    <div class="maint-glow"></div>
    <div class="maint-glow g2"></div>

    <svg class="maint-statue" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 21v-7m0 0V9a2 2 0 012-2h2m-4 6h4m12 8v-7m0 0V9a2 2 0 00-2-2h-2m4 6h-4M12 3v18"/>
    </svg>

    @if ($maintLogo)
        <div class="maint-brand">
            <img src="{{ asset('storage/' . $maintLogo) }}" alt="">
        </div>
    @endif

    <div class="maint-content">

        <div class="maint-icon">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>

        <h1 class="maint-title">{{ $t['title'] }}</h1>

        <p class="maint-text">{{ $t['text'] }}</p>

        @if ($retryAfter && $retryAfter > 0)
            <div class="maint-eta">
                <span class="maint-eta-icon"></span>
                <span id="maint-countdown" data-seconds="{{ $retryAfter }}">{{ $t['eta'] }}: —</span>
            </div>
        @endif

        <div>
            <button type="button" onclick="window.location.reload()" class="maint-btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                {{ $t['retry'] }}
            </button>
        </div>

    </div>
</section>

<script>
(function () {
    const el = document.getElementById('maint-countdown');
    if (!el) return;

    let seconds = parseInt(el.dataset.seconds) || 0;
    const label = el.textContent.split(':')[0];

    function pad(n) { return n < 10 ? '0' + n : '' + n; }

    function tick() {
        if (seconds <= 0) {
            el.textContent = label + ': ' + '{{ $t['retry'] }}';
            return;
        }

        const d = Math.floor(seconds / 86400);
        const h = Math.floor((seconds % 86400) / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = seconds % 60;

        let parts = [];
        if (d > 0) parts.push(d + ' gün');
        if (h > 0 || d > 0) parts.push(pad(h) + ' saat');
        parts.push(pad(m) + ' dəq');
        parts.push(pad(s) + ' san');

        el.textContent = label + ': ' + parts.join(' ');
        seconds--;
        setTimeout(tick, 1000);
    }

    tick();
})();
</script>

</body>
</html>
