<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Page;
use App\Models\Sculpture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class FrontendController extends Controller
{
    public function home($locale)
    {
        App::setLocale($locale);

        $sculptures = Sculpture::with('translations')
            ->where('is_published', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($s) use ($locale) {
                $tr = $s->translation($locale);
                return [
                    'id' => $s->id,
                    'slug' => $s->slug,
                    'lat' => (float) $s->latitude,
                    'lng' => (float) $s->longitude,
                    'title' => $tr?->title ?? '',
                    'city' => $tr?->city ?? '',
                    'image' => $s->main_image ? asset('storage/' . $s->main_image) : null,
                    'panorama' => $s->panorama_embed,
                    'url' => url('/' . $locale . '/sculptures/' . $s->slug),
                ];
            })
            ->values();

        return view('theme.rjmuseum.pages.home', compact('sculptures'));
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

        $related = $this->findRelatedSculptures($sculpture, $locale, 3);

        return view('theme.rjmuseum.pages.sculpture', compact('sculpture', 'related'));
    }

    protected function findRelatedSculptures(Sculpture $sculpture, string $locale, int $limit = 3)
    {
        $currentTr = $sculpture->translation($locale);
        $city = $currentTr?->city;
        $sculptor = $currentTr?->sculptor;

        $collected = collect();
        $usedIds = [$sculpture->id];

        if ($city) {
            $sameCity = Sculpture::with('translations')
                ->where('is_published', true)
                ->where('id', '!=', $sculpture->id)
                ->whereHas('translations', function ($q) use ($city) {
                    $q->where('city', $city);
                })
                ->take($limit)
                ->get();

            $collected = $collected->merge($sameCity);
            $usedIds = array_merge($usedIds, $sameCity->pluck('id')->toArray());
        }

        if ($collected->count() < $limit && $sculptor) {
            $need = $limit - $collected->count();

            $sameSculptor = Sculpture::with('translations')
                ->where('is_published', true)
                ->whereNotIn('id', $usedIds)
                ->whereHas('translations', function ($q) use ($sculptor) {
                    $q->where('sculptor', $sculptor);
                })
                ->take($need)
                ->get();

            $collected = $collected->merge($sameSculptor);
            $usedIds = array_merge($usedIds, $sameSculptor->pluck('id')->toArray());
        }

        if ($collected->count() < $limit) {
            $need = $limit - $collected->count();

            $random = Sculpture::with('translations')
                ->where('is_published', true)
                ->whereNotIn('id', $usedIds)
                ->inRandomOrder()
                ->take($need)
                ->get();

            $collected = $collected->merge($random);
        }

        return $collected->values();
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

    public function panorama($locale, $slug)
    {
        App::setLocale($locale);

        $sculpture = Sculpture::with('translations')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        if (!$sculpture->panorama_embed) {
            abort(404);
        }

        return view('theme.rjmuseum.pages.panorama', compact('sculpture'));
    }

    public function page($locale, $slug)
    {
        App::setLocale($locale);

        $page = Page::with('translations')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $customView = 'theme.rjmuseum.pages.templates.' . $slug;
        if (view()->exists($customView)) {
            return view($customView, [
                'page' => $page,
                'slug' => $slug,
            ]);
        }

        return view('theme.rjmuseum.pages._dynamic', [
            'slug' => $slug,
            'page' => $page,
        ]);
    }

    public function shortUrl(Request $request, $code)
    {
        $shortUrl = \App\Models\ShortUrl::where('code', $code)
            ->where('is_active', true)
            ->firstOrFail();

        $locale = $this->detectLocale($request);

        $target = $shortUrl->resolveUrl($locale);

        if (!$target) {
            abort(404);
        }

        $shortUrl->incrementHits();

        return redirect()->to($target, 302);
    }

    protected function detectLocale(Request $request): string
    {
        $active = Language::where('is_active', true)->pluck('code')->toArray();
        $fallback = 'en';

        $header = $request->header('Accept-Language', '');
        if (!$header) {
            return in_array($fallback, $active, true) ? $fallback : ($active[0] ?? 'en');
        }

        preg_match_all('/([a-z]{2})(?:-[A-Z]{2})?(?:;q=([0-9.]+))?/i', $header, $matches, PREG_SET_ORDER);

        $candidates = [];
        foreach ($matches as $m) {
            $lang = strtolower($m[1]);
            $q = isset($m[2]) && $m[2] !== '' ? (float) $m[2] : 1.0;
            $candidates[$lang] = max($candidates[$lang] ?? 0, $q);
        }

        arsort($candidates);

        foreach (array_keys($candidates) as $lang) {
            if (in_array($lang, $active, true)) {
                return $lang;
            }
        }

        return in_array($fallback, $active, true) ? $fallback : ($active[0] ?? 'en');
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
