<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Sculpture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class FrontendController extends Controller
{
    public function home($locale)
    {
        App::setLocale($locale);
        return view('theme.rjmuseum.pages.home');
    }

    public function sculptures(Request $request, $locale)
    {
        App::setLocale($locale);

        $query = Sculpture::with('translations')->where('is_published', true);

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->whereHas('translations', function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('sculptor', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%");
            });
        }

        if ($request->filled('city')) {
            $city = $request->input('city');
            $query->whereHas('translations', function ($sub) use ($city) {
                $sub->where('city', $city);
            });
        }

        if ($request->filled('year')) {
            $query->where('year', $request->input('year'));
        }

        if ($request->filled('style')) {
            $style = $request->input('style');
            $query->whereHas('translations', function ($sub) use ($style) {
                $sub->where('style', $style);
            });
        }

        $sculptures = $query->latest()->paginate(12)->withQueryString();

        $cities = Sculpture::where('is_published', true)
            ->whereHas('translations', function ($q) {
                $q->whereNotNull('city')->where('city', '!=', '');
            })
            ->with('translations')
            ->get()
            ->map(fn ($s) => $s->translation('en')?->city)
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $styles = Sculpture::where('is_published', true)
            ->whereHas('translations', function ($q) {
                $q->whereNotNull('style')->where('style', '!=', '');
            })
            ->with('translations')
            ->get()
            ->map(fn ($s) => $s->translation('en')?->style)
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $years = Sculpture::where('is_published', true)
            ->whereNotNull('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return view('theme.rjmuseum.pages.sculptures', compact('sculptures', 'cities', 'styles', 'years'));
    }

    public function sculptureShow($locale, $slug)
    {
        App::setLocale($locale);

        $sculpture = Sculpture::with(['translations', 'images'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $related = Sculpture::with('translations')
            ->where('is_published', true)
            ->where('id', '!=', $sculpture->id)
            ->take(3)
            ->get();

        return view('theme.rjmuseum.pages.sculpture', compact('sculpture', 'related'));
    }

    public function about($locale)
    {
        App::setLocale($locale);
        return view('theme.rjmuseum.pages.about');
    }

    public function contact($locale)
    {
        App::setLocale($locale);
        return view('theme.rjmuseum.pages.contact');
    }

    public function switchLang($code)
    {
        if (Language::where('code', $code)->where('is_active', true)->exists()) {
            session(['locale' => $code]);
            return redirect()->to('/' . $code);
        }

        return redirect()->back();
    }
}
