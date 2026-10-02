<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', \App\Models\Setting::get('site_name_' . app()->getLocale(), config('app.name')))</title>
    <meta name="description" content="@yield('meta_description', \App\Models\Setting::get('meta_description_' . app()->getLocale(), ''))">
    <meta name="keywords" content="@yield('meta_keywords', \App\Models\Setting::get('meta_keywords_' . app()->getLocale(), ''))">

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
