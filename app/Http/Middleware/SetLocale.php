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
        if ($request->is('admin') || $request->is('admin/*')) {
            return $next($request);
        }

        $locale = $request->route('locale');

        if (!$locale || !Language::where('code', $locale)->where('is_active', true)->exists()) {
            $locale = Language::getDefault();
            return redirect()->to('/' . $locale . '/' . ltrim($request->path(), '/'));
        }

        App::setLocale($locale);
        session(['locale' => $locale]);

        return $next($request);
    }
}
