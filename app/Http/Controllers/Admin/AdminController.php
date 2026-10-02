<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Sculpture;
use App\Models\SculptureImage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total' => Sculpture::count(),
            'published' => Sculpture::where('is_published', true)->count(),
            'draft' => Sculpture::where('is_published', false)->count(),
            'cities' => Sculpture::whereHas('translations', function ($q) {
                $q->whereNotNull('city')->where('city', '!=', '');
            })->distinct('id')->count('id'),
            'languages' => Language::where('is_active', true)->count(),
            'images' => SculptureImage::count(),
        ];

        $recent = Sculpture::with('translations')->latest()->take(5)->get();

        $byCity = Sculpture::whereHas('translations', function ($q) {
            $q->where('locale', 'en')->whereNotNull('city')->where('city', '!=', '');
        })->with(['translations' => function ($q) {
            $q->where('locale', 'en');
        }])->get()
            ->groupBy(fn ($s) => $s->translation('en')?->city)
            ->map->count()
            ->sortDesc()
            ->take(5);

        return view('admin.dashboard', compact('stats', 'recent', 'byCity'));
    }

    public function settings()
    {
        return view('admin.settings');
    }
}
