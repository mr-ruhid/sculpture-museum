<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        $siteName = \App\Models\Setting::get('site_name_' . app()->getLocale(), config('app.name'));
        $defaultTitle = $siteName;
        $defaultDesc = \App\Models\Setting::get('site_description_' . app()->getLocale(), '');
        $defaultImage = \App\Models\Setting::get('logo') ? asset('storage/' . \App\Models\Setting::get('logo')) : null;
        $currentUrl = url()->current();
        $favicon = \App\Models\Setting::get('favicon');
    @endphp

    @if ($favicon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $favicon) }}?v={{ filemtime(storage_path('app/public/' . $favicon)) }}">
        <link rel="apple-touch-icon" href="{{ asset('storage/' . $favicon) }}">
        <link rel="shortcut icon" href="{{ asset('storage/' . $favicon) }}">
    @endif

    <title>@yield('title', $defaultTitle)</title>
    <meta name="description" content="@yield('meta_description', $defaultDesc)">
    <meta name="keywords" content="@yield('meta_keywords', '')">

    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="@yield('og_title', View::getSection('title') ?: $defaultTitle)">
    <meta property="og:description" content="@yield('og_description', View::getSection('meta_description') ?: $defaultDesc)">
    <meta property="og:url" content="@yield('og_url', $currentUrl)">
    @php $ogImage = trim($__env->yieldContent('og_image')) ?: $defaultImage; @endphp
    @if ($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
    @endif
    <meta property="og:locale" content="{{ app()->getLocale() }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', View::getSection('title') ?: $defaultTitle)">
    <meta name="twitter:description" content="@yield('og_description', View::getSection('meta_description') ?: $defaultDesc)">
    @if ($ogImage)
        <meta name="twitter:image" content="{{ $ogImage }}">
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('theme/css/app.css') }}">

    <style>
        .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 90;
            width: 3rem;
            height: 3rem;
            border-radius: 999px;
            background: #0f172a;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.35);
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
            transition: opacity .35s ease, transform .35s cubic-bezier(.4,0,.2,1), visibility .35s, background .25s;
            cursor: pointer;
            border: 0;
        }
        .back-to-top.is-visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .back-to-top:hover {
            background: #6366f1;
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.45);
        }
        .back-to-top svg {
            width: 1.15rem;
            height: 1.15rem;
        }
        @media (max-width: 640px) {
            .back-to-top {
                bottom: 1.25rem;
                right: 1.25rem;
                width: 2.65rem;
                height: 2.65rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="bg-white text-slate-800">

@include('theme.rjmuseum.widgets.header')

<main>
    @yield('content')
</main>

@include('theme.rjmuseum.widgets.footer')

<button type="button" id="back-to-top" class="back-to-top" aria-label="Back to top">
    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
    </svg>
</button>

<script src="{{ url('theme/js/app.js') }}"></script>

<script>
(function () {
    const btn = document.getElementById('back-to-top');
    if (!btn) return;

    function updateVisibility() {
        if (window.scrollY > 500) {
            btn.classList.add('is-visible');
        } else {
            btn.classList.remove('is-visible');
        }
    }

    btn.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    window.addEventListener('scroll', updateVisibility, { passive: true });
    updateVisibility();
})();
</script>

@stack('scripts')
</body>
</html>
