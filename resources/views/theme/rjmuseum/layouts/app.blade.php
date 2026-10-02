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
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass { backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); background: rgba(255,255,255,0.75); }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-up { animation: fadeUp 1s cubic-bezier(.4,0,.2,1) forwards; }
        .delay-1 { animation-delay: .2s; opacity: 0; }
        .delay-2 { animation-delay: .4s; opacity: 0; }
        .delay-3 { animation-delay: .6s; opacity: 0; }
        .card-hover { transition: all .5s cubic-bezier(.4,0,.2,1); }
        .card-hover:hover { transform: translateY(-8px); box-shadow: 0 30px 60px -15px rgba(0,0,0,0.2); }
        .card-hover:hover img { transform: scale(1.08); }
        .card-hover img { transition: transform .7s cubic-bezier(.4,0,.2,1); }
    </style>
    @stack('styles')
</head>
<body class="scroll-smooth bg-white text-slate-800">

@include('theme.rjmuseum.widgets.header')

<main>
    @yield('content')
</main>

@include('theme.rjmuseum.widgets.footer')

<script>
    const header = document.getElementById('site-header');
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('glass', 'shadow-lg', 'shadow-slate-900/5');
            } else {
                header.classList.remove('glass', 'shadow-lg', 'shadow-slate-900/5');
            }
        });
    }
</script>

@stack('scripts')
</body>
</html>
