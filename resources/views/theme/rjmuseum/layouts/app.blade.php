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
    @endphp

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

    @stack('styles')
</head>
<body class="bg-white text-slate-800">

@include('theme.rjmuseum.widgets.header')

<main>
    @yield('content')
</main>

@include('theme.rjmuseum.widgets.footer')

<script src="{{ url('theme/js/app.js') }}"></script>

@stack('scripts')
</body>
</html>
