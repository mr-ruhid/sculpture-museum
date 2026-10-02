<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale');

        if (!$locale || !in_array($locale, Language::active()->pluck('code')->toArray())) {
            $locale = Language::getDefault();
        }

        App::setLocale($locale);

        return $next($request);
    }
}
