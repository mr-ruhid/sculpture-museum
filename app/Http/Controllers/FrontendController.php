<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Sculpture;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function home()
    {
        return view('theme.rjmuseum.pages.home');
    }

    public function sculptures(Request $request)
    {
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
                $q->where('locale', 'en')->whereNotNull('city')->where('city', '!=', '');
            })
            ->with(['translations' => fn ($q) => $q->where('locale', 'en')])
            ->get()
            ->pluck('translations.0.city')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $styles = Sculpture::where('is_published', true)
            ->whereHas('translations', function ($q) {
                $q->where('locale', 'en')->whereNotNull('style')->where('style', '!=', '');
            })
            ->with(['translations' => fn ($q) => $q->where('locale', 'en')])
            ->get()
            ->pluck('translations.0.style')
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

    public function sculptureShow($slug)
    {
        $sculpture = Sculpture::with(['translations', 'images'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $related = Sculpture::with('translations')
            ->where('is_published', true)
            ->where('id', '!=', $sculpture->id)
            ->whereHas('translations', function ($q) use ($sculpture) {
                $city = $sculpture->translation('en')?->city;
                if ($city) {
                    $q->where('city', $city);
                }
            })
            ->take(3)
            ->get();

        return view('theme.rjmuseum.pages.sculpture', compact('sculpture', 'related'));
    }

    public function about()
    {
        return view('theme.rjmuseum.pages.about');
    }

    public function contact()
    {
        return view('theme.rjmuseum.pages.contact');
    }

    public function switchLang($code)
    {
        if (Language::where('code', $code)->where('is_active', true)->exists()) {
            session(['locale' => $code]);
        }

        return redirect()->back();
    }
}
